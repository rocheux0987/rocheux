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
$controllers_loader->load("breeds, pet_types");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"]=="edit"){
		$breeds_data = $_POST["breeds"];
		$lang_code = $_POST["lang_code"];
		
		if ($breeds_controller->edit($breeds_data, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$breeds_data["lang"] = $_POST["form_data"];
		$breeds_data["breed"] = $_POST["extra_form_data"];
		$breed_id = $breeds_data["breed"]["breed_id"];
		
		$breeds_controller->edit_langs($breeds_data, $breed_id);
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$breeds_data = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];

		if ($breeds_controller->add($breeds_data, $lang_code)){
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
	$smarty->assign("site_module", "Breeds");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list";
	$breadcrumb[1]["title"] = "Breeds";
	$breadcrumb[1]["url"] = "#";
	
	#Search terms (for Pager)
	$search_terms = $db->db_quote(" AND B.breed_id = BL.breed_id");
	$search_terms.= $db->db_quote(" AND B.pet_type_id = PTL.pet_type_id");
	$search_terms.= $db->db_quote(" AND BL.lang_code = ?s ", _CLIENT_LANGUAGE_);
	$search_terms.= $db->db_quote(" AND BL.lang_code = PTL.lang_code ");
	
	if (!empty($_GET["breed"])){
		$search_terms.= $db->db_quote(" AND BL.value LIKE ?l ", $_GET["breed"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&breed=".$_GET["breed"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module."?";
	$pager_config["sql"] =  $db->db_quote("SELECT B.breed_id, B.position, B.status, BL.value, PTL.value AS pet_type_value, PTL.pet_type_id FROM ".$breeds_controller->table." B, ".$breeds_controller->table_langs." BL, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$breeds_controller->table." B, ".$breeds_controller->table_langs." BL, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	
	$breeds_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY PTL.value ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("breeds_list", $breeds_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "breeds_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Breeds");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Breeds";
	$breadcrumb[1]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Add";
	$breadcrumb[2]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("pet_types", $pet_types_controller->get_all());
	
	#Smarty TPL
	$smarty_tpl = "breeds_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: Edit
	
	#Section title
	$smarty->assign("site_module", "Breeds");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[3]["title"] = "Breeds";
	$breadcrumb[3]["url"] = $current_module."?act=list&";
	$breadcrumb[2]["title"] = "Edit";
	$breadcrumb[2]["url"] = "#";
	
	$breed_data = $breeds_controller->get($_GET["breed_id"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("breed_data", $breed_data["breed"]);
	$smarty->assign("breed_lang", $breed_data["lang"]);
	
	#Smarty TPL
	$smarty_tpl = "breeds_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$breeds_controller->delete($_GET["breed_id"]);
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
