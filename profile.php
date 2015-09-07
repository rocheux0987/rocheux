<?php
  $controllers_loader->load("profile");

  switch($friendly->params[3]) {
    case 'about':
      $pet_id = $friendly->params[2];
      $rel = json_decode($misc->fn_check_friendship($pet_id));

      $profile = $profile_controller->show_about($pet_id);
      $full_name = $profile['name'].' '.$profile['lastname'];

      $smarty->assign("profile", $profile);
      $smarty->assign("section_title", "About ".$full_name);
      $smarty->assign("section_template", 'profile.tpl');
      $smarty->assign("details", '<br /><p>'.$profile['about'].'</p>');
      $smarty->assign("rel_msg", $rel->msg);
      $smarty->assign("rel_code", $rel->code);
      break;

    case 'friends':
      $pet_id = $friendly->params[2];
      $rel = json_decode($misc->fn_check_friendship($pet_id));

      $profile = $profile_controller->show_profile($pet_id);
      $friends = $profile_controller->show_friends($pet_id);
      $full_name = $profile['name'].' '.$profile['lastname'];

      $smarty->assign("profile", $profile);
      $smarty->assign("friends", $friends);

      $smarty->assign("section_title", "Friends of ".$full_name);
      $smarty->assign("section_template", 'friends.tpl');
      $smarty->assign("rel_msg", $rel->msg);
      $smarty->assign("rel_code", $rel->code);
      break;

    case 'edit':
      $smarty->assign("profile", $profile_controller->edit_profile());

      $smarty->assign("section_title", "Edit Profile");
      $smarty->assign("section_template", 'profile.tpl');
      break;

    case 'switch':
      $pet_id = $friendly->params[2];

      $smarty->assign("profile", $profile_controller->switch_profile($pet_id));

      $smarty->assign("section_title", "Switch Profile");
      $smarty->assign("section_template", 'profile.tpl');
      break;

    default:
      $pet_id = $friendly->params[2];
      $rel = json_decode($misc->fn_check_friendship($pet_id));

      $smarty->assign("profile", $profile_controller->show_profile($pet_id));
      $smarty->assign("posts", $profile_controller->show_posts($pet_id));

      $smarty->assign("section_title", "User Profile");
      $smarty->assign("section_template", 'profile.tpl');
      $smarty->assign("rel_msg", $rel->msg);
      $smarty->assign("rel_code", $rel->code);
      $smarty->assign("misc", $misc);
  }

  #Smarty display.
  $smarty->display(_TPL_FRONTEND_DIR_."index.tpl");
?>