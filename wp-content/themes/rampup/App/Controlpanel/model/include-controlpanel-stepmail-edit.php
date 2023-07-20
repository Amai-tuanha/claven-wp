<?php
use Carbon\Carbon;
// add_action('page_controlpanel_stepmail_edit__send_form' , 'page_controlpanel_stepmail_edit__send_form');

// function page_controlpanel_stepmail_edit__send_form() {
// ---------- 送信された情報をユーザーメタで更新 ----------
$email_post_id = $_GET['email_post_id'];

  if (isset($_POST['posteditFormTrigger'])) {
    // ---------- 変数定義 ----------
    // $post_id = $post->ID;
    // $user_id = $_GET['user_id'];
    // $class_post = new Rampup_Post($post_id);
    // $class_user = new Rampup_User($user_id);
    // $now = new Carbon('now');
    // $now_formatted = $now->format('Y-m-d H:i:s');
    // ---------- ユーザーメタアップデート ----------
    $my_post = array(
      'ID'           => $_POST['post_id'],
      'post_content'   => $_POST['post_content'],
    );
    // データベースにある投稿を更新する
    wp_update_post($my_post);
    update_post_meta($_POST['post_id'], "email_subject", $_POST['post_subject']);


    // do_action('single_reservation_user_component_posts', $user_id);

    wp_safe_redirect(get_current_link());
    exit;
  }


// }

?>

<?php


/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
 ?>