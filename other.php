<?php 
$pets_of_the_week = $db->db_get_array("select pet_id, name, image from ?:pets limit ?i",3);

foreach ($pets_of_the_week as $key=>$pet){
	$pets_of_the_week[$key]['image'] = $images->fn_get_image($pet['pet_id'], 'pet', $pet['image']);
}

//$common->fn_print_r($pets_of_the_week);

#Smarty assigns
$smarty->assign("section_title", "Home");
$smarty->assign("section_template", 'home.tpl');
$smarty->assign("pets_of_the_week", $pets_of_the_week);

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>