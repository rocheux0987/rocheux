<?php
  class misc {
    function fn_time_diff($date1) {
      $date2 = strtotime(date('Y-m-d H:i:s'));
      $diff = $date2 - $date1;

      if ($diff < 60) {
        return $diff . " seconds ago";
      }

      if (($diff >= 60) && ($diff < 3600)) {
        if(round($diff / 60) < 2)
        {
          return round($diff / 60) . " minute ago";
        } else {
          return round($diff / 60) . " minutes ago";
        }
      }

      if (($diff >= 3600) && ($diff < 86400)) {
        if(round($diff / 3600) < 2)
        {
          return round($diff / 3600) . " hour ago";
        } else {
          return round($diff / 3600) . " hours ago";
        }
      }

      if (($diff >= 86400) && ($diff < 604800)) {
        if(round($diff / 86400) < 2)
        {
          return round($diff / 86400) . " day ago";
        } else {
          return round($diff / 86400) . " days ago";
        }
      }

      if ($diff >= 604800) {
        if(round($diff / 604800) < 2)
        {
          return round($diff / 604800) . " week ago";
        } else {
          return round($diff / 604800) . " weeks ago";
        }
      }
    }

    function fn_check_friendship($pet_id2) {
      global $db;
      $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];
      if($pet_id2 != $pet_id) {
        $query = "SELECT * FROM ?:friends WHERE (pet_id1 = ?i and pet_id2 = ?i) ";
        $parsed = $db->db_quote($query, $pet_id, $pet_id2);
        $result = $db->db_get_array($parsed);
        $rows = $db->db_get_found_rows();
        if ($rows > 0) {
          if($result[0]['status'] == 'N') {
            $arr = array("code"=>2,"msg"=>"Already Requested");
          } else {
            $arr = array("code"=>3,"msg"=>"Already Friends");
          }
        } else {
          $arr = array("code"=>1,"msg"=>"Add Friend");
        }
      } else {
        $arr = array("code"=>0,"msg"=>"");
      }
      return json_encode($arr);
    }

  }
?>