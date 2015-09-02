<?php
require("../framework/config/backend.config.php");

#Administrators validation
$administrators_controller->session_check();

#Check administrator privileges
if (!$administrators_controller->module_check(2)){
	$notifications->set("You dont have access to ADMINISTRATION module.", "error", true);
	$common->redirect("home.php?");
	die();
}

#Controllers
$controllers_loader->load("languages");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"]=="edit"){
		$lang_data = $_POST["lang_vars"];
		$lang_code = $_POST["lang_code"];
		
		if ($languages_controller->edit($lang_data, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$language_data = $_POST["form_data"];
		$name = $_POST["extra_form_data"]["name"];
		
		$languages_controller->edit_langs($language_data, $name);
		
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$lang_data = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		$languages_controller->add($lang_data, $lang_code);
		$common->redirect($current_module."?act=list");
		die();
		
	}
	
}

#Methods
if ($_GET["act"] == "list"){
	#Method: LIST
	
	#Section title
	$smarty->assign("site_module", "Administration / Languages");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Administration";
	$breadcrumb[1]["url"] = "#";
	$breadcrumb[2]["title"] = "Languages";
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
	$pager_config["sql"] =  $db->db_quote("SELECT * FROM ".$languages->table." WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$languages->table." WHERE 1 ".$search_terms);
	
	$languages_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY name ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("languages_list", $languages_list);
	$smarty->assign("url_delete", "?act=delete");
	$smarty->assign("url_edit", "?act=edit");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "administration_languages_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Administration / Languages");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Administration";
	$breadcrumb[1]["url"] = "#";
	$breadcrumb[2]["title"] = "Languages";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Add";
	$breadcrumb[3]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	
	#Smarty TPL
	$smarty_tpl = "administration_languages_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: MULTILANGUAGE EDIT
	$smarty->assign("site_module", "Languages");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Languages";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Edit";
	$breadcrumb[2]["url"] = "#";
	
	$language_data = $languages_controller->get($_GET["name"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("language_data", $language_data);
	
	#Smarty TPL
	$smarty_tpl = "administration_languages_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	
	$languages_controller->delete($_GET["name"]);
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
