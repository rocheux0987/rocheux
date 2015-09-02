<?php

class administrators {

	var $administrators_table = "?:administrators";
	var $administrators_modules_table = "?:administrators_modules";
	
	function delete($id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->administrators_table." WHERE id = ?i", $id);
		$notifications->set("The administrator was deleted successfully", "success", true);
		return true;
	}
	
	function add($administrator){
		global $notifications, $db;
		
		if ($this->exists("username", $administrator["username"])){
			$notifications->set("<b>Error:</b> The username ".$administrator["username"]." already exists.", "error", true);
			return false;
		}
		if ($this->exists("email", $administrator["email"])){
			$notifications->set("<b>Error:</b> The email ".$administrator["email"]." already exists.", "error", true);
			return false;
		}
		
		if (!empty($administrator["modules"])){
			$administrator_modules = "|";
			foreach ($administrator["modules"] as $key => $value){
				$administrator_modules.=$key."|";
			}
		}
		
		$db->db_query("INSERT INTO ".$this->administrators_table." 
		(username, password, modules, name, lastname, email, status) 
		VALUES
		(?s, MD5(?s), ?s, ?s, ?s, ?s, ?i)
		", $administrator["username"], $administrator["password"], $administrator_modules, $administrator["name"], $administrator["lastname"], $administrator["email"], $administrator["status"]);
		
		$notifications->set("The administrator was created successfully.", "success", true);
		
		return true;
	}
	
	function edit($id, $administrator){
		global $notifications, $db;
		
		if ($this->exists("username", $administrator["username"], $id)){
			$notifications->set("<b>Error:</b> The username ".$administrator["username"]." already exists.", "error", true);
			return false;
		}
		if ($this->exists("email", $administrator["email"], $id)){
			$notifications->set("<b>Error:</b> The email ".$administrator["email"]." already exists.", "error", true);
			return false;
		}
		
		if (!empty($administrator["modules"])){
			$administrator_modules = "|";
			foreach ($administrator["modules"] as $key => $value){
				$administrator_modules.=$key."|";
			}
		}
		
		if (!empty($administrator["password"]) && !empty($administrator["confirm_password"]) && ($administrator["password"] == $administrator["confirm_password"])){
			$db->db_query("UPDATE ".$this->administrators_table." SET password = MD5(?s) WHERE id = ?i", $administrator["password"], $id);
		}
		
		$db->db_query("UPDATE ".$this->administrators_table." SET username = ?s, modules = ?s, name = ?s, lastname = ?s, email = ?s, status = ?i WHERE id = ?i", $administrator["username"], $administrator_modules, $administrator["name"], $administrator["lastname"], $administrator["email"], $administrator["status"], $id);
		
		$notifications->set("The administrator was updated successfully.", "success", true);
		
		return true;
	}
	
	function login($username, $password){
		global $db;
		
		$user_data=$db->db_get_row("SELECT id, status FROM ".$this->administrators_table." WHERE (email = ?s OR username = ?s) AND password = MD5(?s) LIMIT 1", $username, $username, $password);
		if (empty($user_data["id"])){
			return "non-existent";
		}elseif($user_data["status"] == 0){
			return "inactive";
		}else{
			return $user_data["id"];
		}
	}
	
	function internal_session_start(){
		ini_set("session.use_cookies","1");
		ini_set("session.use_only_cookies","1");
		session_save_path(_BACKEND_SESSIONS_PATH_);
		session_start();
	}
	
	function session_set($user_id){
		global $languages;
		$this->internal_session_start();
		$user_data=$this->get("id", $user_id);
		
		$_SESSION["supported_languages"] = $languages->get_supported_languages(true);
		
		$_SESSION["user_data"] = $user_data;
		$_SESSION["user_modules"] = $this->user_modules_get($user_data["modules"]);
		$_SESSION["activity_time"] = time();
	}
	
	function session_check(){
		global $smarty, $common, $languages;
		
		$this->internal_session_start();

		$activity_time_diff = time() - $_SESSION["activity_time"];
		
		if ($activity_time_diff>=_BACKEND_INACTIVIY_MAXTIME_){
			$common->redirect(_BACKEND_LOGIN_URL_."?redirect=1");
			session_destroy();
			die();
		}
		
		$_SESSION["activity_time"] = time();
		
		if ($_GET["log_out"]=="1"){
			$common->redirect(_BACKEND_LOGIN_URL_."?redirect=2");
			session_destroy();
			die();	
		}
		
		if (empty($_SESSION["user_data"]["id"])){
			$common->redirect(_BACKEND_LOGIN_URL_."?redirect=3");
			session_destroy();
			die();
		}else{
			#Cached menu
			$smarty->assign("menu", $_SESSION["menu"]);

			#Configuraciones extra
			$smarty->assign("sid", SID);
			$smarty->assign("supported_languages", $_SESSION["supported_languages"]);
			$smarty->assign("current_language", $languages->get_language_by_code(_CLIENT_LANGUAGE_));
			$smarty->assign("url_base", _BACKEND_URL_);
			$smarty->assign("tpl_base", _TPL_BACKEND_DIR_);
			$smarty->assign("user_data", $_SESSION["user_data"]);
			$smarty->assign("user_modules", $_SESSION["user_modules"]);
			$smarty->assign("current_uri", $_SERVER["REQUEST_URI"]);
		}
	}
	
	function get($key, $value){
		global $db;
		
		$user_data = $db->db_get_row("SELECT * FROM ".$this->administrators_table." WHERE ".$key." = ?s LIMIT 1", $value);
		
		if (!empty($user_data["id"])){
			return $user_data;
		}
		
		return false;
	}
	
	
	function exists($key, $value, $exclude_ids=false){
		global $db;

		if ($exclude_ids){
			$user_data = $db->db_get_row("SELECT id FROM ".$this->administrators_table." WHERE ".$key." = ?s AND id NOT IN(?s) LIMIT 1", $value, $exclude_ids);
		}else{
			$user_data = $db->db_get_row("SELECT id FROM ".$this->administrators_table." WHERE ".$key." = ?s LIMIT 1", $value);
		}
		
		if (!empty($user_data["id"])){
			return true;
		}
		
		return false;
	}
	
	function modules_get(){
		global $db;
		$modules = $db->db_get_array("SELECT * FROM ".$this->administrators_modules_table." ORDER BY list_order ASC");
		for ($i=0;$i<=count($modules)-1;$i++){
			$modules[$i]["options"] = json_decode($modules[$i]["options"], true);
		}
		return $modules;
	}
	
	function user_modules_get($user_modules_string){
		$modules = $this->modules_get();
		
		$user_modules_string = explode("|", $user_modules_string);
		$user_modules = array();
		
		for ($i=0;$i<=count($modules);$i++){
			for ($j=0;$j<=count($user_modules_string);$j++){
				if ($modules[$i]["id"] == $user_modules_string[$j] && !empty($modules[$i]["id"])){
					$user_modules[] = $modules[$i];
				}
			}
		}
		return $user_modules;
	}
	
	#Chek admin privileges
	function module_check($module_id){
		$user_modules = $_SESSION["user_modules"];
		for ($i=0;$i<=count($user_modules);$i++){
			if (!empty($user_modules[$i]["id"])){
				if ($user_modules[$i]["id"] == $module_id){
					return true;
				}
			}
		}	
		return false;
	}
	
	
	
}

?>