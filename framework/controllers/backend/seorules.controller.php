<?php

class seorules {

	var $table = "?:seo_rules";
	
	function delete($name){
		global $notifications, $db;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE name = ?s", $name);
		$notifications->set("The SEO Rule was deleted successfully", "success", true);
		return true;
	}
	
	function add($seo_rules_data, $lang_code){
		global $notifications, $db;
		
		if ($this->exists($seo_rules_data["name"])){
			$notifications->set("<b>Error:</b> The SEO Rule for the script ".$seo_rules_data["name"]." already exists.", "error", true);
			return false;
		}
		
		$this->set($seo_rules_data["name"], $seo_rules_data["value"], $lang_code);
		$notifications->set("The SEO Rule was created successfully.", "success", true);
		return true;
	}
	
	function set($name, $value, $lang_code){
		global $db, $languages, $common;
		
		if (!$this->exists($name)){
			foreach ($languages->supported_languages as $k => $v){
				$db->db_query("INSERT INTO ".$this->table." SET value = ?s, lang_code = ?s, name = ?s", $common->sanitize_content($value), $v, $name);
			}
			return true;
		}
		return false;
	}
	
	function edit($seo_rules_data, $lang_code){
		global $notifications, $db, $common;
		
		if (count($seo_rules_data)>0){
			foreach($seo_rules_data as $name => $value){
				$db->db_query("UPDATE ".$this->table." SET value = ?s WHERE lang_code = ?s AND name = ?s ", $common->sanitize_content($value), $lang_code, $name);
			}
		}
		$notifications->set("The SEO Rules was updated successfully.", "success", true);
		
		return true;
	}
	
	function edit_langs($seorules_data, $name){
		global $notifications, $db, $common;

		if (count($seorules_data)>0){
			foreach($seorules_data as $lang_code => $value){
				$db->db_query("UPDATE ".$this->table." SET value = ?s WHERE lang_code = ?s AND name = ?s", $common->sanitize_content($seorules_data[$lang_code]["value"]), $lang_code, $name);
			}
		}
		$notifications->set("The SEO Rules were updated successfully.", "success", true);
		
		return true;
	}
	
	function get($name){
		global $db;
		return $db->db_get_hash_array("SELECT lang_code, value FROM ".$this->table." WHERE name = ?s", 'lang_code', $name);
	}
	
	
	function exists($name){
		global $db;

		$data = $db->db_get_row("SELECT lang_code FROM ".$this->table." WHERE name = ?s LIMIT 1", $name);
		
		if (!empty($data["lang_code"])){
			return true;
		}
		
		return false;
	}
}

?>