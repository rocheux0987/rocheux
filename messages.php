<?php
  $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];
  $controllers_loader->load("messages");

  switch ($_GET['act']) {
    case 'read_message':
      $smarty->assign("message", $messages_controller->read_thread($_GET['sender_id'], $pet_id));
      $smarty->display(_TPL_FRONTEND_DIR_."message.tpl");
      die();
      break;

    case 'send_message':
      echo $messages_controller->send_message($_GET['recipient_id'], $_GET['message']);
      die();
      break;

    case 'delete_messages':
      echo $messages_controller->delete_messages($_GET['items']);
      die();
      break;

    case 'delete_thread':
      echo $messages_controller->delete_thread($_GET['sender_id']);
      die();
      break;

    default:
      $smarty->assign("messages", $messages_controller->list_messages());
  }

  $smarty->assign("section_title", "Messages");
  $smarty->assign("section_template", 'messages.tpl');

  #Smarty display.
  $smarty->display(_TPL_FRONTEND_DIR_."index.tpl");
?>