<?php

#Smarty assigns
$smarty->assign("section_title", "What to Watch");
$smarty->assign("section_template", 'watch/myuploads.tpl');

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>