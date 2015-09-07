<?php
  $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];
  $posts = $db->db_get_array("SELECT u.name,u.lastname,u.image as thumb,p.post_id,p.user_id,p.pet_id,p.date,p.text,p.image,
                              case when ifnull(ppl.pet_id,0) = 0 then 'Like' else 'Unlike' end liked FROM `rck_pets` u
                              INNER JOIN `rck_posts` p on u.pet_id = p.pet_id and p.status = ?s
                              LEFT JOIN rck_pet_post_likes ppl on ppl.post_id = p.post_id and ppl.pet_id = ?i
                              order by post_id desc", "A", $pet_id);

  foreach ($posts as $key=>$post) {
    $posts[$key]['image'] = $images->fn_get_image($post['post_id'], 'post', $post['image']);
    $thumb = $images->fn_get_image($post['pet_id'], 'pet', $post['thumb']);
    if(!$thumb) {
      $posts[$key]['thumb'] = _SITE_DOMAIN_HTTP_.'images/default-pic.png';
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

  //var_dump($_SESSION);
  //session_destroy();

  #Smarty assigns
  $smarty->assign("section_title", "Newsfeed");
  $smarty->assign("section_template", 'newsfeed.tpl');
  $smarty->assign("posts", $posts);
  $smarty->assign("pet_id", $pet_id);

  /* create object for class misc so we can use its functions on our template file */
  $misc = new misc();
  $smarty->assign("misc", $misc);

  #Smarty display.
  $smarty->display(_TPL_FRONTEND_DIR_."index.tpl");
?>