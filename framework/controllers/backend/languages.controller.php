<?php

class languages {

	function delete($name){
		global $notifications, $db, $languages;
		$languages->delete($name);
		$notifications->set("The language variable was deleted successfully.", "success", true);
		return true;
	}
	
	function add($lang_data, $lang_code){
		global $notifications, $db, $languages;
		
		if ($languages->exists($lang_data["name"], $lang_code)){
			$notifications->set("<b>Error:</b> The language variable ".$lang_data["name"]." already exists.", "error", true);
			return false;
		}
		
		$languages->set($lang_data["name"], $lang_data["value"], $lang_code);
		$notifications->set("The language variable was created successfully.", "success", true);
		return true;
	}
	
	function edit($lang_data, $lang_code){
		global $notifications, $db, $languages;
		
		if (count($lang_data)>0){
			$languages->update_multiple($lang_data, $lang_code);
			$notifications->set("The languages variables was updated successfully.", "success", true);
		}
		return true;
	}
	
	function get($name){
		global $db, $languages;
		$lang_data = $db->db_get_hash_array("SELECT value, lang_code FROM ".$languages->table." WHERE name = ?s ", 'lang_code', $name);
		return $lang_data;
	}
	
	function edit_langs($lang_data, $name){
		global $notifications, $db, $languages;

		if (count($lang_data)>0){
			foreach($lang_data as $lang_code => $value){
				$db->db_query("UPDATE ".$languages->table." SET value = ?s WHERE lang_code = ?s AND name = ?s ", $lang_data[$lang_code]["value"], $lang_code, $name);
			}
		}
		$notifications->set("The languages was updated successfully.", "success", true);
		
		return true;
	}
}
?>