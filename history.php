<?php

#Smarty assigns
$smarty->assign("section_title", "Video's History");
$smarty->assign("section_template", 'watch/history.tpl');


#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>