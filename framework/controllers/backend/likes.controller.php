<?php

class likes {

	var $table = "?:likes";
	var $table_langs = "?:likes_lang";
	
	function delete($like_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE like_id = ?i", $like_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE like_id = ?i", $like_id);
		#were
		$notifications->set("The like was deleted successfully", "success", true);
		return true;
	}
	
	function add($likes_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation cycle
		for ($i=0;$i<=count($likes_data["pet_types"])-1;$i++){
			if ($this->exists($likes_data["like"], $likes_data["pet_types"][$i], $lang_code)){
				$notifications->set("<b>Error:</b> The like ".$likes_data["like"]." already exists for ".$pet_types_controller->get_lang_description($likes_data["pet_types"][$i], $lang_code).".", "error", true);
				return false;
			}
		}
		for ($i=0;$i<=count($likes_data["pet_types"])-1;$i++){
			if (!$this->exists($likes_data["like"], $likes_data["pet_types"][$i], $lang_code)){
				$this->set($likes_data["pet_types"][$i], $likes_data);
			}
		}
		$notifications->set("The like was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $likes_data){
		global $db, $languages;
		
		$like_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $likes_data["position"], $likes_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET like_id = ?i, lang_code = ?s, value = ?s", $like_id, $v, $likes_data["like"]);
		}
		return true;
	}
	
	function edit_langs($likes_data, $like_id){
		global $notifications, $db;

		if (count($likes_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE like_id = ?i ", $likes_data["like"]["position"], $likes_data["like"]["status"], $like_id);
			foreach($likes_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND like_id = ?i ", $likes_data["lang"][$lang_code]["value"], $lang_code, $like_id);
			}
		}
		$notifications->set("The like languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($likes_data, $lang_code){
		global $notifications, $db;
		
		if (count($likes_data)>0){
			foreach($likes_data as $like_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE like_id = ?i ", $likes_data[$like_id]["position"], $like_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND like_id = ?i ", $likes_data[$like_id]["value"], $lang_code, $like_id);
			}
		}
		$notifications->set("The likes were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($like_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE like_id = ?i", 'lang_code', $like_id);
		$like_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE like_id = ?i LIMIT 1", $like_id);
		
		if (!empty($like_data["status"])){
			$data["lang"] = $lang_data;
			$data["like"] = $like_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($like_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT FBL.like_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.pet_type_id = ?i AND FB.like_id = FBL.like_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $pet_type_id, $like_value, $lang_code);
		
		if (!empty($data["like_id"])){
			return true;
		}
		
		return false;
	}
}

?>