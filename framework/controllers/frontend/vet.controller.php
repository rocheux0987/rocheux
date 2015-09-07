<?php


class vet{
	var $user_table = "?:users";
	var $vet_table = "?:vets";
	var $countries_lang_table = "?:countries_lang";

	function get($search = null , $lat = 0 , $lon = 0){
		global $db;
		if($search != null){
			$vets_info = $db->db_get_array("
				SELECT vet.vet_id as id, user.email , vet.image , user.first_name , user.last_name , vet.contact_number , user.address , user.city , user.state , user.lat , user.lon, country.country 
				FROM ".$this->user_table." user
				INNER JOIN ".$this->vet_table." vet
				ON vet.user_id = user.user_id
				INNER JOIN ".$this->countries_lang_table." country
				ON country.code = user.country
				WHERE user.type = 'V' vet.status = 'A'  AND country.lang_code = ?s AND vet.notes LIKE ?s"
				, _CLIENT_LANGUAGE_ , '%'.$search.'%');
		}else{

			$lat1 = $lat+0.025;
			$lat2 = $lat-0.025;
			$lon1 = $lon+0.025;
			$lon2 = $lon-0.025;

			$vets_info = $db->db_get_array("
				SELECT vet.vet_id as id, user.email , vet.image , user.first_name , user.last_name , vet.contact_number , user.address , user.city , user.state , user.lat , user.lon, country.country 
				FROM ".$this->user_table." user 
				INNER JOIN ".$this->vet_table ." vet
				ON vet.user_id = user.user_id
				INNER JOIN ".$this->countries_lang_table." country
				ON country.code = user.country
				WHERE vet.status = ?s  AND country.lang_code = ?s AND 
				user.type = 'V' and user.lat > ?s AND user.lat < ?s  AND user.lon > ?s AND user.lon < ?s"
				, 'A' , _CLIENT_LANGUAGE_ , $lat2 , $lat1 , $lon2 , $lon1);
		}
		return $vets_info;
	}

}