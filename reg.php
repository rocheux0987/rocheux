<?php

if(isset($_SESSION['user_data'])){
	$common->redirect($friendly->get_seourl_by_module("home"));
	exit();
}

#Controllers
$controllers_loader->load("user");
//move to controller (create cache)
$country = $user_controller->get_country();

#Smarty assigns
$smarty->assign("country", $country);
$smarty->assign("reg", true);
$smarty->assign("section_template", 'registration/reg.tpl');
$smarty->assign("section_title", "Register");


if(isset($_POST['act'])){
	$pet_type_id = $_POST['act']; 
	$type = $_POST['type'];

	//breed
	//move to controller
	$breed = $user_controller->get_breed($pet_type_id);

	//food
	//move to controller
	$food = $user_controller->get_food($pet_type_id);

	//brand
	//move to controller
	$brand = $user_controller->get_brand($pet_type_id);

	//style
	//move to controller
	$style = $user_controller->get_style($pet_type_id);

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
		
		if ($_POST['reg_form']['user_type'] == 'B' || $_POST['reg_form']['user_type'] == 'V'){
			//move to controller
			$pet = $user_controller->get_pet_types();
		}
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
				$smarty->assign("section_title", "Register - Pet Foundation Details");
				$smarty->assign("user_type" , 'P');
				break;
			case 'V':
				$smarty->assign("section_title", "Register - Veterinarian Details");
				//move to controller
				$spec = $user_controller->get_specialize();

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

		$data = $user_controller->register($_POST , $_FILES , $_POST['reg_form']['user_type']);	
		$user_controller->go_login($data);
		

		$smarty->assign("name" , $data['user']['first_name'].' '.$data['user']['last_name']);
		$smarty->assign("section_template", 'thanks.tpl');
		
	}

}else{

	if(isset($_GET['act'])){

		if($_GET['act'] == 'second step'){
			$common->redirect($friendly->get_seourl_by_module("home"));
		}

		$country = $_GET['act']; 
		
		//move to controller
		$state = $user_controller->get_state($country);
		
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
		}else{
			echo '
				<div class="valmsg arrow_left hide">
                    <small></small>
                </div>';
            echo '<input type="text" class="validation_check" name="reg_form[state]" placeholder="State">';
		}
		die();
	}

}




#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>