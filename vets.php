<?php

#Controllers
$controllers_loader->load("vet");

switch ($_GET['act']) {
	case 'search':
		foreach ($info as $key => $row){
			$info[$key]['image'] = $images->fn_get_image($row['vet_id'], 'vet', $row['image'])  ;
		}

		$smarty->assign("is_search", true);
		$smarty->assign("data", $info);
		break;
	case 'nearest':
		$info = $vet_controller->get(null , $_GET['lat'] , $_GET['lon']);

		foreach ($info as $key => $row){
			$info[$key]['image'] = $images->fn_get_image($row['vet_id'], 'vet', $row['image']);
		}

		echo '<pre>';
		print_r($info);
		echo '</pre>';
		
		$smarty->assign("data", $info);
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
