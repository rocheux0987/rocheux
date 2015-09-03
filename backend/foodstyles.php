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
$controllers_loader->load("foodstyles, pet_types");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"]=="edit"){
		$foodstyles = $_POST["foodstyles"];
		$lang_code = $_POST["lang_code"];
		
		if ($foodstyles_controller->edit($foodstyles, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$foodstyles_data["lang"] = $_POST["form_data"];
		$foodstyles_data["style"] = $_POST["extra_form_data"];
		$food_style_id = $foodstyles_data["style"]["food_style_id"];
		
		$foodstyles_controller->edit_langs($foodstyles_data, $food_style_id);
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$foodstyles_data = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		if ($foodstyles_controller->add($foodstyles_data, $lang_code)){
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
	$smarty->assign("site_module", "Food styles");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list";
	$breadcrumb[1]["title"] = "Food styles";
	$breadcrumb[1]["url"] = "#";
	
	#Search terms (for Pager)
	$search_terms = $db->db_quote(" AND FB.pet_type_id = PTL.pet_type_id");
	$search_terms.= $db->db_quote(" AND FBL.lang_code = PTL.lang_code ");
	$search_terms.= $db->db_quote(" AND FBL.lang_code = ?s ", _CLIENT_LANGUAGE_);
	$search_terms.= $db->db_quote(" AND FB.food_style_id = FBL.food_style_id");
	
	if (!empty($_GET["foodstyle"])){
		$search_terms.= $db->db_quote(" AND FBL.value LIKE ?l ", $_GET["foodstyle"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&foodstyle=".$_GET["foodstyle"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module . "?";
	$pager_config["sql"] =  $db->db_quote("SELECT FB.food_style_id, FB.position, FB.status, FB.pet_type_id, FBL.value, PTL.value AS pet_type_value FROM ".$foodstyles_controller->table." FB, ".$foodstyles_controller->table_langs." FBL, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$foodstyles_controller->table." FB, ".$foodstyles_controller->table_langs." FBL, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	
	$foodstyles_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY PTL.value ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("foodstyles_list", $foodstyles_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "foodstyles_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Food styles");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[2]["title"] = "Food styles";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Add";
	$breadcrumb[3]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("pet_types", $pet_types_controller->get_all());
	
	#Smarty TPL
	$smarty_tpl = "foodstyles_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Food styles");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[2]["title"] = "Food styles";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Edit";
	$breadcrumb[3]["url"] = "#";
	
	$foodstyle_data = $foodstyles_controller->get($_GET["food_style_id"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("foodstyle_data", $foodstyle_data["style"]);
	$smarty->assign("foodstyle_lang", $foodstyle_data["lang"]);
	
	#Smarty TPL
	$smarty_tpl = "foodstyles_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$foodstyles_controller->delete($_GET["food_style_id"]);
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
