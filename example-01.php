<?php

#Smarty assigns
$smarty->assign("section_title", "Home");
$smarty->assign("section_template", "example-01.tpl");
$smarty->assign("test_var", "Just a Smart test");

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
