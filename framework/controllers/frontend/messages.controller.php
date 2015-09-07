<?php
  class messages {
    function delete_thread($sender_id) {
        global $notifications, $db;
        $recipient_id = $_SESSION['user_data']['pet_data']['pet_id'];
        $db->db_query("DELETE FROM ?:messages WHERE (sender_id = ?i AND recipient_id = ?i)
                       OR (sender_id = ?i AND recipient_id = ?i) ", $sender_id, $recipient_id, $recipient_id, $sender_id);
        //$notifications->set("Message thread was deleted successfully.", "success", true);
        return true;
    }

      function delete_messages($items) {
          global $db;
          $db->db_query("DELETE FROM ?:messages WHERE message_id IN ($items)", $items);
          return true;
      }

    function list_messages() {
        global $db;
        $images = new images();
        $recipient_id = $_SESSION['user_data']['pet_data']['pet_id'];
/*
        $query = "SELECT sender_id, u.name, u.lastname, u.image FROM ?:messages m INNER JOIN ?:pets u ON m.sender_id = u.pet_id
                  WHERE recipient_id = ?i GROUP BY sender_id ORDER BY date DESC";
        $parsed = $db->db_quote($query, $recipient_id);
        $senders = $db->db_get_array($parsed);
*/
        $query = "SELECT message_id, message, sender_id, date, status, u.name, u.lastname, u.image
                  FROM ?:messages m INNER JOIN rck_pets u ON m.sender_id = u.pet_id
                  WHERE message_id = (SELECT message_id FROM ?:messages m2
                    WHERE m2.sender_id = m.sender_id ORDER BY message_id DESC LIMIT 1)
                  AND sender_id <> $recipient_id
                  GROUP BY sender_id ORDER BY date DESC ";
        $parsed = $db->db_quote($query, $recipient_id);
        $messages = $db->db_get_array($parsed);

        foreach($messages as $key=>$msg) {
            $image = $images->fn_get_image($msg['sender_id'], 'pet', $msg['image']);
            if(!$image) {
                $messages[$key]['thumb'] = '/images/default-pic.png';
            } else {
                $messages[$key]['thumb'] = $images->fn_generate_thumbnail('pet', _SITE_DOMAIN_HTTP_ . $image['image_path'], 48, 48, true);
            }
        }
        return $messages;
    }

    function read_thread($sender_id, $recipient_id) {
        global $db;
        $images = new images();
        $query = "SELECT m.message_id, m.message, m.sender_id, m.date, m.status, u.name, u.lastname, u.image
                  FROM ?:messages m INNER JOIN ?:pets u ON m.sender_id = u.pet_id
                  WHERE (m.sender_id = ?i AND m.recipient_id = ?i)
                    OR (m.sender_id = ?i AND m.recipient_id = ?i) ";
        $parsed = $db->db_quote($query, $sender_id, $recipient_id, $recipient_id, $sender_id);
        $message = $db->db_get_array($parsed);
        foreach($message as $key=>$msg) {
            $image = $images->fn_get_image($msg['sender_id'], 'pet', $msg['image']);
            //echo 'm: '.$image['image_path'].'<br />';
            if(!$image) {
                $message[$key]['thumb'] = '/images/default-pic.png';
            } else {
                $message[$key]['thumb'] = $images->fn_generate_thumbnail('pet', _SITE_DOMAIN_HTTP_ . $image['image_path'], 32, 32, true);
            }
        }
        return $message;
    }

    function send_message($recipient_id,$message) {
        global $db;
        $images = new images();
        $sender_id = $_SESSION['user_data']['pet_data']['pet_id'];
        $recipient_id = htmlspecialchars($recipient_id);
        $message = htmlspecialchars($message);
        $date = strtotime(date('Y-m-d H:i:s'));
        $message_date = date('F j Y, H:i:sa');

        $query = "SELECT pet_id, name, lastname, image FROM ?:pets WHERE pet_id = ?i ";
        $parsed = $db->db_quote($query, $sender_id);
        $pet = $db->db_get_array($parsed);
        $pet_name = $pet[0]['name'].' '.$pet[0]['lastname'];
        $image = $images->fn_get_image($pet[0]['pet_id'], 'pet', $pet[0]['image']);
        if(!$image) {
            $thumb = '/images/default-pic.png';
        } else {
            $thumb = $images->fn_generate_thumbnail('pet', _SITE_DOMAIN_HTTP_ . $image['image_path'], 32, 32, true);
        }

        $query = "INSERT INTO ?:messages ?e ";
        $param = array("sender_id" => $sender_id, "recipient_id" => $recipient_id, "message" => $message, "date" => $date, "status" => "N");
        $parsed = $db->db_quote($query, $param);
        $msg_id = $db->db_query($parsed);

        if ($msg_id) {
            $notify = 'Message sent successfully.';
        } else {
            $notify = 'Message sending failed.';
        }

        $data = array("message"=>'<li data-message-id="'.$msg_id.'"><div class="message-check"><input type="checkbox" data-message-id="'.$msg_id.'" value="'.$msg_id.'" /></div><div class="message-thumb"><img src="'.$thumb.'" alt="'.$pet_name.'" /></div><div class="message-body"><a href="'.$sender_id.'">'.$pet_name.'</a><div class="message-date">'.$message_date.'</div><p>'.$message.'</p></div></li>', "notify" => $notify);
        return json_encode($data);
    }
  }
?>