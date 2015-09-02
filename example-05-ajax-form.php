<?php

#Get AJAX request
if ($_GET["act"] == "login"){
	$data = array("post" => $_POST, "get" => $_GET);
	
	#Sample validation, this must be done with a database query_result
	$valid_user = "testuser";
	$valid_password = "testpassword";
	
	$response = array();
	
	if ($_POST["user"] == $valid_user && $_POST["password"] == $valid_password){
		$response["status"] = 1;
		$response["message"] = "Login success";
	}else{
		$response["status"] = 0;
		$response["message"] = "Your username or password is incorrect";
	}
	
	#Send JSON response
	echo json_encode($response);
	die();
}

#Smarty assigns
$smarty->assign("current_url", $friendly->get_current_url());
$smarty->assign("section_template", "example-05-ajax-form.tpl");

#Smarty display.
$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");

?>
