<?php

use Carbon\Carbon;


// ---------- GoogoleCalrendar ----------
require_once get_template_directory() . '/App/GoogleService/GoogleCalendar.php';

/*--------------------------------------------------
/* 投稿削除でカレンダーも削除する
/*------------------------------------------------*/
add_action('before_delete_post', function ($post_id) {
  $post_type = get_post_type($post_id);
  $post_status = get_post_status($post_id);
  // ovd($post_type);
  // ovd($post_status);

  if ($post_type === "reservation" && $post_status !== "noreserve" ) {
    $reservation_meet_id = get_post_meta($post_id, "reservation_meet_id", true);

    $client = new GoogleCalendar;

    
    if ($reservation_meet_id) {
      // ---------- カレンダー削除 ----------
      $client->deleteCalendar($reservation_meet_id);
    }


    // ---------- メタデータを全て削除 ----------
    $post_metas = get_post_meta($post_id);
    foreach ($post_metas as $key => $value) {
      delete_post_meta($post_id, $key);
    }
  }
});

/*--------------------------------------------------
/* ラジオボタンにしてカテゴリを一つしか選択できないようにする
/*------------------------------------------------*/
if (!function_exists('my_print_footer_scripts')) {
  function my_print_footer_scripts()
  {

    $post_type = get_post_type($_GET['post_id']);
    if (
      $post_type == "membership" ||
      $_GET['post_type'] == "membership"
    ) {

      echo '<script type="text/javascript">
             //<![CDATA[
             jQuery(document).ready(function($){
               $(".categorychecklist input[type=checkbox] , .cat-checklist input[type=checkbox]").each(function(){
                 $check = $(this);
                 var checked = $check.attr("checked") ? \' checked="checked"\' : \'\';
                 $(\'<input type="radio" id="\' + $check.attr("id")
                   + \'" name="\' + $check.attr("name") + \'"\'
               + checked
               + \' value="\' + $check.val()
               + \'"/>\'
                 ).insertBefore($check);
                 $check.remove();
               });
             });
             //]]>
             </script>';
    }
  }
}
add_action('admin_print_footer_scripts', 'my_print_footer_scripts', 21);


/*--------------------------------------------------
/* 面談日が過ぎた面談日程のステータスを実施済みにする
/*------------------------------------------------*/
if (
  // $_SERVER['SCRIPT_NAME'] == "/wp-admin/edit.php" &&
  // $_GET['post_type'] == 'reservation'
  is_page('/controlpanel-meeting-list/')
) {
  $post_array = get_posts([
    'post_type' => 'reservation',
    'posts_per_page' => -1,
    'post_status' => 'scheduled',
  ]);
  foreach ($post_array as $post) {
    $post_id = $post->ID;
    $post_status = $post->post_status;
    $class_post = new Rampup_Post($post_id);
    $now = new carbon("now");
    $reservation_date_carbon = new carbon($class_post->reservation_date);
    if (
      $now > $reservation_date_carbon
    ) {

      $post_reservation_array = array(
        'ID' => $post_id,
        'post_status' => 'done',
      );

      // データベースにある投稿を更新する
      wp_update_post($post_reservation_array);
    }
  }
}