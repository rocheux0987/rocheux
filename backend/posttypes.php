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
$controllers_loader->load("posttypes");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){

	if ($_GET["act"]=="edit"){
		$posttypes = $_POST["posttypes"];
		$lang_code = $_POST["lang_code"];
		
		if ($posttypes_controller->edit($posttypes, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$posttypes_data["lang"] = $_POST["form_data"];
		$posttypes_data["posttype"] = $_POST["extra_form_data"];
		$type_id = $posttypes_data["posttype"]["type_id"];
		
		if($posttypes_controller->edit_langs($posttypes_data, $type_id)){
		$common->redirect($current_module."?act=list");
		die();
		}
	}elseif($_GET["act"] == "add"){	
		$posttypes_data = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		if ($posttypes_controller->add($posttypes_data, $lang_code)){
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
	$smarty->assign("site_module", "Post Types/Tags");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Post Types/Tags";
	$breadcrumb[1]["url"] = "#";
	
	#Search terms (for Pager)
	//$search_terms.= $db->db_quote(" AND FBL.lang_code = PTL.lang_code ");
	$search_terms.= $db->db_quote(" AND FBL.lang_code = ?s ", _CLIENT_LANGUAGE_);
	$search_terms.= $db->db_quote(" AND FB.type_id = FBL.type_id");
	
	if (!empty($_GET["posttype"])){
		$search_terms.= $db->db_quote(" AND FBL.value LIKE ?l ", $_GET["posttype"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&posttype=".$_GET["posttype"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module . "?";
	$pager_config["sql"] =  $db->db_quote("SELECT FB.type_id, FB.position, FB.status, FBL.value FROM ".$posttypes_controller->table." FB, ".$posttypes_controller->table_langs." FBL WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$posttypes_controller->table." FB, ".$posttypes_controller->table_langs." FBL WHERE 1 ".$search_terms);
	
	$posttypes_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY FBL.value ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("posttypes_list", $posttypes_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "posttype_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Post Types/Tags");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Post Types/Tags";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Add";
	$breadcrumb[2]["url"] = "#";
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");

	#Smarty TPL
	$smarty_tpl = "posttype_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Post Types/Tags");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Post Types/Tags";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Edit";
	$breadcrumb[2]["url"] = "#";
	
	$posttypes_data = $posttypes_controller->get($_GET["type_id"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("posttypes_data", $posttypes_data["posttype"]);
	$smarty->assign("posttypes_lang", $posttypes_data["lang"]);
	
	#Smarty TPL
	$smarty_tpl = "posttype_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$posttypes_controller->delete($_GET["type_id"]);
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
