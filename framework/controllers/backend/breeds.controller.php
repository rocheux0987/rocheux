<?php

class breeds {

	var $table = "?:breeds";
	var $table_langs = "?:breeds_lang";
	
	function delete($breed_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE breed_id = ?i", $breed_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE breed_id = ?i", $breed_id);
		$notifications->set("The breed was deleted successfully", "success", true);
		return true;
	}
	
	function add($breeds_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation
		if ($this->exists($breeds_data["breed"], $breeds_data["pet_type"], $lang_code)){
			$notifications->set("<b>Error:</b> The Breed ".$breeds_data["breed"]." already exists for ".$pet_types_controller->get_lang_description($breeds_data["pet_type"], $lang_code).".", "error", true);
			return false;
		}
		
		$this->set($breeds_data["pet_type"], $breeds_data);
		
		$notifications->set("The Breed was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $breeds_data){
		global $db, $languages;
		
		$breed_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $breeds_data["position"], $breeds_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET breed_id = ?i, lang_code = ?s, value = ?s", $breed_id, $v, $breeds_data["breed"]);
		}
		return true;
	}
	
	function edit_langs($breeds_data, $breed_id){
		global $notifications, $db;

		if (count($breeds_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE breed_id = ?i ", $breeds_data["breed"]["position"], $breeds_data["breed"]["status"], $breed_id);
			foreach($breeds_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND breed_id = ?i ", $breeds_data["lang"][$lang_code]["value"], $lang_code, $breed_id);
			}
		}
		$notifications->set("The breeds were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($breeds_data, $lang_code){
		global $notifications, $db;
		
		if (count($breeds_data)>0){
			foreach($breeds_data as $breed_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE breed_id = ?i ", $breeds_data[$breed_id]["position"], $breed_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND breed_id = ?i ", $breeds_data[$breed_id]["value"], $lang_code, $breed_id);
			}
		}
		$notifications->set("The Breeds were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($breed_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE breed_id = ?i", 'lang_code', $breed_id);
		$breed_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE breed_id = ?i LIMIT 1", $breed_id);
		
		if (!empty($breed_data["status"])){
			$data["lang"] = $lang_data;
			$data["breed"] = $breed_data;
			return $data;
		}
		
		return false;
	}
	
	function exists($breed_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT BL.breed_id FROM ".$this->table_langs." BL, ".$this->table." B WHERE B.pet_type_id = ?i AND B.breed_id = BL.breed_id AND BL.value = ?s AND BL.lang_code = ?s LIMIT 1", $pet_type_id, $breed_value, $lang_code);
		
		if (!empty($data["breed_id"])){
			return true;
		}
		
		return false;
	}
}

?>