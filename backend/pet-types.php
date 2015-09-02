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
$controllers_loader->load("pet_types");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"]=="edit"){
		$pet_types = $_POST["pet_types"];
		$lang_code = $_POST["lang_code"];
		
		if ($pet_types_controller->edit($pet_types, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$pet_types_data["lang"] = $_POST["form_data"];
		$pet_types_data["type"] = $_POST["extra_form_data"];
		$pet_type_id = $pet_types_data["type"]["pet_type_id"];
		
		
		
		$pet_types_controller->edit_langs($pet_types_data, $pet_type_id);
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$pet_type = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		$pet_types_controller->add($pet_type, $lang_code);
		$common->redirect($current_module."?act=list");
		die();
	}
	
}

#Methods
if ($_GET["act"] == "list"){
	#Method: LIST
	
	#Section title
	$smarty->assign("site_module", "Pet types");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "#";
	$breadcrumb[2]["title"] = "List";
	$breadcrumb[2]["url"] = "#";
	
	#Search terms (for Pager)
	$search_terms = $db->db_quote(" AND PT.pet_type_id = PTL.pet_type_id ");
	$search_terms.= $db->db_quote(" AND PTL.lang_code = ?s ", _CLIENT_LANGUAGE_);
	
	if (!empty($_GET["pet_type"])){
		$search_terms.= $db->db_quote(" AND PTL.value LIKE ?l ", $_GET["pet_type"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&pet_type=".$_GET["pet_type"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module."?";
	$pager_config["sql"] =  $db->db_quote("SELECT PT.pet_type_id, PT.position, PT.status, PTL.value FROM ".$pet_types_controller->table." PT, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$pet_types_controller->table." PT, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	
	$pet_types_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY PTL.value ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("pet_types_list", $pet_types_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "pet_types_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Pet types");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Add";
	$breadcrumb[2]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	
	#Smarty TPL
	$smarty_tpl = "pet_types_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: MULTILANGUAGE EDIT
	#Section title
	$smarty->assign("site_module", "Pet types");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Edit";
	$breadcrumb[2]["url"] = "#";
	
	$pet_type_data = $pet_types_controller->get($_GET["pet_type_id"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("pet_type_data", $pet_type_data["type"]);
	$smarty->assign("pet_type_lang", $pet_type_data["lang"]);
	
	#Smarty TPL
	$smarty_tpl = "pet_types_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$pet_types_controller->delete($_GET["pet_type_id"]);
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
