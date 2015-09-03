<?php
#Get AJAX request
if ($_GET["act"] == "login"){
	$data = array("post" => $_POST, "get" => $_GET);
	
	$sql = $db->db_get_array('SELECT country , status , email , first_name , last_name , type , user_id FROM ?:users WHERE email = ?s AND password = MD5(?s) AND status = ?s' , $_POST['user'] , $_POST['pass'] , 'A');

	$response = array();
	if ($sql){

		$response["status"] = 1;

		$_SESSION['user_data']['user_id'] = $sql[0]['user_id'];
		$_SESSION['user_data']['user_type'] = $sql[0]['type'];
		$_SESSION['user_data']['country'] = $sql[0]['country'];
		$_SESSION['user_data']['user_name'] = $sql[0]['first_name'].' '.$sql[0]['last_name'];
		
		if($sql[0]['type'] == 'B'){
			$pet = $db->db_get_array('SELECT name , pet_id FROM ?:pets WHERE user_id = ?i ORDER BY pet_id ASC LIMIT 1' , $sql[0]['user_id']);
			$_SESSION['pet_data'] ['pet_id'] = $pet[0]['pet_id'];
			$_SESSION['pet_data'] ['pet_name'] = $pet[0]['name'];
		}

	}else{
		$response["status"] = 0;
		$response["message"] = "Your username or password is incorrect";
	}
	
	#Send JSON response
	echo json_encode($response);
}


if($_GET['act'] == 'logout'){
	session_destroy();
}


if($_GET['act'] == 'check_email'){
	$controllers_loader->load("user");
	echo $user_controller->exists("email" , $_POST['email']);
}