<?php

use Carbon\Carbon;
// add_action('page_add_meeting_form__send_form' , 'page_add_meeting_form__send_form');

// function page_add_meeting_form__send_form() {

require_once get_template_directory() . '/App/GoogleService/GoogleCalendar.php';


global
  $wpdb,
  $user_status_translation_array;
$error = [];

// ovd($_POST);

if (isset($_POST["addMeetingForm"])) {
  //バリデーション

  $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  // フォームの送信時にエラーをチェックする
  if ('' === $post['user_lastName']) {
    $error['user_lastName'] = 'blank';
  }
  if ('' === $post['user_firstName']) {
    $error['user_firstName'] = 'blank';
  }
  if ('' === $post['user_email']) {
    $error['user_email'] = 'blank';
  } elseif (!filter_var($post['user_email'], FILTER_VALIDATE_EMAIL)) {
    $error['user_email'] = 'user_email';
  }
  if ('' === $post['user_tel']) {
    $error['user_tel'] = 'blank';
  }
  if ('' === $post['user_question']) {
    $error['user_question'] = 'blank';
  } elseif ('default' === $post['user_question']) {
    $error['user_question'] = 'blank';
  }
  if ('' === $post['user_meetingBeforeContract_date']) {
    $error['user_meetingBeforeContract_date'] = 'blank';
  }
  
  if(is_user_logged_in() && $post['user_email'] !== '' && $post['user_meetingBeforeContract_date'] !== ''){
    $error = [];
  }
  if (0 === count($error)) {

    /*--------------------------------------------------
      /* 変数
      /*------------------------------------------------*/
    $reservation_date = $_POST['reservation_date'];
    $reservation_personInCharge_user_id = $_POST['reservation_personInCharge_user_id'];



    /*--------------------------------------------------
    /* ユーザー登録
    /*------------------------------------------------*/


    // ---------- ユーザーが存在していたら ----------

    if (user_exists("user_email", $_POST['user_email'])) {
      $class_user = new Rampup_User($_POST['user_email'], 'email');
      $user_id = $class_user->user_id;

      if ($class_user->user_status == 'cancelled') {
        update_user_meta($class_user->user_id, 'user_status', 'before_contract');
      }
    }
    // ---------- ユーザーが存在していなかったら ----------
    else {
      // ---------- ユーザー登録実行 ----------
      $user_registeringInfo = [
        'user_login' => $_POST['user_login'],
        'display_name' => $_POST['user_lastName'] . ' ' . $_POST['user_firstName'],
        'user_email' => $_POST['user_email'],
        'user_pass' => $_POST['user_login'],
        'role' => 'subscriber',
      ];
      $user_id = wp_insert_user($user_registeringInfo);
      $class_user = new Rampup_User($user_id);
    }

    // // ---------- ユーザー情報 (WP_usersより) ----------
    // $user_displayName = $user_info_array->display_name;
    // $user_login = $user_info_array->user_login;

    /*--------------------------------------------------
      /* 投稿に関する処理
      /*------------------------------------------------*/
    // $user_status = get_user_meta($user_id, "user_status", true);

    // ---------- カレンダーのアップデートじゃなかったら ----------
    if ($_GET['calendar_action'] !== 'update_calendar') {
      // ---------- 投稿挿入 ----------
      if (
        $class_user->user_status == 'before_contract' || // 契約前
        $class_user->user_status == 'cancelled' || //キャンセル
        $class_user->user_status == 'rest' || //休止中
        !$class_user->user_status
      ) {
        $post_id = insert_reservation_post(
          $class_user->user_displayName,
          $meetingBeforeContract_title,
          $user_id,
          $reservation_date,
          'before_contract',
          'before_contract'
        );
      } elseif (
        $class_user->user_status == 'application' || //申し込み
        $class_user->user_status == 'paid'  //決済すみ
      ) {
        $post_id = insert_reservation_post(
          $class_user->user_displayName,
          $meetingAfterContract_title,
          $user_id,
          $reservation_date,
          'after_contract',
          'after_contract'
        );
      }
    }
    // ---------- カレンダーのアップデートの場合 ----------
    else {
      $post_id = $_GET['calendar_post_id'];
    }
    update_post_meta($post_id, 'reservation_date', $reservation_date);
    update_post_meta($post_id, 'reservation_personInCharge_user_id', $reservation_personInCharge_user_id);

    /*--------------------------------------------------
    /* ユーザーメタ登録
    /*------------------------------------------------*/
    $user_registeredMeta_array = [
      // "post_id" => $post_id,
      "user_tel" => $_POST['user_tel'],
      'user_nickname' => $class_user->user_displayName,
      "user_question" => $_POST['user_question'],
      "user_lastName" => $_POST['user_lastName'],
      "user_firstName" => $_POST['user_firstName'],
    ];
    // ---------- メタ情報登録実行 ----------
    foreach ($user_registeredMeta_array as $user_registeredMeta_key => $user_registeredMeta_value) {
      update_user_meta($user_id, $user_registeredMeta_key, $user_registeredMeta_value);
    }
    if (
      $class_user->user_status == 'cancelled' || //キャンセル
      !$class_user->user_status
    ) {
      update_user_meta($user_id, 'user_status', 'before_contract');
    }

    /*--------------------------------------------------
      /* GoogleAPIに渡す値
      /*------------------------------------------------*/
    session_start();
    $_SESSION['calendar_action'] = $_GET['calendar_action'];
    $_SESSION['post_id'] = $post_id;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['emailCheck'] = $_POST['emailCheck'];
    $_SESSION['post_token'] = $_POST['token'];

    // ---------- リダイレクト ----------
    wp_safe_redirect("/add-meeting-thanks");
    exit;
  }
}

// }

if (isset($_GET['user_id'])) {

  $user_id = esc_attr($_GET['user_id']);
} else {

  $user_id = get_current_user_id();
}
$class_user = new Rampup_User($user_id);
$calendar_action = esc_attr($_GET['calendar_action']);
$reservation_personInCharge_user_id_GET = $_GET['reservation_personInCharge_user_id'];
$reservation_personInCharge_user_id_esc = esc_attr($reservation_personInCharge_user_id_GET);
$token = uniqid('', true);
session_start();
$_SESSION['token'] = $token;
