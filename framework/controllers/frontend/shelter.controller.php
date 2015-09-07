<?php


class shelter{
	var $user_table = "?:users";
	var $foundation_table = "?:foundations";
	var $countries_lang_table = "?:countries_lang";

	function get($search = null , $lat = 0 , $lon = 0){
		global $db;
		if($search != null){
			return $db->db_get_array("
				SELECT foundations.foundation_id as id, user.email , user.first_name , user.last_name  , user.address , user.city , foundations.image ,  foundations.name  , foundations.contact_number , foundations.website , foundations.work_shedules , user.state , user.lat , user.lon, country.country 
				FROM ".$this->user_table." user
				INNER JOIN ".$this->foundation_table." foundations
				ON foundations.user_id = user.user_id
				INNER JOIN ".$this->countries_lang_table." country
				ON country.code = user.country
				WHERE user.type = 'P' AND foundations.status = 'A'  AND country.lang_code = ?s AND foundations.name LIKE ?s"
				, _CLIENT_LANGUAGE_ , '%'.$search.'%');
		}else{

			$lat1 = $lat+0.025;
			$lat2 = $lat-0.025;
			$lon1 = $lon+0.025;
			$lon2 = $lon-0.025;

			return $db->db_get_array("
				SELECT foundations.foundation_id as id, user.email , user.first_name , user.last_name  , user.address , user.city , foundations.image ,  foundations.name  , foundations.contact_number , foundations.website , foundations.work_shedules , user.state , user.lat , user.lon, country.country 
				FROM ".$this->user_table." user 
				INNER JOIN ".$this->foundation_table." foundations
				ON foundations.user_id = user.user_id
				INNER JOIN ".$this->countries_lang_table." country
				ON country.code = user.country
				WHERE foundations.status = 'A'  AND country.lang_code = ?s AND 
				user.type = 'P' and user.lat > ?s AND user.lat < ?s  AND user.lon > ?s AND user.lon < ?s"
				, _CLIENT_LANGUAGE_ , $lat2 , $lat1 , $lon2 , $lon1);
		}
	}

}