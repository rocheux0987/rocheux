<?php 

class cache{

	function get_pet_types(){
		global $db;

		return $db->db_get_array('
			SELECT pt.pet_type_id, pl.value 
			FROM ?:pet_types pt 
			INNER JOIN ?:pet_types_lang pl 
			ON pt.pet_type_id = pl.pet_type_id
			WHERE pl.lang_code = ?s', _CLIENT_LANGUAGE_);
	}

	function get_breed($pet_type_id){
		global $db;

		return $db->db_get_array('
			SELECT ?:breeds.breed_id , value 
			FROM ?:breeds 
			INNER JOIN ?:breeds_lang 
			ON ?:breeds.breed_id = ?:breeds_lang.breed_id 
			WHERE ?:breeds.pet_type_id = ?s AND ?:breeds_lang.lang_code = ?s', 
			$pet_type_id, _CLIENT_LANGUAGE_);

	}

	function get_food($pet_type_id){
		global $db;

		return  $db->db_get_array('
			SELECT ?:foods.food_id, value 
			FROM ?:foods 
			INNER JOIN ?:foods_lang 
			ON ?:foods.food_id = ?:foods_lang.food_id 
			WHERE ?:foods.pet_type_id = ?s AND ?:foods_lang.lang_code = ?s', 
			$pet_type_id, _CLIENT_LANGUAGE_);
	}

	function get_brand($pet_type_id){
		global $db;

		return $db->db_get_array('
			SELECT ?:food_brands.food_brand_id, value 
			FROM ?:food_brands 
			INNER JOIN ?:food_brands_lang 
			ON ?:food_brands.food_brand_id = ?:food_brands_lang.food_brand_id 
			WHERE ?:food_brands.pet_type_id = ?s AND ?:food_brands_lang.lang_code = ?s', 
			$pet_type_id, _CLIENT_LANGUAGE_);	
	}

	function get_style($pet_type_id){
		global $db;

		return $db->db_get_array('
			SELECT ?:food_styles.food_style_id, value 
			FROM ?:food_styles 
			INNER JOIN ?:food_styles_lang 
			ON ?:food_styles.food_style_id = ?:food_styles_lang.food_style_id 
			WHERE ?:food_styles.pet_type_id = ?s AND ?:food_styles_lang.lang_code = ?s', 
			$pet_type_id, _CLIENT_LANGUAGE_);
	}

	function get_specialize(){
		global $db;

		$db->db_get_array('
			SELECT ?:opt_vet_specializations.specialization_id, value 
			FROM ?:opt_vet_specializations 
			INNER JOIN ?:opt_vet_specialization_lang ON ?:opt_vet_specializations.specialization_id = ?:opt_vet_specialization_lang.specialization_id 
			WHERE ?:opt_vet_specialization_lang.lang_code = ?s', 
			_CLIENT_LANGUAGE_);
	}

	function get_country(){
		global $db;
		return $db->db_get_array('SELECT code , country from ?:countries_lang WHERE lang_code = ?s',_CLIENT_LANGUAGE_);
	}

	function get_state($country){
		global $db;
		return $db->db_get_array('
			SELECT DISTINCT state, code 
			FROM ?:states 
			INNER JOIN ?:states_lang ON ?:states.state_id = ?:states_lang.state_id 
			WHERE country_code = ?s', $country);
	}

}