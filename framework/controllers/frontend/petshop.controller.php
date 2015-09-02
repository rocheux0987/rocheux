<?php


class petshop{
	var $user_table = "?:users";
	var $merchant_table = "?:merchants";
	var $countries_lang_table = "?:countries_lang";

	function get($search = null , $lat = 0 , $lon = 0){
		global $db;
		if($search != null){
			return $db->db_get_array("
				SELECT merchant.merchant_id , user.email , user.first_name , user.last_name  , user.address , user.city , merchant.image , user.state , user.lat , user.lon, country.country 
				FROM ".$this->user_table." user
				INNER JOIN ".$this->merchant_table." merchant
				ON merchant.user_id = user.user_id
				INNER JOIN ".$this->countries_lang_table." country
				ON country.code = user.country
				WHERE user.type = 'M' AND merchant.status = 'A'  AND country.lang_code = ?s AND merchant.store_name LIKE ?s"
				, _CLIENT_LANGUAGE_ , '%'.$search.'%');
		}else{

			$lat1 = $lat+0.025;
			$lat2 = $lat-0.025;
			$lon1 = $lon+0.025;
			$lon2 = $lon-0.025;

			return $db->db_get_array("
				SELECT merchant.merchant_id , user.email , user.first_name , user.last_name  , user.address , user.city , merchant.image , user.state , user.lat , user.lon, country.country 
				FROM ".$this->user_table." user 
				INNER JOIN ".$this->merchant_table." merchant
				ON merchant.user_id = user.user_id
				INNER JOIN ".$this->countries_lang_table." country
				ON country.code = user.country
				WHERE merchant.status = 'A'  AND country.lang_code = ?s AND 
				user.type = 'M' and user.lat > ?s AND user.lat < ?s  AND user.lon > ?s AND user.lon < ?s"
				, _CLIENT_LANGUAGE_ , $lat2 , $lat1 , $lon2 , $lon1);
		}
	}

}