<?php

class pet_types {

	var $table = "?:pet_types";
	var $table_langs = "?:pet_types_lang";
	
	function delete($pet_type_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE pet_type_id = ?i", $pet_type_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE pet_type_id = ?i", $pet_type_id);
		$notifications->set("The Pet type was deleted successfully", "success", true);
		return true;
	}
	
	function add($pet_type_data, $lang_code){
		global $notifications, $db;
		
		if ($this->exists($pet_type_data["value"], $lang_code)){
			$notifications->set("<b>Error:</b> The Pet type ".$pet_type_data["value"]." already exists.", "error", true);
			return false;
		}
		
		$this->set($pet_type_data, $lang_code);
		$notifications->set("The Pet type was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_data, $lang_code){
		global $db, $languages;
		
		if (!$this->exists($value, $lang_code)){
			$pet_type_id = $db->db_query("INSERT INTO ".$this->table." SET position = ?i, status = ?s", $pet_type_data["position"], $pet_type_data["status"]);
			foreach ($languages->supported_languages as $k => $v){
				$db->db_query("INSERT INTO ".$this->table_langs." SET pet_type_id = ?s, lang_code = ?s, value = ?s", $pet_type_id, $v, $pet_type_data["value"]);
			}
			return true;
		}
		return false;
	}
	
	function edit_langs($pet_types_data, $pet_type_id){
		global $notifications, $db;

		if (count($pet_types_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE pet_type_id = ?i ", $pet_types_data["type"]["position"], $pet_types_data["type"]["status"], $pet_type_id);
			foreach($pet_types_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND pet_type_id = ?i ", $pet_types_data["lang"][$lang_code]["value"], $lang_code, $pet_type_id);
			}
		}
		$notifications->set("The Pet type languages was updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($pet_types_data, $lang_code){
		global $notifications, $db;
		
		if (count($pet_types_data)>0){
			foreach($pet_types_data as $pet_type_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE pet_type_id = ?i ", $pet_types_data[$pet_type_id]["position"], $pet_type_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND pet_type_id = ?i ", $pet_types_data[$pet_type_id]["value"], $lang_code, $pet_type_id);
			}
		}
		$notifications->set("The Pet types was updated successfully.", "success", true);
		
		return true;
	}
	
	function get_all($lang_code = _CLIENT_LANGUAGE_){
		global $db;
		$elements = $db->db_get_array("SELECT PTL.value AS pet_type, PTL.pet_type_id FROM ".$this->table_langs." PTL, ".$this->table." PT WHERE PTL.lang_code = ?s AND PTL.pet_type_id = PT.pet_type_id AND PT.status != ?s", $lang_code, 'D');
		return $elements;
	}
	
	function get($pet_type_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE pet_type_id = ?i", 'lang_code', $pet_type_id);
		$type_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE pet_type_id = ?i LIMIT 1", $pet_type_id);
		
		if (!empty($type_data["status"])){
			$data["lang"] = $lang_data;
			$data["type"] = $type_data;
			return $data;
		}
		
		return false;
	}
	
	function get_lang_description($pet_type_id, $lang_code){
		global $db;
		$description = $db->db_get_field("SELECT value FROM ".$this->table_langs." WHERE pet_type_id = ?i AND lang_code = ?s LIMIT 1", $pet_type_id, $lang_code);
		if (!empty($description)){
			return $description;
		}
		return false;
	}
	
	
	function exists($value, $lang_code){
		global $db;

		$data = $db->db_get_row("SELECT pet_type_id FROM ".$this->table_langs." WHERE value = ?s AND lang_code = ?s LIMIT 1", $value, $lang_code);
		
		if (!empty($data["pet_type_id"])){
			return true;
		}
		
		return false;
	}
}

?>