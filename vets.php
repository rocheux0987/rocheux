<?php

#Controllers
$controllers_loader->load("vet");

switch ($_GET['act']) {
	case 'search':
		$smarty->assign("is_search", true);
		$smarty->assign("vet", $vet_controller->get($_GET['value']));
		break;
	case 'nearest':
		$smarty->assign("vet", $vet_controller->get(null , $_GET['lat'] , $_GET['lon']));
		$smarty->display(_TPL_FRONTEND_DIR_."ajax_display/nearest.tpl");
		die();
		break;
}




#Smarty assigns
$smarty->assign("section_title", "Vets");
$smarty->assign("section_template", 'vets.tpl');

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
