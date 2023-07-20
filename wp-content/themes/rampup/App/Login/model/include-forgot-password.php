<?php

use Carbon\Carbon;
// add_action('page_forgot_password__send_form' , 'page_forgot_password__send_form');

// function page_forgot_password__send_form() {
if (isset($_POST['forgotPassword'])) {

  $user_email = $_POST['user_email'];
  $class_user = new Rampup_User($user_email, 'email');

  $user_login = $class_user->user_login;
  $user_id = $class_user->user_id;
  $user_exists = username_exists($user_login);
  $user_status = $class_user->user_status;

  if (
    $user_status !== 'before_contract'
  ) {

    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_login'] = $user_login;


    // ---------- 生成されたパラメータ ----------
    $param_id = mt_rand(0, 9999999999);
    $_SESSION['param_id'] = $param_id;

    // ---------- 送信日時を取得 ----------
    $user_resetPassword_submittedTime = Carbon::now()->format("Y-m-d H:i:s");


    // ---------- ユーザーメタに情報を追加 update_user_meta ----------
    update_user_meta($user_id, 'user_resetPassword_key', $param_id);
    update_user_meta($user_id, 'user_resetPassword_submittedTime', $user_resetPassword_submittedTime);



    // ---------- メールの設定 ----------

    $emailPost_id = slug_to_post_id("email-password-forgot");
    my_PHPmailer_function(
      do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
      do_shortcode(get_post_field('post_content', $emailPost_id)),
      $user_email
    );

    session_unset();
    wp_safe_redirect(home_url() . '/forgot-password/?form=sent');
    exit;
  } else {
    $error_text = '無料カウンセリング終了後より変更が可能になります。';
  }
}

// $emailPost_id = slug_to_post_id("email-password-forgot");
// my_PHPmailer_function(
//   do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
//   do_shortcode(get_post_field('post_content', $emailPost_id)),
//   'k.tanba@clane.co.jp'
// );

// }