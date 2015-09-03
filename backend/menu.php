<?php
require("../framework/config/backend.config.php");

#Administrators validation
$administrators_controller->session_check();

#Check administrator privileges
if (!$administrators_controller->module_check(1)){
	$notifications->set("You dont have access to ADMINISTRATORS module.", "error", true);
	$common->redirect("home.php?");
	die();
}

#Load controller
$controllers_loader->load("menu");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"] == "add"){
		$menu_data = $_POST["form_data"];

		if ($menu_controller->add($menu_data)){
			$common->redirect($current_module."?act=list");
		}else{
			$common->redirect($current_module."?act=add");
		}
		die();
		
	}elseif($_GET["act"] == "edit"){
		$menu_data = $_POST["menu"];

		if ($menu_controller->edit($menu_data)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}
}

#Methods
if ($_GET["act"] == "list"){
	#Method: LIST
	
	#Section title
	$smarty->assign("site_module", "Menu");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Menu";
	$breadcrumb[1]["url"] = $current_module."?act=list";
	$breadcrumb[2]["title"] = "List";
	$breadcrumb[2]["url"] = "#";

	$menu_list = $menu_controller->get_options_val();
	
	#Smarty assigns
	$smarty->assign("user_modules", $_SESSION["user_modules"]);
	$smarty->assign("menu_list", $menu_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "menu_list.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$menu_list = $menu_controller->get_options_val();

	$menu_controller->delete($_GET["menu_id"], $menu_list);
	$common->redirect($current_module."?act=list");
	die();
}else{
	#Method: ADD
	
	#Section title
	$smarty->assign("site_module", "Menu");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Menu";
	$breadcrumb[1]["url"] = "administrators-list.php?";
	$breadcrumb[2]["title"] = "Add";
	$breadcrumb[2]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("user_modules", $_SESSION["user_modules"]);
	$smarty->assign("cancel_url", $current_module."?act=list&");
	
	#Smarty TPL
	$smarty_tpl = "menu_add.tpl";
}

#Notifications.
$notifications->assign();

#Smarty assigns
$smarty->assign("act", $_GET["act"]);
$smarty->assign("breadcrumb", $breadcrumb);

#Smarty TPLs
$smarty->assign("section_content", $smarty_tpl);
$smarty->display(_TPL_BACKEND_DIR_."layout.tpl");
?>
