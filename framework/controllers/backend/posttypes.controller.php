<?php

class posttypes {

	var $table = "?:opt_post_types";
	var $table_langs = "?:opt_post_types_lang";
	
	function delete($type_id){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE type_id = ?i", $type_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE type_id = ?i", $type_id);
		#were
		$notifications->set("The post type/tag was deleted successfully", "success", true);
		return true;
	}
	
	function add($posttypes_data, $lang_code){
		global $notifications, $db, $controllers_loader;
		

		if ($this->exists($posttypes_data["posttype"], $lang_code)){
			$notifications->set("<b>Error:</b> The post type/tag ".$posttypes_data["posttype"]." already exists", "error", true);
			return false;
		}

		if (!$this->exists($posttypes_data["posttype"], $lang_code)){
			if($this->set($posttypes_data))
			{
				$notifications->set("The post type/tag was created successfully.", "success", true);
				return true;
			}
			else
			{
				$notifications->set("<b>Error in creating post type/tag!</b>", "error", true);
				return false;
			}
		}
		
	}
	
	function set($posttypes_data){
		global $db, $languages;

		$type_id = $db->db_query("INSERT INTO ".$this->table." SET position = ?i, status = ?s", $posttypes_data["position"], $posttypes_data["status"]);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." SET type_id = ?i, lang_code = ?s, value = ?s", $type_id, $v, $posttypes_data["posttype"]);
		}
		return true;
	}
	
	function edit_langs($posttypes_data, $type_id){
		global $notifications, $db;

		if (count($posttypes_data["lang"])>0){
			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s WHERE type_id = ?i ", $posttypes_data["posttype"]["position"], $posttypes_data["posttype"]["status"], $type_id);
			foreach($posttypes_data["lang"] as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND type_id = ?i ", $posttypes_data["lang"][$lang_code]["value"], $lang_code, $type_id);
			}
		}
		$notifications->set("The post type/tag's languages were updated successfully.", "success", true);
		
		return true;
	}
	
	function edit($posttypes_data, $lang_code){
		global $notifications, $db;
		
		if (count($posttypes_data)>0){
			foreach($posttypes_data as $type_id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i WHERE type_id = ?i ", $posttypes_data[$type_id]["position"], $type_id);
				$db->db_query("UPDATE ".$this->table_langs." SET value = ?s WHERE lang_code = ?s AND type_id = ?i ", $posttypes_data[$type_id]["value"], $lang_code, $type_id);
			}
		}
		$notifications->set("The post type/tags were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($type_id){
		global $db;
		
		$lang_data = $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table_langs." WHERE type_id = ?i", 'lang_code', $type_id);
		$feedinginterval_data = $db->db_get_row("SELECT position, status FROM ".$this->table." WHERE type_id = ?i LIMIT 1", $type_id);
		
		if (!empty($feedinginterval_data["status"])){
			$data["lang"] = $lang_data;
			$data["posttype"] = $feedinginterval_data;
			return $data;
		}
		
		return false;
	}
	
	
	function exists($posttype_value, $lang_code){
		global $db, $controllers_loader;
		
		$data = $db->db_get_row("SELECT FBL.type_id FROM ".$this->table_langs." FBL, ".$this->table." FB WHERE FB.type_id = FBL.type_id AND FBL.value = ?s AND FBL.lang_code = ?s LIMIT 1", $posttype_value, $lang_code);
		
		if (!empty($data["type_id"])){
			return true;
		}
		
		return false;
	}
}

?>