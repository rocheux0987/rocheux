<?php

class menu {

	var $administrators_modules_table = "?:administrators_modules";
	
	function delete($id ,$menu_data){
		global $notifications, $db;
		
		unset($menu_data[$id]); //delete

    	$new_menu = array_values($menu_data); //Re-populate input into new array object
		$encoded_menu = json_encode($new_menu);	 //Convert array object into json object 
		$reformat_menu = print_r($encoded_menu, true); //save to option 

		$db->db_query("UPDATE ".$this->administrators_modules_table." SET options = ?s WHERE id = 2 ", $reformat_menu);
		$notifications->set("The menu option was deleted successfully", "success", true);
		return true;
	}
	
	function add($menu_data){
		global $notifications, $db;
		
		if ($this->exists($menu_data["description"])){
			$notifications->set("<b>Error:</b> The description ".$menu_data["description"]." already exists.", "error", true);
			return false;
		}
		else
		{
			$menu_list =  $this->get_options_val();

			$reformat_desc = ucfirst(strtolower($menu_data["description"]));

			array_push($menu_list, array(
				"description" => $reformat_desc,
				"url" => $menu_data["url"]
				));

			if ($this->edit($menu_list)){
				$notifications->set("The menu option was created successfully.", "success", true);
				return true;
			}
		}
	}
	
	function edit($menu_data){
		global $notifications, $db;

		$new_menu = array_values($menu_data); //Re-populate input into new array object
		$encoded_menu = json_encode($new_menu);	 //Convert array object into json object 
		$reformat_menu = print_r($encoded_menu, true); //save to option 

		//print_r($reformat_menu);exit;
			
		if($menu_data != null)
		{
			$db->db_query("UPDATE ".$this->administrators_modules_table." SET options = ?s WHERE id = 2 ", $reformat_menu);
			$notifications->set("The menu was updated successfully.", "success", true);
			return true;
		}
		else
		{
			$notifications->set("No data input.", "error", true);
			return true;
		}
	}
	
	function exists($value){
		global $db;

		$menu_list = $this->get_options_val();
		$err = 0;
		foreach($menu_list as $menu)
		{
			if(strtolower($menu["description"]) === strtolower($value))
				$err = 1;
		}

		if ($err == 1){
			return true;
		}
		
		return false;
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
	
	#Get Menu Options Value
	function get_options_val(){
		global $db;

		$row = $db->db_get_row("SELECT options FROM ".$this->administrators_modules_table." WHERE id = 2");
		$menu_list = json_decode($row[options], true);

		return $menu_list;
	}
	
	
}

?>