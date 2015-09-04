<?php

#Controllers
$controllers_loader->load("petshop");

switch ($_GET['act']) {
	case 'search':
		$info = $petshop_controller->get($_GET['value']);

		foreach ($info as $key => $row){
			$info[$key]['image'] = $images->fn_get_image($row['merchant_id'], 'merchant', $row['image'])  ;
		}

		$smarty->assign("is_search", true);
		$smarty->assign("data", $info);
		break;
	case 'nearest':
		$info = $petshop_controller->get(null , $_GET['lat'] , $_GET['lon']);

		foreach ($info as $key => $row){
			$info[$key]['image'] = $images->fn_get_image($row['merchant_id'], 'merchant', $row['image'])  ;
		}
		
		$smarty->assign("data", $info);
		$smarty->display(_TPL_FRONTEND_DIR_."ajax_display/nearest.tpl");
		die();
		break;
}



#Smarty assigns
$smarty->assign("section_title", "Petshop");
$smarty->assign("section_template", 'petshop.tpl');

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
