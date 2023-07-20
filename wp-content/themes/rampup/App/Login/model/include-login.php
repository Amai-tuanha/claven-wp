<?php
use Carbon\Carbon;
// add_action('page_login__send_form' , 'page_login__send_form');

// function page_login__send_form() {
  if (isset($_POST['userLoginForm'])) {

    // $users_name = get_user_by('login', $_POST['user_login']);
    $users_email = get_user_by('email', $_POST['user_login']);
    if ($users_email) {
      $user_login = $users_email->user_login;
    } else {
      $user_login = $_POST['user_login'];
    }
    $creds = array();
    $creds['user_login'] = $user_login;
    // $creds['user_login'] = $_POST[$login];
    // $creds['user_login'] = $login;
    $creds['user_password'] = $_POST['user_password'];
    $creds['remember'] = true;
    $user = wp_signon($creds, false);
    if (is_wp_error($user)) {

      $error_text = "<b class='-error'>エラー</b>: ユーザー名 <b>" .  $_POST['user_login'] . "</b> のパスワードが間違っています。<a href='" . home_url() . '/forgot-password' . "'>パスワードをお忘れですか ?</a>";
    } else {
      wp_clear_auth_cookie();
      $user_id = $user->ID;
      wp_set_auth_cookie($user_id);
      $class_user = new Rampup_User($user_id);

      if ($user->roles[0]  == 'administrator') {

        wp_safe_redirect(home_url('/controlpanel-dashboard/'));
        // wp_safe_redirect(user_admin_url());
        exit;
      } elseif ($user->roles[0] !== 'administrator' && $class_user->user_status == 'rest') {
        wp_safe_redirect(home_url('/mypage-contract-end/'));
        exit;
      } else {
        wp_safe_redirect(home_url('/mypage/'));
        exit;
      }
    }
}

  if ($_GET['administrator'] == 'true') {
    // $admin= admin_url( $path = '', $scheme = 'https' );
    // wp_redirect($admin);
    wp_safe_redirect(home_url('/controlpanel-dashboard/'));
    // wp_safe_redirect(home_url() . '/wp-admin');
    exit;
    // if ( $_REQUEST['redirect_to']  ) {
    //   $redirect_to = admin_url();
    // } else {
    //   // $redirect_to = $_REQUEST['redirect_to'];
    // }
  }

// }