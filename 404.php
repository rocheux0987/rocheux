<?php

#Smarty assigns.
$smarty->assign("test", "Just a smarty test");

#Smarty fetch template.
$smarty->display(_TPL_FRONTEND_DIR_."404.tpl");

?>
