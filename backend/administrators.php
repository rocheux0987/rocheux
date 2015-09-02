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

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"] == "save"){
		
		$administrator_data = $_POST["form_data"];
		if ($administrators_controller->add($administrator_data)){
			$common->redirect($current_module."?act=list");
		}else{
			$common->redirect($current_module."?act=add");
		}
		die();
		
	}elseif($_GET["act"] == "edit"){
		$administrator_data = $_POST["form_data"];
		if ($administrators_controller->edit($_GET["id"], $administrator_data)){
			$common->redirect($current_module."?act=list");
		}else{
			$common->redirect($current_module."?act=add");
		}
		die();
	}
}

#Methods
if ($_GET["act"] == "edit"){
	#Method: EDIT
	
	#Section title
	$smarty->assign("site_module", "Administrators");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Administrators";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Update";
	$breadcrumb[2]["url"] = "#";
	
	if (!$administrators_controller->exists("id", $_GET["id"])){
		$notifications->set("The administrator that you wanna edit no longer exists.", "error", true);
		$common->redirect($current_module."?act=list&");
		die();
	}
	
	$administrator_data = $administrators_controller->get("id", $_GET["id"]);
	$administrator_data["modules"] = explode("|", $administrator_data["modules"]);
	$notifications->set("To reset a password complete the two fields (Password reset & Confirm new password)", "info", false);
	
	#Smarty assigns
	$smarty->assign("user_modules", $_SESSION["user_modules"]);
	$smarty->assign("data", $administrator_data);
	$smarty->assign("cancel_url", $current_module."?act=list&");
	
	#Smarty TPL
	$smarty_tpl = "administrators_update.tpl";
	
}elseif ($_GET["act"] == "list"){
	#Method: LIST
	
	#Section title
	$smarty->assign("site_module", "Administrators");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Administrators";
	$breadcrumb[1]["url"] = $current_module."?act=list";
	$breadcrumb[2]["title"] = "List";
	$breadcrumb[2]["url"] = "#";
	
	#Search terms (for Pager)
	if (!empty($_GET["email"])){
		$search_terms.= $db->db_quote(" AND email LIKE ?l ", $_GET["email"]."%");
	}
	if (!empty($_GET["username"])){
		$search_terms.= $db->db_quote(" AND username LIKE ?l ", $_GET["username"]."%");
	}
	if (!empty($_GET["status"])){
		if ($_GET["status"]){
			$status = 1;
		}else{
			$status = 0;
		}
		$search_terms.= $db->db_quote(" AND status = ?i ", $status);
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&email=".$_GET["email"]."&username=".$_GET["username"]."&status=".$_GET["status"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module."?";
	$pager_config["sql"] =  $db->db_quote("SELECT * FROM ".$administrators_controller->administrators_table." WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$administrators_controller->administrators_table." WHERE 1 ".$search_terms);
	
	$administrators_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY id DESC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("user_modules", $_SESSION["user_modules"]);
	$smarty->assign("administrators_list", $administrators_list);
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_delete", "?act=delete&");
	
	#Smarty TPL
	$smarty_tpl = "administrators_list.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	
	$administrators_controller->delete($_GET["id"]);
	$common->redirect($current_module."?act=list");
	die();
}else{
	#Method: ADD
	
	#Section title
	$smarty->assign("site_module", "Administrators");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Administrators";
	$breadcrumb[1]["url"] = "administrators-list.php?";
	$breadcrumb[2]["title"] = "Add";
	$breadcrumb[2]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("user_modules", $_SESSION["user_modules"]);
	$smarty->assign("cancel_url", $current_module."?act=list&");
	
	#Smarty TPL
	$smarty_tpl = "administrators_update.tpl";
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
