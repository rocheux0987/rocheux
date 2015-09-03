<?php

class dislikes {

	var $table = "?:dislikes";
	var $table_langs = "?:dislikes_lang";
	
	function delete($dislike_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE dislike_id = ?i", $dislike_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE dislike_id = ?i", $dislike_id);
		#were
		$notifications->set("The dislike was deleted successfully", "success", true);
		return true;
	}
	
	function add($dislikes_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation cycle
		for ($i=0;$i<=count($dislikes_data["pet_types"])-1;$i++){
			if ($this->exists($dislikes_data["dislike"], $dislikes_data["pet_types"][$i], $lang_code)){
				$notifications->set("<b>Error:</b> The dislike ".$dislikes_data["dislike"]." already exists for ".$pet_types_controller->get_lang_description($dislikes_data["pet_types"][$i], $lang_code).".", "error", true);
				return false;
			}
		}
		for ($i=0;$i<=count($dislikes_data["pet_types"])-1;$i++){
			if (!$this->exists($dislikes_data["dislike"], $dislikes_data["pet_types"][$i], $lang_code)){
				$this->set($dislikes_data["pet_types"][$i], $dislikes_data);
			}
		}
		$notifications->set("The dislike was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $dislikes_data){
		global $db, $languages;
		
		$dislike_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $dislikes_data["position"], $dislikes_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET dislike_id = ?i, lang_code = ?s, value = ?s", $dislike_id, $v, $dislikes_data["dislike"]);
		}
		return true;
	}
	
	function edit_langs($dislikes_data, $dislike_id){
		global $notifications, $db;

		if (count($dislikes_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE dislike_id = ?i ", $dislikes_data["dislike"]["position"], $dislikes_data["dislike"]["status"], $dislike_id);
			foreach($dislikes_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND dislike_id = ?i ", $dislikes_data["lang"][$lang_code]["value"], $lang_code, $dislike_id);
			}
		}
		$notifications->set("The dislike languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($dislikes_data, $lang_code){
		global $notifications, $db;
		
		if (count($dislikes_data)>0){
			foreach($dislikes_data as $dislike_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE dislike_id = ?i ", $dislikes_data[$dislike_id]["position"], $dislike_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND dislike_id = ?i ", $dislikes_data[$dislike_id]["value"], $lang_code, $dislike_id);
			}
		}
		$notifications->set("The dislikes were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($dislike_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE dislike_id = ?i", 'lang_code', $dislike_id);
		$dislike_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE dislike_id = ?i LIMIT 1", $dislike_id);
		
		if (!empty($dislike_data["status"])){
			$data["lang"] = $lang_data;
			$data["dislike"] = $dislike_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($dislike_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT FBL.dislike_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.pet_type_id = ?i AND FB.dislike_id = FBL.dislike_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $pet_type_id, $dislike_value, $lang_code);
		
		if (!empty($data["dislike_id"])){
			return true;
		}
		
		return false;
	}
}

?>