<?php

class foodstyles {

	var $table = "?:food_styles";
	var $table_langs = "?:food_styles_lang";
	
	function delete($food_style_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE food_style_id = ?i", $food_style_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE food_style_id = ?i", $food_style_id);
		#were
		$notifications->set("The food style was deleted successfully", "success", true);
		return true;
	}
	
	function add($foodstyles_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation cycle
		for ($i=0;$i<=count($foodstyles_data["pet_types"])-1;$i++){
			if ($this->exists($foodstyles_data["foodstyle"], $foodstyles_data["pet_types"][$i], $lang_code)){
				$notifications->set("<b>Error:</b> The food style ".$foodstyles_data["foodstyle"]." already exists for ".$pet_types_controller->get_lang_description($foodstyles_data["pet_types"][$i], $lang_code).".", "error", true);
				return false;
			}
		}
		for ($i=0;$i<=count($foodstyles_data["pet_types"])-1;$i++){
			if (!$this->exists($foodstyles_data["foodstyle"], $foodstyles_data["pet_types"][$i], $lang_code)){
				$this->set($foodstyles_data["pet_types"][$i], $foodstyles_data);
			}
		}
		$notifications->set("The food style was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $foodstyles_data){
		global $db, $languages;
		
		$foodstyle_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $foodstyles_data["position"], $foodstyles_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET food_style_id = ?i, lang_code = ?s, value = ?s", $foodstyle_id, $v, $foodstyles_data["foodstyle"]);
		}
		return true;
	}
	
	function edit_langs($foodstyles_data, $food_style_id){
		global $notifications, $db;

		if (count($foodstyles_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE food_style_id = ?i ", $foodstyles_data["style"]["position"], $foodstyles_data["style"]["status"], $food_style_id);
			foreach($foodstyles_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND food_style_id = ?i ", $foodstyles_data["lang"][$lang_code]["value"], $lang_code, $food_style_id);
			}
		}
		$notifications->set("The Pet type languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($foodstyles_data, $lang_code){
		global $notifications, $db;
		
		if (count($foodstyles_data)>0){
			foreach($foodstyles_data as $food_style_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE food_style_id = ?i ", $foodstyles_data[$food_style_id]["position"], $food_style_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND food_style_id = ?i ", $foodstyles_data[$food_style_id]["value"], $lang_code, $food_style_id);
			}
		}
		$notifications->set("The Food styles were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($food_style_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE food_style_id = ?i", 'lang_code', $food_style_id);
		$style_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE food_style_id = ?i LIMIT 1", $food_style_id);
		
		if (!empty($style_data["status"])){
			$data["lang"] = $lang_data;
			$data["style"] = $style_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($foodstyle_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT FBL.food_style_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.pet_type_id = ?i AND FB.food_style_id = FBL.food_style_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $pet_type_id, $foodstyle_value, $lang_code);
		
		if (!empty($data["food_style_id"])){
			return true;
		}
		
		return false;
	}
}

?>