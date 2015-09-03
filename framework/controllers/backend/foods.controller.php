<?php

class foods {

	var $table = "?:foods";
	var $table_langs = "?:foods_lang";
	
	function delete($food_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE food_id = ?i", $food_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE food_id = ?i", $food_id);
		#were
		$notifications->set("The food was deleted successfully", "success", true);
		return true;
	}
	
	function add($foods_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation cycle
		for ($i=0;$i<=count($foods_data["pet_types"])-1;$i++){
			if ($this->exists($foods_data["food"], $foods_data["pet_types"][$i], $lang_code)){
				$notifications->set("<b>Error:</b> The food ".$foods_data["food"]." already exists for ".$pet_types_controller->get_lang_description($foods_data["pet_types"][$i], $lang_code).".", "error", true);
				return false;
			}
		}
		for ($i=0;$i<=count($foods_data["pet_types"])-1;$i++){
			if (!$this->exists($foods_data["food"], $foods_data["pet_types"][$i], $lang_code)){
				$this->set($foods_data["pet_types"][$i], $foods_data);
			}
		}
		$notifications->set("The food was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $foods_data){
		global $db, $languages;
		
		$food_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $foods_data["position"], $foods_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET food_id = ?i, lang_code = ?s, value = ?s", $food_id, $v, $foods_data["food"]);
		}
		return true;
	}
	
	function edit_langs($foods_data, $food_id){
		global $notifications, $db;

		if (count($foods_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE food_id = ?i ", $foods_data["food"]["position"], $foods_data["food"]["status"], $food_id);
			foreach($foods_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND food_id = ?i ", $foods_data["lang"][$lang_code]["value"], $lang_code, $food_id);
			}
		}
		$notifications->set("The food languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($foods_data, $lang_code){
		global $notifications, $db;
		
		if (count($foods_data)>0){
			foreach($foods_data as $food_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE food_id = ?i ", $foods_data[$food_id]["position"], $food_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND food_id = ?i ", $foods_data[$food_id]["value"], $lang_code, $food_id);
			}
		}
		$notifications->set("The foods were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($food_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE food_id = ?i", 'lang_code', $food_id);
		$food_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE food_id = ?i LIMIT 1", $food_id);
		
		if (!empty($food_data["status"])){
			$data["lang"] = $lang_data;
			$data["food"] = $food_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($food_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT FBL.food_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.pet_type_id = ?i AND FB.food_id = FBL.food_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $pet_type_id, $food_value, $lang_code);
		
		if (!empty($data["food_id"])){
			return true;
		}
		
		return false;
	}
}

?>