<?php

use Carbon\Carbon;
// add_action('page_add_meeting_thanks__process_form' , 'page_add_meeting_thanks__process_form');

// function page_add_meeting_thanks__process_form() {
require_once get_template_directory() . '/App/GoogleService/GoogleCalendar.php';
require_once get_template_directory() . '/App/GoogleService/GoogleSheet.php';

session_start();
global $wpdb;
ovd($_SESSION['get_param']);
$calendar_action = $_SESSION['calendar_action'];
// $calendar_post_id = $_SESSION['calendar_post_id'];
// POSTされたトークンを取得
$post_token = isset($_SESSION["post_token"]) ? $_SESSION["post_token"] : "";
// セッション変数のトークンを取得
$session_token = isset($_SESSION["token"]) ? $_SESSION["token"] : "";
$post_id = $_SESSION['post_id'];
$user_id = $_SESSION['user_id'];
$emailCheck = $_SESSION['emailCheck'];
$class_post = new Rampup_Post($post_id);
$class_user = new Rampup_User($user_id);
$meetingTitle = get_post_field('post_title', $post_id);
$reservation_date = new Carbon($class_post->reservation_date);

// ---------- GoogoleCalrendar ----------
$client = new GoogleCalendar;
if($post_token != "" && $post_token == $session_token){


// ---------- カレンダーを追加する場合 ----------
if ($calendar_action == "add_calendar") {
    $meet_info = $client->addCalendar(
        $class_user->user_displayName,
        $class_user->user_email,
        $class_user->user_tel,
        $class_user->user_question,
        $reservation_date,
        $post_id
    );

    $reservation_meet_url = $meet_info[0];
    $reservation_meet_id = $meet_info[1];

    // ---------- 投稿に登録 ----------
    update_post_meta($post_id, "reservation_meet_url", $reservation_meet_url);
    update_post_meta($post_id, "reservation_meet_id", $reservation_meet_id);

    // ---------- meetが発行できなかったら投稿を削除 ----------
    if (!$reservation_meet_url) {
        wp_delete_post($post_id, true);
    }
}
// ---------- カレンダーを変更する場合 ----------
elseif ($calendar_action == "update_calendar") {
    // $post_id = $calendar_post_id;
    $meet_info = $client->updateCalendar(
        $class_post->reservation_meet_id,
        $class_user->user_displayName,
        $class_user->user_email,
        $class_user->user_tel,
        null,
        $reservation_date,
        $post_id
    );
}


$class_post = new Rampup_Post($post_id);
$class_user = new Rampup_User($user_id);
if ($emailCheck) {
    // ---------- 日程変更だったら ----------
    if ($calendar_action == 'update_calendar') {
        reset_reminder($post_id);
    }

    $emailPost_id = $class_user->user_get_email($calendar_action)['post_id'];

    my_PHPmailer_function(
        do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
        do_shortcode(get_post_field('post_content', $emailPost_id)),
        $class_user->user_email,
        $class_post->reservation_meet_url,
        null,
        'false'
    );

    $add_meeting_thanks_post_args = [
        'post_id' => $post_id,
        'user_id' => $user_id,
        'calendar_action' => $calendar_action,
    ];

    /*--------------------------------------------------
    /* アクションフック追加
    /*------------------------------------------------*/
    do_action('add_meeting_thanks_post', $add_meeting_thanks_post_args);
}
}
// // ---------- GAS ----------
// $sheet = new GoogleSheet();
// $sheet->addRecord(
//   $user_displayName,
//   $user_email,
//   $user_tel,
//   $user_question,
//   $user_meetingBeforeContract_date,
//   $email_subject,
//   $db_col_id,
//   $email_message,
//   $user_meetingBeforeContract_meet_url,
//   $user_meetingBeforeContract_formatted);
session_unset();
unset($post_token);




// ---------- 管理者がauthor.phpから日程追加・変更した時 ----------
$current_user_id = get_current_user_id();
if (
    $user_id !== $current_user_id &&
    current_user_can('administrator')
) {
    // wp_safe_redirect(get_author_posts_url($user_id));
    // exit;
}

// }