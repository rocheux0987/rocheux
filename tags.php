<?php
  $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];
  $keyword = $friendly->params[2];
  /* $posts = $db->db_get_array("SELECT t.post_id, t.type_id, p.text, p.image, p.date, u.pet_id, u.name, u.lastname,
                              CASE WHEN IFNULL(l.pet_id,0) = 0 THEN 'Like' ELSE 'Unlike' END liked
                              FROM rck_post_types t
                              INNER JOIN rck_posts p on t.post_id = p.post_id
                              INNER JOIN rck_pets u on p.pet_id = u.pet_id
                              LEFT JOIN rck_pet_post_likes l ON l.post_id = p.post_id AND l.pet_id = ?i
                              WHERE type_id = (SELECT type_id FROM `rck_opt_post_types_lang`
                                WHERE description = ?s AND lang_code = _CLIENT_LANGUAGE_)
                              AND p.status = ?s ", $pet_id, $keyword, "A"); */
  $posts = $db->db_get_array("SELECT t.post_id, t.type_id, p.text, p.image, p.date, u.pet_id, u.name, u.lastname, u.image as thumb,
                              CASE WHEN IFNULL(l.pet_id,0) = 0 THEN 'Like' ELSE 'Unlike' END liked
                              FROM rck_opt_post_types_lang ptl
                              INNER JOIN rck_post_types t ON ptl.type_id = t.type_id
                              INNER JOIN rck_posts p ON t.post_id = p.post_id
                              INNER JOIN rck_pets u ON p.pet_id = u.pet_id
                              LEFT JOIN rck_pet_post_likes l ON l.post_id = p.post_id AND l.pet_id = ?i
                              WHERE ptl.value = ?s AND ptl.lang_code = ?s
                              AND p.status = ?s order by p.post_id desc", $pet_id, $keyword,_CLIENT_LANGUAGE_, "A");

  foreach ($posts as $key=>$post) {
    $posts[$key]['image'] = $images->fn_get_image($post['post_id'], 'post', $post['image']);
    $thumb = $images->fn_get_image($post['pet_id'], 'pet', $post['thumb']);
    if(!$thumb) {
      $posts[$key]['thumb'] = _IMAGES_URL_.'default-pic.png';
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
      $tmp .=  '<a href="'. $friendly->get_seourl_by_module("tags/$tdesc", true). '">'.$tdesc.'</a>, ';
    }
    $posts[$key]['tags'] = substr($tmp,0,strlen($tmp)-2);
  }

  $smarty->assign("section_title", "Posts tagged '".$keyword."'");
  $smarty->assign("section_template", "tags.tpl");
  $smarty->assign("posts", $posts);
  $smarty->assign("misc", $misc);

  #Smarty display.
  $smarty->display(_TPL_FRONTEND_DIR_."index.tpl");
?>