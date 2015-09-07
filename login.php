<?php
#Get AJAX request
if ($_GET["act"] == "login"){
	$data = array("post" => $_POST, "get" => $_GET);
	
	$sql = $db->db_get_row('SELECT country , status , email , first_name , last_name , type , user_id FROM ?:users WHERE email = ?s AND password = MD5(?s) AND status = ?s' , $_POST['user'] , $_POST['pass'] , 'A');

	$response = array();
	
	if ($sql){

		$response["status"] = 1;

		$_SESSION['user_data']['user_id'] = $sql['user_id'];
		$_SESSION['user_data']['user_type'] = $sql['type'];
		$_SESSION['user_data']['country'] = $sql['country'];
		
		if($sql['type'] == 'B'){
			$pet = $db->db_get_row('SELECT name, pet_id, image FROM ?:pets WHERE user_id = ?i ORDER BY pet_id ASC LIMIT 1' , $sql['user_id']);
			$_SESSION['user_data']['pet_data']['pet_id'] = $pet['pet_id'];
			$_SESSION['user_data']['pet_data']['pet_name'] = $pet['name'];
			$_SESSION['user_data']['pet_data']['pet_image'] = $images->fn_get_image($pet['pet_id'], 'pet', $pet['image']);
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