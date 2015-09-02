<?php

if ($_GET["act"] == "get_ajax_data"){
	#Get data from the database
	$data = $db->db_get_array("SELECT id, title, description FROM ?:test WHERE id = ?i OR id = ?i", 1, 2);
	echo json_encode($data);
	die();
}

#Smarty assigns
$smarty->assign("query_result", $data);
$smarty->assign("section_template", "example-04.tpl");

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
