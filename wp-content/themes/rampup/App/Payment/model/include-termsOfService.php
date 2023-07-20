<?php

use Carbon\Carbon;

// add_action('page_termsOfService__send_form' , 'page_termsOfService__send_form');

// function page_termsOfService__send_form() {

//   // $user_info_array = get_user_by('ID', $get_id);
//   // $user_id = $user_info_array->ID;

// ---------- メールアドレスをURLとデータベースから取得 ----------
// $get_email = $_GET['user_email'];
// $user_email = $user_info_array->user_email;


// }


// ---------- メールアドレスがデータベースと一致していなかったらリダイレクト ----------
// if (!Rampup_User_match($_GET['user_id'], $_GET['user_login'])) {
//   wp_safe_redirect(home_url() . '/terms-of-service');
// }

// ---------- ユーザーIDを取得し、ユーザー情報を読み込む ----------
if (!$_GET['user_id']) {
  $user_id = get_current_user_id();
} else {
  $user_id = $_GET['user_id'];
}
$class_user = new Rampup_User($user_id);

// // ---------- 利用規約の同意が送信されたら契約日をユーザーメタに登録 ----------
if (isset($_POST['termsOfServiceTrigger'])) {
  // ---------- メールアドレスをURLとデータベースから取得 ----------
  $user_id = $_POST['user_id'];
  foreach ($_POST as $key => $value) {

    if (strpos($key, 'user_') !== false) {
      update_user_meta($user_id, $key, $value);
    }
  }
  session_start();
  $_SESSION['user_id'] = $user_id;

  // ---------- POSTで送信したときに現在時刻取得 ----------
  $now = new Carbon('now');
  $now_formatted = $now->format('Y-m-d');
  $user_id = $_POST['user_id'];
  $class_user = new Rampup_User($user_id);
  update_user_meta($class_user->user_id, 'user_contractDate', $now_formatted);
  session_start();
  $_SESSION['user_id'] = $user_id;

  // ---------- POST送信後のリダイレクトページ ----------
  // wp_safe_redirect(get_permalink() . '/?id=' . $class_user->user_id . '&user_email=' . $class_user->user_email);
  if (
    // ---------- 銀行決済かつ申し込みの人 ----------
    $class_user->user_paymentMethod === 'bank' &&
    $class_user->user_status === 'application'
  ) {
    // wp_safe_redirect($class_user->user_termsOfService_link('#termsOfService_formSent'));
    wp_safe_redirect(home_url('/payment-bank/'));
  } elseif (
    // ---------- 銀行決済かつ決済済みの人 ----------
    $class_user->user_paymentMethod === 'bank' &&
    $class_user->user_status === 'paid'
  ) {
    wp_safe_redirect(home_url('/mypage-timer/'));
  } elseif (
    // ---------- カード決済かつ申し込み済みの人 ----------
    $class_user->user_paymentMethod === 'card' &&
    $class_user->user_status === 'application'
  ) {
    wp_safe_redirect($class_user->user_card_payment_link());
  }
}