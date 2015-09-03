<?php

class feedinginterval {

	var $table = "?:feeding_interval";
	var $table_langs = "?:feeding_interval_lang";
	
	function delete($feeding_interval_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE feeding_interval_id = ?i", $feeding_interval_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE feeding_interval_id = ?i", $feeding_interval_id);
		#were
		$notifications->set("The feeding interval was deleted successfully", "success", true);
		return true;
	}
	
	function add($feedingintervals_data, $lang_code){
		global $notifications, $db, $controllers_loader;
		

		if ($this->exists($feedingintervals_data["feeding_interval"], $lang_code)){
			$notifications->set("<b>Error:</b> The feeding interval ".$feedingintervals_data["feeding_interval"]." already exists", "error", true);
			return false;
		}

		if (!$this->exists($feedingintervals_data["feeding_interval"], $lang_code)){
			if($this->set($feedingintervals_data))
			{
				$notifications->set("The feeding interval was created successfully.", "success", true);
				return true;
			}
			else
			{
				$notifications->set("<b>Error in creating feeding interval!</b>", "error", true);
				return false;
			}
		}
		
	}
	
	function set($feedingintervals_data){
		global $db, $languages;

		$feeding_interval_id = $db->db_query("INSERT INTO ".$this->table." SET position = ?i, status = ?s", $feedingintervals_data["position"], $feedingintervals_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET feeding_interval_id = ?i, lang_code = ?s, value = ?s", $feeding_interval_id, $v, $feedingintervals_data["feeding_interval"]);
		}
		return true;
	}
	
	function edit_langs($feedingintervals_data, $feeding_interval_id){
		global $notifications, $db;

		if (count($feedingintervals_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE feeding_interval_id = ?i ", $feedingintervals_data["feeding_interval"]["position"], $feedingintervals_data["feeding_interval"]["status"], $feeding_interval_id);
			foreach($feedingintervals_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND feeding_interval_id = ?i ", $feedingintervals_data["lang"][$lang_code]["value"], $lang_code, $feeding_interval_id);
			}
		}
		$notifications->set("The feeding interval's languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($feedingintervals_data, $lang_code){
		global $notifications, $db;
		
		if (count($feedingintervals_data)>0){
			foreach($feedingintervals_data as $feeding_interval_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE feeding_interval_id = ?i ", $feedingintervals_data[$feeding_interval_id]["position"], $feeding_interval_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND feeding_interval_id = ?i ", $feedingintervals_data[$feeding_interval_id]["value"], $lang_code, $feeding_interval_id);
			}
		}
		$notifications->set("The feeding intervals were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($feeding_interval_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE feeding_interval_id = ?i", 'lang_code', $feeding_interval_id);
		$feedinginterval_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE feeding_interval_id = ?i LIMIT 1", $feeding_interval_id);
		
		if (!empty($feedinginterval_data["status"])){
			$data["lang"] = $lang_data;
			$data["feeding_interval"] = $feedinginterval_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($feedinginterval_value, $lang_code){
		global $db, $controllers_loader;
		
		$data = $db->db_get_row("SELECT FBL.feeding_interval_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.feeding_interval_id = FBL.feeding_interval_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $feedinginterval_value, $lang_code);
		
		if (!empty($data["feeding_interval_id"])){
			return true;
		}
		
		return false;
	}
}

?>