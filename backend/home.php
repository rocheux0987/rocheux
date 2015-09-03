<?php
require("../framework/config/backend.config.php");

#Administrators validation
$administrators_controller->session_check();

#Section title
$smarty->assign("site_module", "Home");
$smarty->assign("site_action", "");

#Breadcrumb
$breadcrumb[0]["title"] = "Home";
$breadcrumb[0]["url"] = "#";

$smarty->assign("breadcrumb", $breadcrumb);

#Notifications.
$notifications->assign();

#Smarty assigns
$smarty->assign("breadcrumb", $breadcrumb);

#Smarty TPLs
$smarty->assign("section_content", "main.tpl");
$smarty->display(_TPL_BACKEND_DIR_."layout.tpl");
?>
