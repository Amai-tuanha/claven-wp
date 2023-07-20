<?php
use Carbon\Carbon;
// add_action('page_controlpanel_dashboard__send_form' , 'page_controlpanel_dashboard__send_form');

// function page_controlpanel_dashboard__send_form() {
  // ---------- 変数 ----------
  global 
    $post,
    $wpdb,
    $clane_email1,
    $clane_email2,
    $amount_int,
    $tax,
    $rand_number,
    $order_id,
    $user_subscribers_array;
  
  $subscriber_count = count($user_subscribers_array);
  // ---------- 変数ファイル ----------
  include get_theme_file_path() . "/App/Dashboard/model/memberList/memberList-variables.php";
  
  if (isset($_POST['seminarListPageTrigger'])) {
    $result = Carbon::now()->format("Y-m-d");
  
    foreach ($_POST as $_postKey => $_postValue) {
      $_post_pieces_array = explode("_", $_postKey);
      // $_post_slug = $_post_pieces_array[0];
      $_post_userID = $_post_pieces_array[1];
  
      $user_seminar_attendance = get_user_meta($_post_userID, $_postValue, true);
      foreach ($user_subscribers_array as $user) {
        $user_id = $user->id;
        // ---------- 値がなかったら ----------
        if (
          $user_id == $_post_userID &&
          !$user_seminar_attendance
        ) {
          update_user_meta($user_id, $_postValue, $result);
        }
  
        // ---------- 値があったら ----------
        elseif (
          $user_id == $_post_userID
          && $user_seminar_attendance
        ) {
          update_user_meta($user_id, $_postValue, "");
        }
      }
    }
  }
// }


