<?php
#Primary config
require("framework/config/frontend.config.php");


#TODO: documentar
$smarty->assign("ga_code", _GA_CODE_);
$smarty->assign("site_url_base", _SITE_URL_);
$smarty->assign("current_url", $_SERVER["REQUEST_URI"]);

include($friendly->page);

?>
