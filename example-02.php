<?php

#Smarty assigns
$smarty->assign("section_title", "Home");
$smarty->assign("section_template", "example-02.tpl");
$smarty->assign("test_var", "Just a Smart test");

#Example parsing $_GET array()
if (!empty($_GET["param1"]) && !empty($_GET["param2"])){
	$common->fn_print_r("\$_GET", $_GET["param1"], $_GET["param2"]);
}

#Example parsing $friendly->params array()
if (!empty($friendly->params[2]) && !empty($friendly->params[3])){
	$common->fn_print_r("\$friendly->params", $friendly->params[2], $friendly->params[3]);
}

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
