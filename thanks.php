<?php

#Smarty assigns
$smarty->assign("section_title", "Welcome!");
$smarty->assign("section_template", 'thanks.tpl');
$smarty->assign("reg", true);


#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>