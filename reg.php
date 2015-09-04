<?php

if(isset($_SESSION['user_data'])){
	$common->redirect("../home");
	exit();
}

#Controllers
$controllers_loader->load("user");
$country = $db->db_get_array('SELECT code , country from ?:countries_lang WHERE lang_code = ?s',_CLIENT_LANGUAGE_);
$pet = $db->db_get_array('SELECT pet_type_id , value from ?:pet_types_lang WHERE lang_code = ?s',_CLIENT_LANGUAGE_);

#Smarty assigns
$smarty->assign("country", $country);
$smarty->assign("reg", true);
$smarty->assign("section_template", 'registration/reg.tpl');
$smarty->assign("section_title", "Register");


if(isset($_POST['act'])){
	$pet = $_POST['act']; 
	$type = $_POST['type'];

	//breed
	$breed = $db->db_get_array('SELECT ?:breeds.breed_id, value FROM ?:breeds INNER JOIN ?:breeds_lang ON ?:breeds.breed_id = ?:breeds_lang.breed_id WHERE ?:breeds.pet_type_id = ?s AND ?:breeds_lang.lang_code = ?s', $pet, _CLIENT_LANGUAGE_);

	//food
	$food = $db->db_get_array('SELECT ?:foods.food_id, value FROM ?:foods INNER JOIN ?:foods_lang ON ?:foods.food_id = ?:foods_lang.food_id WHERE ?:foods.pet_type_id = ?s AND ?:foods_lang.lang_code = ?s', $pet, _CLIENT_LANGUAGE_);

	//brand
	$brand = $db->db_get_array('SELECT ?:food_brands.food_brand_id, value FROM ?:food_brands INNER JOIN ?:food_brands_lang ON ?:food_brands.food_brand_id = ?:food_brands_lang.food_brand_id WHERE ?:food_brands.pet_type_id = ?s AND ?:food_brands_lang.lang_code = ?s', $pet, _CLIENT_LANGUAGE_);

	//style
	$style = $db->db_get_array('SELECT ?:food_styles.food_style_id, value FROM ?:food_styles INNER JOIN ?:food_styles_lang ON ?:food_styles.food_style_id = ?:food_styles_lang.food_style_id WHERE ?:food_styles.pet_type_id = ?s AND ?:food_styles_lang.lang_code = ?s', $pet, _CLIENT_LANGUAGE_);

	switch ($type) {
		case 'pet':
			if(count($breed) > 0){
					echo '
						<div class="valmsg arrow_left hide">
                            <small></small>
                        </div>';
					echo '<select name="petreg_form[breed_id]" class="pet validation_check" data-type="breed"> ';
					echo '<option value="">- SELECT BREED -</option>';
					foreach ($breed as $key => $row) {
						echo '<option value="'.$row['breed_id'].'">'.$row['value'].'</option>';
					}
					echo '</select>';
				}else{
					return false;
				}
			break;
		case 'breed':
			if(count($food) > 0){
					echo '
						<div class="valmsg arrow_left hide">
                            <small></small>
                        </div>';
					echo '<select name="petreg_form[food_id]" class="pet validation_check" data-type="food">';
					echo '<option value="">- SELECT FOOD -</option>';
					foreach ($food as $key => $row) {
						echo '<option value="'.$row['food_id'].'">'.$row['value'].'</option>';
					}
					echo '</select>';
				}else{
					return false;
				}
			break;
		case 'food':
			if(count($brand) > 0){
					echo '
						<div class="valmsg arrow_left hide">
                            <small></small>
                        </div>';
					echo '<select name="petreg_form[food_brand_id]" class="pet validation_check" data-type="foodbrand">';
					echo '<option value="">- SELECT BRAND -</option>';
					foreach ($brand as $key => $row) {
						echo '<option value="'.$row['food_brand_id'].'">'.$row['value'].'</option>';
					}
					echo '</select>';
				}else{
					return false;
				}
			break;
			case 'foodbrand':
			if(count($style) > 0){
					echo '
						<div class="valmsg arrow_left hide">
                            <small></small>
                        </div>';
					echo '<select name="petreg_form[food_style_id]" class="pet validation_check" data-type="foodstyle">';
					echo '<option value="">- SELECT STYLE -</option>';
					foreach ($style as $key => $row) {
						echo '<option value="'.$row['food_style_id'].'">'.$row['value'].'</option>';
					}
					echo '</select>';
				}else{
					return false;
				}
			break;
		default:
			# code...
			break;
	}
	die();
}


