<?php
  class profile {
    function show_about($pet_id) {
        global $db;
        $profile = $db->db_get_row("SELECT * FROM ?:pets WHERE pet_id = ?i ", $pet_id);
        return $profile;
    }

    function add_friend($pid2) {
        $id1 = $_SESSION['user_data']['pet_data']['pet_id'];
        $pid2 = htmlspecialchars($pid);
        $date = strtotime(date('Y-m-d H:i:s'));
        $query = "SELECT * FROM ?:friends WHERE (pet_id1 = ?i and pet_id2 = ?i) ";
        $parsed = $db->db_quote($query, $pid1, $pid2);
        $result = $db->db_get_array($parsed);
        $rows = $db->db_get_found_rows();
        if ($rows > 0) {
          if($result[0]['status'] == 'N') {
            $arr = array("code"=>2,"msg"=>"You already sent this pet a friend request.");
          } else {
            $arr = array("code"=>3,"msg"=>"You are already friends with this pet.");
          }
          echo json_encode($arr);
        } else {
          $query = "INSERT INTO ?:friends ?e ";
          $param = array("pet_id1" => $pid1, "pet_id2" => $pid2, "date" => $date, "status" => "N");
          $parsed = $db->db_quote($query, $param);
          $rel_id = $db->db_query($parsed);
          if($rel_id > 0) {
            $arr = array("code"=>1,"msg"=>"Friend request has been sent.");
          } else {
            $arr = array("code"=>0,"msg"=>"An error was encountered while trying to send friend request.");
          }
          echo json_encode($arr);
        }
    }

    function show_friends($pet_id) {
        global $db;
        $images = new images();
        $friends = $db->db_get_array("SELECT p.pet_id, p.name, p.lastname, p.image 
                                    FROM ?:friends f
                                        INNER JOIN ?:pets p ON f.pet_id2 = p.pet_id
                                    WHERE f.pet_id1 = ?i and f.status = 'A'", $pet_id);
        foreach ($friends as $key=>$friend) {
          $friends[$key]['image'] = $images->fn_get_image($friend['pet_id'], 'pet', $friend['image']);
          if(!$friend['image']) {
            $friends[$key]['thumb'] = _IMAGES_URL_.'default-pic.png';
          } else {
            $friends[$key]['thumb'] = $images->fn_generate_thumbnail('pet', $friends[$key]['image']['image_path'], 200, 200, true);
          }
        }
        return $friends;
    }

    function edit_profile() {
	/* TODO: finish this */
        global $db;
        $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];
        $profile = $db->db_get_row("SELECT * FROM ?:pets WHERE pet_id = ?i ", $pet_id);
        return $profile;
    }

    function update_profile() {
	/* TODO: finish this */
        global $db;
        $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];
        //$result = $db->db_get_row("UPDATE ?:pets SET ... WHERE pet_id = ?i ", $pet_id);
        return $result;
    }

    function switch_profile($pet_id) {
	/* TODO: finish this */
        global $db;
        $profile = $db->db_get_row("SELECT * FROM ?:pets WHERE pet_id = ?i ", $pet_id);
        return $profile;
    }

    function show_profile($pet_id) {
        global $db;
        $profile = $db->db_get_row("SELECT * FROM ?:pets WHERE pet_id = ?i ", $pet_id);
        return $profile;
    }

    function show_posts($pet_id) {
        global $db;
        $images = new images();
        $friendly = new friendly();
        $posts = $db->db_get_array("SELECT u.name,u.lastname,u.image AS thumb,p.post_id,p.pet_id,p.date,p.text,p.image,
                                    CASE WHEN IFNULL(ppl.pet_id,0) = 0 THEN 'Like' ELSE 'Unlike' END liked FROM `rck_pets` u
                                    INNER JOIN `rck_posts` p ON u.pet_id = p.pet_id AND p.status = ?s
                                    LEFT JOIN rck_pet_post_likes ppl ON ppl.post_id = p.post_id AND ppl.pet_id = p.pet_id
                                    WHERE p.pet_id = ?i ORDER BY post_id DESC", "A", $pet_id);

        foreach ($posts as $key=>$post) {
          $posts[$key]['image'] = $images->fn_get_image($post['post_id'], 'post', $post['image']);
          $thumb = $images->fn_get_image($post['pet_id'], 'pet', $post['thumb']);
          if(!$thumb) {
            $posts[$key]['thumb'] = _IMAGE_URL_.'default-pic.png';
          } else {
            $posts[$key]['thumb'] = $images->fn_generate_thumbnail('pet', $thumb['image_path'], 48, 48, true);
          }

          if($post['pet_id'] == $pet_id) {
            $posts[$key]['button'] = '<div><a href="javascript:void(0);" class="delete-post" id="delete-post-'.$post['post_id'].'" title="Delete this Post"><span>Delete</span></a></div>';
          } else {
            $posts[$key]['button'] = '';
          }

          $post_id = $post['post_id'];
          $tags = $db->db_get_array("select value from rck_opt_post_types_lang
                                     inner join rck_post_types on rck_opt_post_types_lang.type_id = rck_post_types.type_id
                                     where post_id = ?i and lang_code = ?s ", $post_id, _CLIENT_LANGUAGE_);
          $tmp = '';
          foreach($tags as $tag) {
            $tdesc = $tag['value'];
            $tmp .=  '<a href="'.$friendly->get_seourl_by_module("tags/$tdesc", true). '">'.$tdesc.'</a>, ';
          }
          $posts[$key]['tags'] = substr($tmp,0,strlen($tmp)-2);
        }

        return $posts;
    }
  }
?>