<?php
  $date = strtotime(date('Y-m-d H:i:s'));
  $pet_id = $_SESSION['user_data']['pet_data']['pet_id'];

  switch($_POST['act']) {
    case 'flag':
      $pid = htmlspecialchars($_POST['pid']);
      // check if pet has already liked this post
      $query = "select * from ?:pet_post_likes where post_id = ?i and pet_id = ?i ";
      $parsed = $db->db_quote($query, $pid, $pet_id);
      $bool = $db->db_get_row($parsed);
      if (!$bool) {
        // if pet has not liked the post, insert 'post_id', 'pet_id', 'date' to the pet_post_likes table - like the post
        $query = "INSERT INTO ?:pet_post_likes ?e ";
        $param = array("post_id" => $pid, "pet_id" => $pet_id, "date" => $date);
        $parsed = $db->db_quote($query, $param);
        $db->db_query($parsed);
        echo "Unlike";
      } else {
        // if pet has already liked the post, delete 'post_id', 'pet_id' from the pet_post_likes table - unlike the post
        $query = "DELETE FROM ?:pet_post_likes WHERE post_id = ?i and pet_id = ?i ";
        $parsed = $db->db_quote($query, $pid, $pet_id);
        $db->db_query($parsed);
        echo "Like";
      }
      break;

    case 'report_post':
      $pid = htmlspecialchars($_POST['pid']);
      // check if pet has already reported this post
      $query = "select * from ?:pet_post_dislikes where post_id = ?i and pet_id = ?i";
      $parsed = $db->db_quote($query, $pid, $pet_id);
      $bool = $db->db_get_row($parsed);
      if(!$bool) {
        // if pet has not reported the post, insert 'post_id', 'pet_id', 'date' to the pet_post_dislikes table
        $query = "INSERT INTO ?:pet_post_dislikes ?e ";
        $param = array("post_id" => $pid, "pet_id" => $pet_id, "date" => $date);
        $parsed = $db->db_quote($query, $param);
        $db->db_query($parsed);
        echo "Post has been reported.";
      } else {
        echo "You have already reported this post.";
      }
      break;

    case 'report_comment':
      $cid = htmlspecialchars($_POST['cid']);
      // check if pet has already reported this comment
      $query = "SELECT * FROM ?:post_comments_dislikes WHERE comment_id = ?i and pet_id = ?i ";
      $parsed = $db->db_quote($query, $cid, $pet_id);
      $bool = $db->db_get_row($parsed);
      if(!$bool) {
        // if pet has not reported the comment, insert 'comment_id', 'pet_id' to the pet_post_comments_dislikes table
        $query = "INSERT INTO ?:post_comments_dislikes ?e ";
        $param = array("comment_id" => $cid, "pet_id" => $pet_id);
        $parsed = $db->db_quote($query, $param);
        $db->db_query($parsed);
        echo "Comment has been reported.";
      } else {
        echo "You have already reported this comment.";
      }
      break;

    case 'remove':
      /* remove selected comment */
      $cid = htmlspecialchars($_POST['cid']);
      $query = "DELETE FROM ?:post_comments WHERE comment_id = ?i ";
      $parsed = $db->db_quote($query, $cid);
      $result = $db->db_query($parsed);

      /* remove all replies to deleted comment */
      $query = "DELETE FROM ?:post_comments WHERE comment_id_rel = ?i ";
      $parsed = $db->db_quote($query, $cid);
      $result = $db->db_query($parsed);

      /* remove all dislikes on deleted comment to free up now unused table rows */
      $query = "DELETE FROM ?:post_comments_dislikes WHERE comment_id = ?i ";
      $parsed = $db->db_quote($query, $cid);
      $result = $db->db_query($parsed);

      break;

    case 'delete':
      $pid = htmlspecialchars($_POST['pid']);
      /* delete selected post */
      $query = "DELETE FROM ?:posts WHERE post_id = ?i ";
      $parsed = $db->db_quote($query, $pid);
      $result = $db->db_query($parsed);

      /* delete all comments and replies to selected post */
      $query = "DELETE FROM ?:post_comments WHERE post_id = ?i ";
      $parsed = $db->db_quote($query, $pid);
      $result = $db->db_query($parsed);

      /* TODO - remove all comment dislikes on comments to the deleted post */

      break;

    case 'post':
      $pid = htmlspecialchars($_POST['pid']);
      /* post new comment */
      $comment = htmlspecialchars($_POST['comment']);

      $query = "INSERT INTO ?:post_comments ?e ";
      $param = array("post_id" => $pid, "user_type" => "B", "user_id" => $pet_id, "comment" => $comment, "date" => $date, "status" => "A");
      $parsed = $db->db_quote($query, $param);
      $com_id = $db->db_query($parsed);

      $query = "select pet_id, name, lastname, image from ?:pets where pet_id = ?i";
      $parsed = $db->db_quote($query, $pet_id);
      $p = $db->db_get_row($parsed);

      $image = $images->fn_get_image($pet_id, 'pet', $p['image']);
      $thumb = $images->fn_generate_thumbnail('pet', _SITE_URL_.$image['image_path'], 48, 48, true);

      echo '<li class="comment-container" id="comment-container-'.$com_id.'">
        <div class="col-lg-1">
          <a href="'.$friendly->get_seourl_by_module("profile/".$p['pet_id'], true).'"><img class="profile-pic" src="'.$thumb.'"/></a>
        </div>
        <div class="col-lg-11">
          <div class="row">
            <div class="col-lg-6 text-left nopad comment-author">
              <a href="'.$friendly->get_seourl_by_module("profile/".$p['pet_id'], true).'"><span>'.$p['name'].' '.$p['lastname'].'</span></a>
              </div>
              <div class="col-lg-6 text-right nopad comment-date">
                <h6><small>'.$misc->fn_time_diff($date).'</small></h6>
              </div>
            </div>
          <div class="row">
            <div class="comment-message">
              <p>'.$comment.'</p>
            </div>
          </div>
          <div class="row">
            <p class="buttons"><button class="button-remove" data-act="remove" data-comment-id="'.$com_id.'">Remove Comment</button></p>
          </div>
        </div>
        <br clear="all"/>
      </li>';
      break;

    case 'reply':
      $pid = htmlspecialchars($_POST['pid']);
      $cid = htmlspecialchars($_POST['cid']);
      $comment = htmlspecialchars($_POST['comment']);

      $query = "INSERT INTO ?:post_comments ?e ";
      $param = array("post_id" => $pid, "user_type" => "B", "user_id" => $pet_id, "comment" => $comment, "date" => $date, "status" => "A", "comment_id_rel" => $cid);
      $parsed = $db->db_quote($query, $param);
      $com_id = $db->db_query($parsed);

      $query = "select pet_id, name, lastname, image from ?:pets where pet_id = ?i";
      $parsed = $db->db_quote($query, $pet_id);
      $p = $db->db_get_row($parsed);

      $image = $images->fn_get_image($pet_id, 'pet', $p['image']);
      $thumb = $images->fn_generate_thumbnail('pet', _SITE_DOMAIN_HTTP_.$image['image_path'], 48, 48, true);

      echo '<li class="comment-container" id="comment-container-'.$com_id.'">
        <div class="col-lg-1">
          <a href="'._SITE_DOMAIN_HTTP_.$lang.'/profile/'.$pet_id.'"><img class="profile-pic" src="'.$thumb.'"/></a>
        </div>
        <div class="col-lg-11">
          <div class="row">
            <div class="col-lg-6 text-left nopad comment-author">
              <a href="'._SITE_DOMAIN_HTTP_.$lang.'/profile/'.$pet_id.'"><span>'.$p['name'].' '.$p['lastname'].'</span></a>
            </div>
            <div class="col-lg-6 text-right nopad comment-date">
              <h6><small>'.$misc->fn_time_diff($date).'</small></h6>
            </div>
          </div>
          <div class="row">
            <div class="comment-message">
              <p>'.$comment.'</p>
            </div>
          </div>
          <div class="row">
            <p class="buttons"><button class="button-remove" data-act="remove" data-comment-id="'.$com_id.'">Remove Comment</button></p>
          </div>
        </div>
        <br clear="all"/>
      </li>';
      break;

    default:
      $pid = htmlspecialchars($_POST['pid']);
      $query = "select c.post_id, c.comment_id, c.comment, c.date, u.pet_id, u.name, u.lastname, u.image from ?:post_comments c inner join ?:pets u on c.user_id = u.pet_id where c.post_id = ?i and c.comment_id_rel = 0 order by comment_id desc ";
      $parsed = $db->db_quote($query, $pid);
      $comments = $db->db_get_array($parsed);
      foreach($comments as $c) {
        $image = $images->fn_get_image($c['pet_id'], 'pet', $c['image']);
        if (!$image) {
          $thumb = '/images/default-pic.png';
        } else {
          $thumb = $images->fn_generate_thumbnail('pet', _SITE_DOMAIN_HTTP_.$image['image_path'], 48, 48, true);
        }
        echo '<li class="comment-container" id="comment-container-' . $c['comment_id'] . '">
          <div class="col-lg-1">
            <a href="'.$friendly->get_seourl_by_module("profile", true).'/'.$c['pet_id'].'"><img class="profile-pic" src="'.$thumb.'" /></a>
          </div>
          <div class="col-lg-11">
            <div class="row">
              <div class="col-lg-6 text-left nopad comment-author">
                <a href="'.$friendly->get_seourl_by_module("profile", true).'/'.$c['pet_id'].'"><span>' . $c['name'] . ' ' . $c['lastname'] . '</span></a>
              </div>
              <div class="col-lg-6 text-right nopad comment-date">
                <h6><small>' . $misc->fn_time_diff($c['date']) . '</small></h6>
              </div>
            </div>
            <div class="row">
              <div class="comment-message">
                <p>'.$c['comment'].'</p>
              </div>
            </div>
            <div class="row">
              <p class="buttons"><button class="button-reply" id="button-reply-' . $c['comment_id'] . '">Respond to this Comment</button>';
              if ($pet_id != $c['pet_id']) {
                /* if pet who made this comment is not the same as the currently logged in pet, you have the option to report the comment */
                echo '&nbsp;&nbsp;&nbsp;<button class="button-report" id="button-report-' . $c['comment_id'] . '">Report Comment</button>';
              } else {
                /* if pet who made this comment is YOU, you have the option to remove it */
                echo '&nbsp;&nbsp;&nbsp;<button class="button-remove" data-act="remove" data-comment-id="'.$c['comment_id'].'">Remove Comment</button>';
              }
              echo '</p>
              <div class="comcom-reply" id="comcom-reply-' . $c['comment_id'] . '">
                <hr />
                <textarea name="comcom-field" class="comcom-field" id="comcom-field-' . $c['comment_id'] . '"></textarea>
                <p><button class="button-comreply" id="button-comreply-' . $c['comment_id'] . '-' . $c['post_id'] . '">Submit Reply</button></p>
                <hr />
              </div>
              <ul class="comcom_loading_area" id="comcom_loading_area-' . $c['comment_id'] . '">';
                /* this is for loading respective replies to each comment */
                $query = "select c.post_id, c.comment_id, c.comment, c.date, u.pet_id, u.name, u.lastname, u.image from ?:post_comments c inner join ?:pets u on c.user_id = u.pet_id where c.comment_id_rel = ?i order by comment_id desc ";
                $parsed = $db->db_quote($query, $c['comment_id']);
                $comcom = $db->db_get_array($parsed);
                if ($comcom) {
                  foreach ($comcom as $c2) {
                    $image = $images->fn_get_image($c2['pet_id'], 'pet', $c2['image']);
                    if (!$image) {
                      $thumb = '/images/default-pic.png';
                    } else {
                      $thumb = $images->fn_generate_thumbnail('pet', _SITE_DOMAIN_HTTP_.$image['image_path'], 48, 48, true);
                    }
                    echo '<li class="comment-container" id="comment-container-' . $c2['comment_id'] . '">
                      <div class="col-lg-1">
                        <a href="'.$friendly->get_seourl_by_module("profile", true).'/'.$c2['pet_id'].'"><img class="profile-pic" src="' . $thumb . '"/></a>
                      </div>
                      <div class="col-lg-11">
                        <div class="row">
                          <div class="col-lg-6 text-left nopad comment-author">
                            <a href="'.$friendly->get_seourl_by_module("profile", true).'/'.$c2['pet_id'].'"><span>' . $c2['name'] . ' ' . $c2['lastname'] . '</span></a>
                          </div>
                          <div class="col-lg-6 text-right nopad comment-date">
                            <h6><small>'.$misc->fn_time_diff($c2['date']).'</small></h6>
                          </div>
                        </div>
                        <div class="row">
                          <div class="comment-message">
                            <p>'.$c2['comment'].'</p>
                          </div>
                        </div>
                        <div class="row">';
                          if ($pet_id != $c2['pet_id']) {
                            echo '<p class="buttons"><button class="button-report" id="button-report-' . $c2['comment_id'] . '">Report Comment</button></p>';
                          } else {
                            echo '<p class="buttons"><button class="button-remove" data-act="remove" data-comment-id="'.$c2['comment_id.'].'">Remove Comment</button></p>';
                          }
                        echo '</div>
                      </div>
                    </li>';
                  }
                }
              echo '</ul>
            </div>
          </div>
        </div>
        <br clear="all" />
        </li>';
      }
  }
?>