<?php

#Get data from the database
$data = $db->db_get_array("SELECT id, title, description FROM ?:test WHERE id = ?i OR id = ?i", 1, 2);

#Smarty assigns
$smarty->assign("query_result", $data);
$smarty->assign("section_template", "example-03.tpl");

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
