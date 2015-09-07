<?php
	$user_id = $_SESSION['user_data']['user_id'];
	$pet_id = $_SESSION['user_data']['pet_data']['pet_id'];

	if ($_GET["act"] == "upload") {
				
		$upload_result = $filesystem->fn_upload($_FILES["post_file"]);
	
		switch($upload_result["error"]) {
			case 1:
				#Empty variable
				$smarty->assign("upload_result_msg", "Error 01: Variable empty");
				$smarty->assign("upload_result_type", "error");

				$post_view = $_POST['post_priv'];
				$post_date = strtotime(date('Y-m-d H:i:s'));
				$post_desc = htmlspecialchars($_POST['post_desc']);
				$status = 'A';

				$param = array("user_id"=>$user_id, "pet_id"=>$pet_id, "view"=>$post_view, "date"=>$post_date, "text"=>$post_desc, "status"=>$status);
				$query = "INSERT INTO ?:posts ?e ";
				$parsed = $db->db_quote($query, $param);
				$pid = $db->db_query($parsed);

				foreach($_POST['post_tags'] as $tag) {
					$tmp = array("post_id"=>$pid,"type_id"=>$tag);
					$query = "INSERT INTO ?:post_types ?e ";
					$parsed = $db->db_quote($query, $tmp);
					$db->db_query($parsed);
				}

				$smarty->assign("upload_result_msg", "Post created successfully!");
				$smarty->assign("upload_result_type", "success");
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
				$post_view = $_POST['post_priv'];
				$post_date = strtotime(date('Y-m-d H:i:s'));
				$post_desc = htmlspecialchars($_POST['post_desc']);
				$post_file = $_FILES['post_file']['name'];
				$status = 'A';

				$param = array("user_id"=>$user_id, "pet_id"=>$pet_id, "view"=>$post_view, "date"=>$post_date, "text"=>$post_desc, "image"=>$post_file, "status"=>$status);
				$query = "INSERT INTO ?:posts ?e ";
				$parsed = $db->db_quote($query, $param);
				$pid = $db->db_query($parsed);

				foreach($_POST['post_tags'] as $tag) {
  					$tmp = array("post_id"=>$pid,"type_id"=>$tag);
					$query = "INSERT INTO ?:post_types ?e ";
  					$parsed = $db->db_quote($query, $tmp);
  					$db->db_query($parsed);
				}

				$images->fn_update_image($upload_result, $pid, 'post');

				$smarty->assign("upload_result_msg", "Image uploaded successfully: <a target='_blank' href='".$upload_result["link"]."'>".$upload_result["file"]."</a>");
				$smarty->assign("upload_result_type", "success");
				$smarty->assign("pet_id", $pet_id);
				break;
		}
	}

	$query = "SELECT * FROM ?:opt_post_types_lang WHERE lang_code = ?s";
	$param = _CLIENT_LANGUAGE_;
	$parsed = $db->db_quote($query, $param);
	$tags = $db->db_get_array($parsed);

	#Smarty assigns
	$smarty->assign("current_url", $friendly->get_seourl_by_module("upload")."/");
	$smarty->assign("tags", $tags);
	$smarty->assign("section_template", "upload.tpl");
	$smarty->assign("pet_id", $pet_id);

	#Smarty display.
	$smarty->display(_TPL_FRONTEND_DIR_."index.tpl");
?>