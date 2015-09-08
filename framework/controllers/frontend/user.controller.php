<?php

	class user {

		var $user_table = "?:users";
		var $pet_table = "?:pets";
		var $vet_table = "?:vets";
		var $mer_table = "?:merchants";
		var $vet_pet_table = "?:vet_pet_types";
		var $vet_images = "?:vet_images";
		var $foundations = "?:foundations";
		var $foundation_image = "?:foundation_images";

		function register($user , $file , $type){
			global $notifications, $db;

			if ($this->exists("email", $user["email"])){
				// $notifications->set("<b>Error:</b> The email ".$user["email"]." already exists.", "error", true);
				return false;
			}

			$user_arr = array(
				'type' => $type,
				'email' => $user['reg_form']["email"],
				'password' => md5($user['reg_form']["password"]),
				'first_name' => $user['reg_form']["first_name"],
				'last_name' => $user['reg_form']["last_name"],
				'status' => 'A',
				'timestamp' => time(),
				'timestamp_login' => 0,
				'activation_hash' => '',
				'address' => $user['reg_form']["address"],
				'lat' => $user['reg_form']["lat"],
				'lon' => $user['reg_form']["lon"],
				'city' => $user['reg_form']["city"],
				'phone' => $user['reg_form']["phone"],
				'country' => $user['reg_form']["country"],
				'state' => $user['reg_form']["state"]
				);

			$id = $db->db_query("INSERT INTO ".$this->user_table." ?e ",$user_arr);


			if($type == 'B'){
				$this->petregister($user['petreg_form'] , $file , $id);
			}else if($type == 'M'){
				$this->mercregister($user['merc_form'] , $file , $id);
			}else if($type == 'V'){
			    $this->vetregister($user['vetreg_form'] , $file , $id);
			}else if($type == 'P'){
				$this->foundationregister($user['shelter_form'] , $file , $id);
			}else{
				return false;
			}

			return [
				"user" => $this->get_user($id)[0],
				"pet" => $this->get_pet($id)[0]
			];
			
		}
		function foundationregister($info , $file , $id){
			global $notifications, $db , $filesystem , $images;
			$file['mercimage']['name'] = time().'_'.$file['mercimage']['name'];

			$arr = array(
				'user_id' => $id,
				'name' => $info["name"],
				'contact_number' => $info['contact_number'],
				'website' => $info['website'],
				'work_schedules' => $info['work_schedules'],
				'about' => $info['about'],
				'mission' => $info['mission'],
				'image' => $file['mercimage']['name'],
				'date' => time(),
				'status' => 'A'
				);
			$id = $db->db_query("INSERT INTO ".$this->foundations." ?e ",$arr);


			if($id){
				$upload_result = $filesystem->fn_upload($file['mercimage']);
				$images->fn_update_image($upload_result, $id, 'foundations');

				/* START MULTIPLE IMAGE UPLOAD */
				$fileArray = $filesystem->fn_arrange_array($file['storeimage']);

				foreach($fileArray as $image){
					$image['name'] = time().'_'.$image['name'];
					$arr = array(
						'foundation_id' => $id,
						'image' => $image['name'],
						'date' => time()
					);

					$sql = $db->db_query("INSERT INTO ".$this->foundation_image." ?e" , $arr);

					$upload_result = $filesystem->fn_upload($image);
					$images->fn_update_image($upload_result, $id, 'foundation_image');
				}
				
				/* END MULTIPLE IMAGE UPLOAD */
				return true;
			}

			return false;
		}
		function petregister($pet , $file , $id){
			global $notifications, $db , $filesystem,$images;
			$file['petimage']['name'] = time().'_'.$file['petimage']['name'];
			$pet_arr = array(
				'user_id' => $id,
				'name' => $pet["name"],
				'howtocall' => $pet["howtocall"],
				'gender' => $pet["gender"],
				'pet_type_id' => $pet["pet_type_id"],
				'breed_id' => $pet["breed_id"],
				'food_id' => $pet["food_id"],
				'food_brand_id' => $pet["food_brand_id"],
				'food_style_id' => $pet["food_style_id"],
				'height' => $pet["height"],
				'weight' => $pet["weight"],
				'feeding_time' => $pet["feeding_time"],
				'birthdate' => time($pet["birthdate"]),
				'image' => $file['petimage']['name'],
				);
			$check = $db->db_query("INSERT INTO ".$this->pet_table." ?e ",$pet_arr);

			if($check){
				$upload_result = $filesystem->fn_upload($file['petimage']);
				$images->fn_update_image($upload_result, $check, 'pet');
				return true;
			}
			return false;
		}

		function vetregister($vet , $file , $id){
			global $notifications, $db , $filesystem, $images;

			$file['logo']['name'] = time().'_'.$file['logo']['name'];

			$vet_arr = array(
				'user_id' => $id, 
				'specialization_id' => $vet["specialization_id"],
				'contact_number' => $vet["contact_number"],
				'work_schedules' => $vet["work_schedules"],
				'vet_association' => $vet["vet_association"],
				'licenses' => $vet["licenses"],
				'notes' => $vet["notes"],
				'image' => $file['logo']['name'],
				'date' => time(),
				'status' => 'A'
				);

			$new_vet_id = $db->db_query("INSERT INTO ".$this->vet_table." ?e ",$vet_arr);

			if($new_vet_id){

				foreach ($vet["pet_type_id"] as $pet_type_id)
				{
					$pet_type_arr = array(
						'vet_id' => $new_vet_id,
						'pet_type_id' => $pet_type_id,
					);

					$db->db_query("INSERT INTO ".$this->vet_pet_table." ?e ", $pet_type_arr);
				};
				// vet upload image
				
				$upload_result = $filesystem->fn_upload($file['logo']);
				$images->fn_update_image($upload_result, $new_vet_id, 'vet');


				/* START MULTIPLE IMAGE UPLOAD */
				$fileArray = $filesystem->fn_arrange_array($file['vetimage']);

				foreach($fileArray as $image){
					$image['name'] = time().'_'.$image['name'];
					$arr = array(
						'vet_id' => $new_vet_id,
						'image' => $image['name'],
						'date' => time()
					);

					$db->db_query("INSERT INTO ".$this->vet_images." ?e" , $arr);

					$upload_result = $filesystem->fn_upload($image);
					$images->fn_update_image($upload_result, $new_vet_id , 'vet_images');
				}
				
				/* END MULTIPLE IMAGE UPLOAD */
				return true;
			}
			return false;

		}

		function mercregister($mer , $file , $id){
			global $notifications, $db , $filesystem, $images;
			$file['mercimage']['name'] = time().'_'.$file['mercimage']['name'];


			$merc_arr = array(
				'user_id' => $id,
				'store_name' => $mer["store_name"],
				'contact_number' => $mer["contact_number"],
				'website' => $mer["website"],
				'work_schedules' => $mer["work_schedules"],
				'image' => $file['mercimage']['name'],
				'date' => time(),
				'address_id' => 0,
				'status' => 'A'
			);

			$new_mer_id = $db->db_query("INSERT INTO ".$this->mer_table." ?e ",$merc_arr);

			if($new_mer_id){
				// Merchant upload image
				$upload_result = $filesystem->fn_upload($file['mercimage']);
				$images->fn_update_image($upload_result, $new_mer_id, 'mer');
				return true;
			}
			return false;
		}

		function exists($key, $value, $exclude_id=0){
			global $db;

			if ($exclude_id){
				$user_data = $db->db_get_row("SELECT user_id FROM ".$this->user_table." WHERE ".$key." = ?s AND user_id != ?i LIMIT 1", $value, $exclude_id);
			}else{
				$user_data = $db->db_get_row("SELECT user_id FROM ".$this->user_table." WHERE ".$key." = ?s LIMIT 1", $value);
			}
			
			if (!empty($user_data["user_id"])){
				return true;
			}
			
			return false;
		}

		function get_pet($id){
			global $db;

			return  $db->db_get_array('
				SELECT name , pet_id, image 
				FROM '.$this->pet_table.'
				WHERE user_id = ?i ORDER BY pet_id ASC LIMIT 1' , $id);
		}

		function get_user($id){
			global $db;

			return $db->db_get_array('
				SELECT country , status , email , first_name , last_name , type , user_id 
				FROM '.$this->user_table.' 
				WHERE user_id = ?s' , $id);
		}

		function go_login($data){
			global $images;

			$_SESSION['user_data']['user_id'] = $data['user']['user_id'];
			$_SESSION['user_data']['user_type'] = $data['user']['type'];
			$_SESSION['user_data']['country'] = $data['user']['country'];
			$_SESSION['user_data']['user_name'] = $data['user']['first_name'].' '.$data['user']['last_name'];

			
			if($data['user']['type'] == 'B'){
				$_SESSION['user_data']['pet_data']['pet_id'] = $data['pet']['pet_id'];
				$_SESSION['user_data']['pet_data']['pet_name'] = $data['pet']['name'];
				$_SESSION['user_data']['pet_data']['pet_image'] = $images->fn_get_image($data['pet']['pet_id'], 'pet', $data['pet']['image']);
			}

			return true;
		}
	}
?>