<?php

class foodbrands {

	var $table = "?:food_brands";
	var $table_langs = "?:food_brands_lang";
	
	function delete($food_brand_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE food_brand_id = ?i", $food_brand_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE food_brand_id = ?i", $food_brand_id);
		#were
		$notifications->set("The food brand was deleted successfully", "success", true);
		return true;
	}
	
	function add($foodbrands_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation cycle
		for ($i=0;$i<=count($foodbrands_data["pet_types"])-1;$i++){
			if ($this->exists($foodbrands_data["foodbrand"], $foodbrands_data["pet_types"][$i], $lang_code)){
				$notifications->set("<b>Error:</b> The food brand ".$foodbrands_data["foodbrand"]." already exists for ".$pet_types_controller->get_lang_description($foodbrands_data["pet_types"][$i], $lang_code).".", "error", true);
				return false;
			}
		}
		for ($i=0;$i<=count($foodbrands_data["pet_types"])-1;$i++){
			if (!$this->exists($foodbrands_data["foodbrand"], $foodbrands_data["pet_types"][$i], $lang_code)){
				$this->set($foodbrands_data["pet_types"][$i], $foodbrands_data);
			}
		}
		$notifications->set("The food brand was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $foodbrands_data){
		global $db, $languages;
		
		$foodbrand_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $foodbrands_data["position"], $foodbrands_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET food_brand_id = ?i, lang_code = ?s, value = ?s", $foodbrand_id, $v, $foodbrands_data["foodbrand"]);
		}
		return true;
	}
	
	function edit_langs($foodbrands_data, $food_brand_id){
		global $notifications, $db;

		if (count($foodbrands_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE food_brand_id = ?i ", $foodbrands_data["brand"]["position"], $foodbrands_data["brand"]["status"], $food_brand_id);
			foreach($foodbrands_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND food_brand_id = ?i ", $foodbrands_data["lang"][$lang_code]["value"], $lang_code, $food_brand_id);
			}
		}
		$notifications->set("The Pet type languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($foodbrands_data, $lang_code){
		global $notifications, $db;
		
		if (count($foodbrands_data)>0){
			foreach($foodbrands_data as $food_brand_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE food_brand_id = ?i ", $foodbrands_data[$food_brand_id]["position"], $food_brand_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND food_brand_id = ?i ", $foodbrands_data[$food_brand_id]["value"], $lang_code, $food_brand_id);
			}
		}
		$notifications->set("The Food brands were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($food_brand_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE food_brand_id = ?i", 'lang_code', $food_brand_id);
		$brand_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE food_brand_id = ?i LIMIT 1", $food_brand_id);
		
		if (!empty($brand_data["status"])){
			$data["lang"] = $lang_data;
			$data["brand"] = $brand_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($foodbrand_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT FBL.food_brand_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.pet_type_id = ?i AND FB.food_brand_id = FBL.food_brand_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $pet_type_id, $foodbrand_value, $lang_code);
		
		if (!empty($data["food_brand_id"])){
			return true;
		}
		
		return false;
	}
}

?>