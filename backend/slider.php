<?php
require("../framework/config/backend.config.php");

#Administrators validation
$administrators_controller->session_check();

#Check administrator privileges
if (!$administrators_controller->module_check(3)){
	$notifications->set("You dont have access to SLIDER module.", "error", true);
	$common->redirect("home.php?");
	die();
}

#Controllers
$controllers_loader->load("slider");

#Module name
$current_module = basename(__FILE__);

#Section title
$smarty->assign("site_module", "Slide");
$smarty->assign("site_action", "Edit");

#Breadcrumb
$breadcrumb[0]["title"] = "Home";
$breadcrumb[0]["url"] = "#";
$breadcrumb[1]["title"] = "Administration";
$breadcrumb[1]["url"] = "#";
$breadcrumb[2]["title"] = "Slider";
$breadcrumb[2]["url"] = "?act=list&";

$seo = $db->db_get_array('SELECT DISTINCT name FROM ?:seo_rules');

#Notifications.
$notifications->assign();


if($_POST){
	switch ($_GET['act']) {
		case 'save':
			$data = $_POST;
			$file = $_FILES['photo'];
			$slider_controller->add($data , $file);
			$common->redirect($current_module."?act=list");
		break;
		case 'edit_image':
			$slide_data["lang"] = $_POST["form_data"];
			$slide_data["type"] = $_POST["extra_form_data"];
			$slide_id = $slide_data["type"]["slide_id"];
			$files = $_FILES['photo'];
			#$upload_result = $filesystem->fn_upload($_FILES["photo"],_SITE_DIR_.'images/sliders/');
	
			$slider_controller->edit_image($slide_data, $slide_id , $files);
			$common->redirect($current_module."?act=list");
		break;
		default:
			# code...
			break;
	}
}
switch ($_GET['act']) {
	case 'add':
		$smarty->assign("section_content", "slider_add.tpl");
		$breadcrumb[3]["title"] = "Add";
		$breadcrumb[3]["url"] = "#";
		$smarty->assign("seo_link", $seo);
		break;
	case 'update':
		$slider = $_POST['slider'];

		$slider_controller->edit($slider);
		$common->redirect($current_module."?act=list");
		break;
	case 'edit':
		$id = $_GET['slider_id'];
		$smarty->assign("section_content", "slider_edit.tpl");
		$breadcrumb[3]["title"] = "Edit";
		$breadcrumb[3]["url"] = "#";

		$slider_list = $slider_controller->get($id);
		
		
		#Smarty assigns
		$smarty->assign("slider_type", $slider_list["type"]);
		$smarty->assign("slider_lang", $slider_list["lang"]);
		$smarty->assign("seo_link", $seo);
	
		break;
	case 'list':

		#Section title
		$smarty->assign("site_module", "Sliders");
		$smarty->assign("site_action", "List");
		$smarty->assign("section_content", "slider_list.tpl");
		$breadcrumb[3]["title"] = "List";
		$breadcrumb[3]["url"] = "#";

		#Extra params (for Pager)
		$extra_params = "act=list";	

		$pager_config["current_url"] = _BACKEND_URL_ . $current_module."?";
		$pager_config["sql"] =  $db->db_quote("SELECT slide_id , name , link , position , status FROM ".$slider_controller->table);
		$pager_config["sql_count"] =  $db->db_quote("SELECT COUNT(1) FROM ".$slider_controller->table);

		$slider_list = $pager->get($pager_config["sql"] , $pager_config["sql_count"], 30, "ORDER BY position ASC", $pager_config["current_url"], $_GET["page"], $extra_params);
		
		#Smarty assigns
		$smarty->assign("slider_list", $slider_list);
		$smarty->assign("url_delete", "?act=delete&");
		$smarty->assign("url_edit", "?act=edit&");
		$smarty->assign("url_add", "?act=add&");


		break;
	case 'delete':
		$slider_controller->delete($_GET['slider_id']);
		$common->redirect($current_module."?act=list");
		break;
	
	case 'search':
		echo 'search';
		die();
		break;
	default:
		# code...
		break;
}

$smarty->assign("breadcrumb", $breadcrumb);

#print_r($slider_list);

#Smarty TPLs
$smarty->assign("cancel_url", $current_module."?act=list&");
$smarty->display(_TPL_BACKEND_DIR_."layout.tpl");
?>
