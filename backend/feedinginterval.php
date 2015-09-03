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
$controllers_loader->load("feedinginterval");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){

	if ($_GET["act"]=="edit"){
		$feedingintervals = $_POST["feedingintervals"];
		$lang_code = $_POST["lang_code"];
		
		if ($feedinginterval_controller->edit($feedingintervals, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$feedingintervals_data["lang"] = $_POST["form_data"];
		$feedingintervals_data["feeding_interval"] = $_POST["extra_form_data"];
		$feeding_interval_id = $feedingintervals_data["feeding_interval"]["feeding_interval_id"];
		
		$feedinginterval_controller->edit_langs($feedingintervals_data, $feeding_interval_id);
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$feedingintervals_data = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		if ($feedinginterval_controller->add($feedingintervals_data, $lang_code)){
			$common->redirect($current_module."?act=list");
		}else{
			$common->redirect($current_module."?act=add");
		}
		die();
	}
	
}

#Methods
if ($_GET["act"] == "list"){
	#Method: LIST
	
	#Section title
	$smarty->assign("site_module", "Feeding Intervals");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "feedingintervals";
	$breadcrumb[1]["url"] = "#";
	
	#Search terms (for Pager)
	//$search_terms.= $db->db_quote(" AND FBL.lang_code = PTL.lang_code ");
	$search_terms.= $db->db_quote(" AND FBL.lang_code = ?s ", _CLIENT_LANGUAGE_);
	$search_terms.= $db->db_quote(" AND FB.feeding_interval_id = FBL.feeding_interval_id");
	
	if (!empty($_GET["feeding_interval"])){
		$search_terms.= $db->db_quote(" AND FBL.value LIKE ?l ", $_GET["feeding_interval"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&feeding_interval=".$_GET["feeding_interval"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module . "?";
	$pager_config["sql"] =  $db->db_quote("SELECT FB.feeding_interval_id, FB.position, FB.status, FBL.value FROM ".$feedinginterval_controller->table." FB, ".$feedinginterval_controller->table_langs." FBL WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$feedinginterval_controller->table." FB, ".$feedinginterval_controller->table_langs." FBL WHERE 1 ".$search_terms);
	
	$feedingintervals_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY FBL.value ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("feedingintervals_list", $feedingintervals_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "feedinginterval_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Feeding Intervals");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[2]["title"] = "Feeding intervals";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Add";
	$breadcrumb[3]["url"] = "#";
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");

	#Smarty TPL
	$smarty_tpl = "feedinginterval_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Feeding Intervals");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[2]["title"] = "Feeding Intervals";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Edit";
	$breadcrumb[3]["url"] = "#";
	
	$feedingintervals_data = $feedinginterval_controller->get($_GET["feeding_interval_id"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("feedingintervals_data", $feedingintervals_data["feeding_interval"]);
	$smarty->assign("feedingintervals_lang", $feedingintervals_data["lang"]);
	
	#Smarty TPL
	$smarty_tpl = "feedinginterval_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$feedinginterval_controller->delete($_GET["feeding_interval_id"]);
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
