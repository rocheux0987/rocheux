<?php

#Smarty assigns
$smarty->assign("section_title", "Pet of the Week");
$smarty->assign("section_template", 'watch/petoftheweek.tpl');
$smarty->assign("pets_of_the_week", $pets_of_the_week);


#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>