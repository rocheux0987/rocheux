<?php

#Smarty assigns
$smarty->assign("section_title", "Pet Needs");


switch ($_GET['address']) {
	case 'groomers':
		$smarty->assign("section_template", 'pet_wants/groomers.tpl');
		break;
	case 'walkers':
		$smarty->assign("section_template", 'pet_wants/walkers.tpl');
		break;
	case 'boarding':
		$smarty->assign("section_template", 'pet_wants/boarding.tpl');
		break;
	case 'dating':
		$smarty->assign("section_template", 'pet_wants/dating.tpl');
		break;
	default:
		$smarty->assign("section_template", '404.tpl');
		break;
}




#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");