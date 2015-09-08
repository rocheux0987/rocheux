<?php

#Smarty assigns
$smarty->assign("section_title", "Pet Friendly");


switch ($_GET['address']) {
	case 'restaurants':
		$smarty->assign("section_template", 'pet_friendly/restaurants.tpl');
		break;
	case 'hotels':
		$smarty->assign("section_template", 'pet_friendly/hotels.tpl');
		break;
	case 'park and beach':
		$smarty->assign("section_template", 'pet_friendly/park.tpl');
		break;
	case 'travel':
		$smarty->assign("section_template", 'pet_friendly/travel.tpl');
		break;
	default:
		$smarty->assign("section_template", '404.tpl');
		break;
}




#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");