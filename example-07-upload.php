<?php




if ($_GET["act"] == "upload"){

	#$upload_result = $filesystem->fn_upload($_FILES["file"],_SITE_DIR_.'images/sliders/');
	$file_ary = reArrayFiles($_FILES['file']);
	foreach($file_ary as $file){
		$filesystem->fn_upload($file,_SITE_DIR_.'images/testing/');
	}

}

#Smarty assigns
$smarty->assign("current_url", $friendly->get_seourl_by_module("example-07-upload")."/");
$smarty->assign("section_template", "example-07-upload.tpl");

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
