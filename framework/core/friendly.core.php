<?php

class friendly {

	var $params = array();
	var $page = "";
	var $request_uri = "";
	var $table = "?:seo_rules";
	var $default_module = "home.php";
	
	function __construct(){		
		
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = preg_replace("#"._SITE_EXTRA_DIR_."#", '', $request_uri);
		$this->request_uri = $request_uri;
		$this->strip_params($request_uri); 
		
		$this->set_language_by_param();
		
		if (empty($this->params[1])){
    		$this->page = $this->default_module; 
		}else{
			global $languages, $db, $common;
			$module = $this->get_module_by_seourl($this->params[1]);
			if (!$module){
				if(!file_exists(_SITE_DIR_.$this->params[1].".php")){  
					$this->page = "404.php";
				}else{
					if ($this->module_exists($this->params[1].".php")){
						$this->page = "404.php";
					}else{
						$new_seo = $this->params[1];
						$counter = 1;
						while($db->db_get_field("SELECT COUNT(1) FROM ".$this->table." WHERE value = ?s", $new_seo) > 0){
							$new_seo = $this->params[1]."-".$counter;
							$counter++;
						}
						
						foreach ($languages->supported_languages as $k => $v){
							$db->db_query("INSERT INTO ".$this->table." SET lang_code = ?s, name = ?s, value = ?s", $v, $this->params[1].".php", $new_seo);
						}
						$redirect_url = "";
						
						for ($i=0;$i<=count($this->params)-1;$i++){
							if ($i == 1){
								$redirect_url.= $new_seo."/";
							}else{
								$redirect_url.= $this->params[$i]."/";
							}
						}

						$common->redirect(_SITE_URL_.$redirect_url);
						die();
					}
				}
			}else{
				$this->page = $module;
			}	
    	}
	}
	
	function module_exists($module){
		global $db;
		$exists = $db->db_get_field("SELECT COUNT(1) FROM ".$this->table." WHERE name = ?s LIMIT 1", $module);
		if ($exists>0){
			return true;
		}
		return false;
	}
	
	function set_language_by_param(){
		global $languages, $common;
		$lang_code = $this->params[0];
		$current_lang = $languages->get_language();
		if ($languages->language_exists($lang_code, true)){
			if ($lang_code != strtolower($current_lang) || !defined("_CLIENT_LANGUAGE_")){
				$languages->set_language(strtoupper($lang_code));
				return;
			}
		}else{
			if (!defined("_CLIENT_LANGUAGE_")){
				$languages->set_language();
			}
				
			if (empty($lang_code)){
				$redirect_url = _SITE_URL_.strtolower(_CLIENT_LANGUAGE_)."/";
				$common->redirect($redirect_url);
			}else{
				$updated_uri = preg_replace("#".$lang_code."/#", "", $this->request_uri);
				$redirect_url = _SITE_URL_.strtolower(_CLIENT_LANGUAGE_).$updated_uri;
				$common->redirect($redirect_url);
			}
			return;
		}
		
	}
	
	#Used for Smarty plug-in
	public function get_seourl_by_module($module, $querystring_to_seo = false, $lang_code = _CLIENT_LANGUAGE_){
		global $db;
		
		list($module_clean, $query_string) = explode("?", $module);
		
		$module_seo = $db->db_get_row("SELECT value FROM ".$this->table." WHERE lang_code = ?s AND name = ?s", $lang_code, $module_clean);
		
		$return_url = _SITE_URL_.strtolower($lang_code)."/";
		if (!empty($module_seo["value"])){
			$return_url.=$module_seo["value"];
		}else{
			$return_url.=preg_replace("#\.php#", "", $module);
		}
		
		if (!empty($query_string)){
			if ($querystring_to_seo){
				$return_url.= $this->query_string_to_seo($query_string);
			}else{
				$return_url.= "/?".$query_string;
			}
		}
		
		return $return_url;
	}
	
	public function get_module_by_seourl($seo_module, $lang_code = _CLIENT_LANGUAGE_){
		global $db;
		
		$module_seo = $db->db_get_row("SELECT name FROM ".$this->table." WHERE lang_code = ?s AND value = ?s", $lang_code, $seo_module);
		
		if (!empty($module_seo["name"])){
			return $module_seo["name"];
		}
		
		return false;
	}
	
	function query_string_to_seo($string){
		$query_string_seo = "/";
		
		if (preg_match("/\&/", $string)){
			$params = explode("&", $string);
			for ($i=0;$i<=count($params);$i++){
				$subparams = explode("=", $params[$i]);
				if (!empty($subparams[1])){
					$query_string_seo.=$subparams[1]."/";
				}
				
			}
		}else{
			$subparams = explode("=", $string);
			$query_string_seo.=$subparams[1]."/";
		}
		
		return $query_string_seo;
	}
	
	function strip_params($string){
		$string = preg_replace("/^\//", "", $string);
		$params = explode("/", $string);
		
		for ($i=0;$i<=count($params);$i++){
			if (empty($params[$i])){
				unset($params[$i]);
			}
		}
		
	 	$this->params = $params;
	 	return $params;
	}
	
	function get_current_url(){
		$return_url = "/"._SITE_EXTRA_DIR_;
		$params = $this->params;
		for ($i=0;$i<=count($params);$i++){
			if (!empty($params[$i])){
				$return_url.=$params[$i]."/";
			}
		}
		return $return_url;
	}
}

?>