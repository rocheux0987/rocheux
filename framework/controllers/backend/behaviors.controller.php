<?php

class behaviors {

	var $table = "?:behaviors";
	var $table_langs = "?:behaviors_lang";
	
	function delete($behavior_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE behavior_id = ?i", $behavior_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE behavior_id = ?i", $behavior_id);
		#were
		$notifications->set("The behavior was deleted successfully", "success", true);
		return true;
	}
	
	function add($behaviors_data, $lang_code){
		global $notifications, $db, $pet_types_controller, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		#Validation cycle
		for ($i=0;$i<=count($behaviors_data["pet_types"])-1;$i++){
			if ($this->exists($behaviors_data["behavior"], $behaviors_data["pet_types"][$i], $lang_code)){
				$notifications->set("<b>Error:</b> The behavior ".$behaviors_data["behavior"]." already exists for ".$pet_types_controller->get_lang_description($behaviors_data["pet_types"][$i], $lang_code).".", "error", true);
				return false;
			}
		}
		for ($i=0;$i<=count($behaviors_data["pet_types"])-1;$i++){
			if (!$this->exists($behaviors_data["behavior"], $behaviors_data["pet_types"][$i], $lang_code)){
				$this->set($behaviors_data["pet_types"][$i], $behaviors_data);
			}
		}
		$notifications->set("The behavior was created successfully.", "success", true);
		return true;
	}
	
	function set($pet_type_id, $behaviors_data){
		global $db, $languages;
		
		$behavior_id = $db->db_query("INSERT INTO ".$this->table." SET pet_type_id = ?i, position = ?i, status = ?s", $pet_type_id, $behaviors_data["position"], $behaviors_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET behavior_id = ?i, lang_code = ?s, value = ?s", $behavior_id, $v, $behaviors_data["behavior"]);
		}
		return true;
	}
	
	function edit_langs($behaviors_data, $behavior_id){
		global $notifications, $db;

		if (count($behaviors_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE behavior_id = ?i ", $behaviors_data["behavior"]["position"], $behaviors_data["behavior"]["status"], $behavior_id);
			foreach($behaviors_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND behavior_id = ?i ", $behaviors_data["lang"][$lang_code]["value"], $lang_code, $behavior_id);
			}
		}
		$notifications->set("The behavior languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($behaviors_data, $lang_code){
		global $notifications, $db;
		
		if (count($behaviors_data)>0){
			foreach($behaviors_data as $behavior_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE behavior_id = ?i ", $behaviors_data[$behavior_id]["position"], $behavior_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND behavior_id = ?i ", $behaviors_data[$behavior_id]["value"], $lang_code, $behavior_id);
			}
		}
		$notifications->set("The behaviors were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($behavior_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE behavior_id = ?i", 'lang_code', $behavior_id);
		$behavior_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE behavior_id = ?i LIMIT 1", $behavior_id);
		
		if (!empty($behavior_data["status"])){
			$data["lang"] = $lang_data;
			$data["behavior"] = $behavior_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($behavior_value, $pet_type_id, $lang_code){
		global $db, $controllers_loader;
		
		if (!$pet_types_controller){
			$controllers_loader->load("pet_types");
		}
		
		$data = $db->db_get_row("SELECT FBL.behavior_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.pet_type_id = ?i AND FB.behavior_id = FBL.behavior_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $pet_type_id, $behavior_value, $lang_code);
		
		if (!empty($data["behavior_id"])){
			return true;
		}
		
		return false;
	}
}

?>