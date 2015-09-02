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
$controllers_loader->load("foodbrands, pet_types");

#Module name
$current_module = basename(__FILE__);

#Save
if ($_POST){
	
	if ($_GET["act"]=="edit"){
		$foodbrands = $_POST["foodbrands"];
		$lang_code = $_POST["lang_code"];
		
		if ($foodbrands_controller->edit($foodbrands, $lang_code)){
			$common->redirect($current_module."?act=list");
			die();
		}
	}elseif($_GET["act"] == "edit_langs"){
		$foodbrands_data["lang"] = $_POST["form_data"];
		$foodbrands_data["brand"] = $_POST["extra_form_data"];
		$food_brand_id = $foodbrands_data["brand"]["food_brand_id"];
		
		$foodbrands_controller->edit_langs($foodbrands_data, $food_brand_id);
		$common->redirect($current_module."?act=list");
		die();
	}elseif($_GET["act"] == "add"){	
		$foodbrands_data = $_POST["form_data"];
		$lang_code = $_POST["lang_code"];
		
		if ($foodbrands_controller->add($foodbrands_data, $lang_code)){
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
	$smarty->assign("site_module", "Food brands");
	$smarty->assign("site_action", "List");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list";
	$breadcrumb[1]["title"] = "Food brands";
	$breadcrumb[1]["url"] = "#";
	
	#Search terms (for Pager)
	$search_terms = $db->db_quote(" AND FB.pet_type_id = PTL.pet_type_id");
	$search_terms.= $db->db_quote(" AND FBL.lang_code = PTL.lang_code ");
	$search_terms.= $db->db_quote(" AND FBL.lang_code = ?s ", _CLIENT_LANGUAGE_);
	$search_terms.= $db->db_quote(" AND FB.food_brand_id = FBL.food_brand_id");
	
	if (!empty($_GET["foodbrand"])){
		$search_terms.= $db->db_quote(" AND FBL.value LIKE ?l ", $_GET["foodbrand"]."%");
	}
	
	#Extra params (for Pager)
	$extra_params = "act=list&foodbrand=".$_GET["foodbrand"];	
	
	#Pager
	$pager_config["current_url"] = _BACKEND_URL_ . $current_module . "?";
	$pager_config["sql"] =  $db->db_quote("SELECT FB.food_brand_id, FB.position, FB.status, FB.pet_type_id, FBL.value, PTL.value AS pet_type_value FROM ".$foodbrands_controller->table." FB, ".$foodbrands_controller->table_langs." FBL, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$foodbrands_controller->table." FB, ".$foodbrands_controller->table_langs." FBL, ".$pet_types_controller->table_langs." PTL WHERE 1 ".$search_terms);
	
	$foodbrands_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY PTL.value ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
	
	#Smarty assigns
	$smarty->assign("foodbrands_list", $foodbrands_list);
	$smarty->assign("url_delete", "?act=delete&");
	$smarty->assign("url_edit", "?act=edit&");
	$smarty->assign("url_add", "?act=add&");
	
	#Smarty TPL
	$smarty_tpl = "foodbrands_list.tpl";
	
}elseif ($_GET["act"] == "add"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Food brands");
	$smarty->assign("site_action", "Add");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[2]["title"] = "Food brands";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Add";
	$breadcrumb[3]["url"] = "#";
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("pet_types", $pet_types_controller->get_all());
	
	#Smarty TPL
	$smarty_tpl = "foodbrands_add.tpl";
	
}elseif ($_GET["act"] == "edit"){
	#Method: ADD
	#Section title
	$smarty->assign("site_module", "Food brands");
	$smarty->assign("site_action", "Edit");
	
	#Breadcrumb
	$breadcrumb[0]["title"] = "Home";
	$breadcrumb[0]["url"] = "home.php?";
	$breadcrumb[1]["title"] = "Pet types";
	$breadcrumb[1]["url"] = "pet-types.php?act=list&";
	$breadcrumb[2]["title"] = "Food brands";
	$breadcrumb[2]["url"] = $current_module."?act=list&";
	$breadcrumb[3]["title"] = "Edit";
	$breadcrumb[3]["url"] = "#";
	
	$foodbrand_data = $foodbrands_controller->get($_GET["food_brand_id"]);
	
	#Smarty assigns
	$smarty->assign("cancel_url", $current_module."?act=list&");
	$smarty->assign("foodbrand_data", $foodbrand_data["brand"]);
	$smarty->assign("foodbrand_lang", $foodbrand_data["lang"]);
	
	#Smarty TPL
	$smarty_tpl = "foodbrands_edit.tpl";
	
}elseif ($_GET["act"] == "delete"){
	#Method: DELETE
	$foodbrands_controller->delete($_GET["food_brand_id"]);
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
