<?php
require("../framework/config/backend.config.php");

#Administrators validation
$administrators_controller->session_check();

#Check administrator privileges
if (!$administrators_controller->module_check(3)){
	$notifications->set("You dont have access to SEO RULES module.", "error", true);
	$common->redirect("home.php?");
	die();
}

#Controllers
$controllers_loader->load("seorules");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"]=="edit"){
		$seo_rules = $_POST["seo_rules"];
		$lang_code = $_POST["lang_code"];
		
		if ($seorules_controller->edit($seo_rules, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$seorules_data = $_POST["form_data"];
		$seorules_name = $_POST["extra_form_data"]["name"];
		
		$seorules_controller->edit_langs($seorules_data, $seorules_name);
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$seo_rules = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		$seorules_controller->add($seo_rules, $lang_code);
		$common->redirect($current_module."?act=list");
		die();
	}
	
}

#Methods
if ($_GET["act"] == "list"){
	#Method: LIST
	
	#Section title
	$smarty->assign("site_module", "SEO Rules");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "SEO Rules";
	$breadcrumb[1]["url"] = "#";
	$breadcrumb[2]["title"] = "Edit";
	$breadcrumb[2]["url"] = "#";
	
	#Search terms (for Pager)
	$search_terms = $db->db_quote(" AND lang_code = ?s ", _CLIENT_LANGUAGE_);
	
	if (!empty($_GET["name"])){
		$search_terms.= $db->db_quote(" AND name LIKE ?l ", $_GET["name"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&name=".$_GET["name"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module."?";
	$pager_config["sql"] =  $db->db_quote("SELECT * FROM ".$seorules_controller->table." WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$seorules_controller->table." WHERE 1 ".$search_terms);
	
	$seorules_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY name ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("seorules_list", $seorules_list);
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "seorules_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "SEO Rules");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "SEO Rules";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Add";
	$breadcrumb[2]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	
	#Smarty TPL
	$smarty_tpl = "seorules_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "SEO Rules");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "SEO Rules";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Edit";
	$breadcrumb[3]["url"] = "#";
	
	$seorules_data = $seorules_controller->get($_GET["name"]);

	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("seorules_data", $seorules_data);
	
	#Smarty TPL
	$smarty_tpl = "seorules_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	
	$seorules_controller->delete($_GET["name"]);
	$common->redirect($current_module."?act=list");
	die();
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
