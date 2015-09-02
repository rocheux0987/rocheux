<?php


class slider{

	var $table = "?:sliders";
	var $table_langs = "?:sliders_lang";

	function delete($slide_id){
		global $notifications, $db ;
		
		$db->db_query("DELETE FROM ".$this->table." WHERE slide_id = ?i", $slide_id);
		$db->db_query("DELETE FROM ".$this->table_langs." WHERE slide_id = ?i", $slide_id);
		$notifications->set("The slider was deleted successfully", "success", true);
		return true;
	}
	

	function add($data , $file){
		global $notifications, $db , $languages , $filesystem;

		//upload photo
		$upload_result = $filesystem->fn_upload($file,_SITE_DIR_.'images/sliders/');

		$id = $db->db_query("INSERT INTO ".$this->table." (name , link , position , status) VALUES (?s,?s,?i,?s)",$data['name'],$data['link'],$data['position'],$data['status']);
		
		foreach ($languages->supported_languages as $k => $v){
			$db->db_query("INSERT INTO ".$this->table_langs." (slide_id , lang_code , image) VALUES (?i ,?s , ?s ) ", $id, $v , $file['name']);
		}
		$notifications->set("New Slider has been added", "success", true);
		return true;
	}

	function edit($data){
		global $notifications, $db;

		if (count($data) > 0){

			foreach($data as $id => $value){
				$db->db_query("UPDATE ".$this->table." SET position = ?i , name = ?s WHERE slide_id = ?i ", $data[$id]["position"],  $data[$id]["name"] , $id);
			}

			$notifications->set("The Slider was updated successfully.", "success", true);
			return true;
		}

		return false;
		
	}

	function edit_image($slide_data, $slide_id , $file){
		global $notifications, $db , $filesystem;

		$upload_result = $filesystem->fn_upload($file,_SITE_DIR_.'images/sliders/');


		if (count($slide_data["lang"])>0){

			$db->db_query("UPDATE ".$this->table." SET position = ?i, status = ?s , link = ?s WHERE slide_id = ?i ", $slide_data["type"]["position"], $slide_data["type"]["status"], $slide_data["type"]["link"], $slide_id);
			
			foreach($slide_data["lang"] as $lang_code => $value){

				if(isset($slide_data["lang"][$lang_code]["check"])){
					$db->db_query("UPDATE ".$this->table_langs." SET image = ?s WHERE lang_code = ?s AND slide_id = ?i ", $file['name'], $lang_code, $slide_id);
				}else{
					$db->db_query("UPDATE ".$this->table_langs." SET image = ?s WHERE lang_code = ?s AND slide_id = ?i ", $slide_data["lang"][$lang_code]["value"], $lang_code, $slide_id);
				}
				
			}
		}
		$notifications->set("The Image name was updated successfully.", "success", true);
		
		return true;
	}

	function get($id){
		global $db;

		$lang_data = $db->db_get_hash_array("SELECT lang_code, image FROM ".$this->table_langs." WHERE slide_id = ?i ", 'lang_code',$id);
		$slide_data = $db->db_get_row("SELECT slide_id , position, status , name , link FROM ".$this->table." WHERE slide_id = ?i LIMIT 1", $id);
		
		$data["lang"] = $lang_data;
		$data["type"] = $slide_data;
		return $data;
	}

}