<?php
require("../framework/config/backend.config.php");

if ($_POST){		
	$login_response = $administrators_controller->login($_POST["username"], $_POST["password"]);
	
	if (!is_numeric($login_response)){
		$common->redirect(_BACKEND_LOGIN_URL_."?error=".$login_response);
		die();
	}
	
	$administrators_controller->session_set($login_response);
	$common->redirect("home.php?");
	die();
}

$smarty->assign("site_title", " Rocky superdog / Backend");

$smarty->display(_TPL_BACKEND_DIR_."login.tpl");
?>