if($_POST){

	$smarty->assign("reg_form" , $_POST['reg_form']);

	if($_GET['act'] == 'first step'){
		$smarty->assign("prev", true);
	}else if($_GET['act'] == 'second step'){
		switch ($_POST['reg_form']['user_type']) {
			case 'B':
				$smarty->assign("section_title", "Register - Add Pet Details");
				$smarty->assign("section_template", 'registration/petreg.tpl');
				$smarty->assign("user_type" , 'B');
				$smarty->assign("pet", $pet);
				$smarty->assign("breed", $breed);
				$smarty->assign("food", $food);
				$smarty->assign("brand", $brand);
				$smarty->assign("style", $style);

				break;
			case 'M':
				$smarty->assign("section_template", 'registration/merreg.tpl');
				$smarty->assign("section_title", "Register - Pet shop Details");
				$smarty->assign("user_type" , 'M');
				break;
			case 'P':
				$smarty->assign("section_template", 'registration/foundationreg.tpl');
				break;
			case 'V':
				$smarty->assign("section_title", "Register - Veterinarian Details");
				$spec = $db->db_get_array('SELECT ?:opt_vet_specializations.specialization_id, value FROM ?:opt_vet_specializations INNER JOIN ?:opt_vet_specialization_lang ON ?:opt_vet_specializations.specialization_id = ?:opt_vet_specialization_lang.specialization_id WHERE ?:opt_vet_specialization_lang.lang_code = ?s', _CLIENT_LANGUAGE_);
				$pet = $db->db_get_array('SELECT ?:pet_types.pet_type_id, value FROM ?:pet_types INNER JOIN ?:pet_types_lang ON ?:pet_types.pet_type_id = ?:pet_types_lang.pet_type_id WHERE ?:pet_types_lang.lang_code = ?s', _CLIENT_LANGUAGE_);
				$smarty->assign("specialization", $specialization);
				$smarty->assign("spec", $spec);
				$smarty->assign("pet", $pet);
				$smarty->assign("user_type" , 'V');
				$smarty->assign("section_template", 'registration/vetreg.tpl');
				break;
			default:
				# code...
				break;
		}
	}else if($_GET['act'] == 'third step'){

		$id = $user_controller->register($_POST , $_FILES , $_POST['reg_form']['user_type']);

		$sql = $db->db_get_array('SELECT country , status , email , first_name , last_name , type , user_id FROM ?:users WHERE user_id = ?s' , $id);
		
		
		//LOGIN AREA
		$response["status"] = 1;

		$_SESSION['user_data']['user_id'] = $sql[0]['user_id'];
		$_SESSION['user_data']['user_type'] = $sql[0]['type'];
		$_SESSION['user_data']['country'] = $sql[0]['country'];
		$_SESSION['user_data']['user_name'] = $sql[0]['first_name'].' '.$sql[0]['last_name'];

		if($sql[0]['type'] == 'B'){
			$pet = $db->db_get_array('SELECT name , pet_id FROM ?:pets WHERE user_id = ?i ORDER BY pet_id ASC LIMIT 1' , $sql[0]['user_id']);
			$_SESSION['pet_data'] ['pet_id'] = $pet[0]['pet_id'];
			$_SESSION['pet_data'] ['pet_name'] = $pet[0]['name'];
		}
		

		//redirect to home
		echo '<script>window.location.reload();</script>';
	}

}else{
	if(isset($_GET['act'])){
		$country = $_GET['act']; 

		$state = $db->db_get_array('SELECT DISTINCT state, code FROM ?:states INNER JOIN ?:states_lang ON ?:states.state_id = ?:states_lang.state_id WHERE country_code = ?s', $country);
		
		if(count($state) > 0){
			echo '
				<div class="valmsg arrow_left hide">
                    <small></small>
                </div>';
			echo '<select class="validation_check" name="reg_form[state]">';
			foreach ($state as $key => $row) {
				echo '<option value="'.$row['code'].'">'.$row['state'].'</option>';
			}
			echo '</select>';
		}
		die();
	}

}

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>