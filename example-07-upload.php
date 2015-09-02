<?php

if ($_GET["act"] == "upload"){
	$upload_result = $filesystem->fn_upload($_FILES["file"],_SITE_DIR_.'images/sliders/');
	
	switch($upload_result["error"]){
		case 1:
			#Empty variable
			$smarty->assign("upload_result_msg", "Error 01: Variable empty");
			$smarty->assign("upload_result_type", "error");
		break;
		
		case 2:
			#Forbidden file extension
			$smarty->assign("upload_result_msg", "Error 02: Forbidden file extension");
			$smarty->assign("upload_result_type", "error");
		break;
		
		case 3:
			#Max file size reached
			$smarty->assign("upload_result_msg", "Error 03: Max file size reached");
			$smarty->assign("upload_result_type", "error");
		break;
		
		case 4:
			#Internal PHP upload error
			$smarty->assign("upload_result_msg", "Error 04: Internal upload error");
			$smarty->assign("upload_result_type", "error");
		break;
		
		case 0:
			#Upload OK
			$smarty->assign("upload_result_msg", "Image uploaded successfully: <a target='_blank' href='".$upload_result["link"]."'>".$upload_result["file"]."</a>");
			$smarty->assign("upload_result_type", "success");
		break;
	}

}

#Smarty assigns
$smarty->assign("current_url", $friendly->get_seourl_by_module("example-07-upload")."/");
$smarty->assign("section_template", "example-07-upload.tpl");

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
