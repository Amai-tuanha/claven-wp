<?php
use Carbon\Carbon;
// add_action('page_existing_users_register__send_form' , 'page_existing_users_register__send_form');

// function page_existing_users_register__send_form() {
  $error = [];
  global
  $allUsersEmail_array;



  if ('POST' === $_SERVER['REQUEST_METHOD']) {
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
      $error['user_email'] = 'email';
    } elseif (in_array($post['user_email'], $allUsersEmail_array)) {
      $error['user_email'] = 'user_once';
    }
    if ('' === $post['user_tel']) {
      $error['user_tel'] = 'blank';
    }
    if ('' === $post['user_status']) {
      $error['user_status'] = 'blank';
    }
    // if ('' === $post['user_pass']) {
    //   $error['user_pass'] = 'blank';
    // }



    if (0 === count($error)) {
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
      // ---------- メタに電話番号登録 ----------
      update_user_meta($class_user->user_id, 'user_tel', $_POST['user_tel']);
      update_user_meta($class_user->user_id, 'user_status', $_POST['user_status']);
      
      // ---------- セッション（ショートコード用,二重送信防止） ----------
      session_start();
      $token = uniqid();
      $_SESSION['user_id'] = $user_id;
      $_SESSION['token'] = $token;
      
      $emailPost_id = slug_to_post_id("email-new-users-register");

      my_PHPmailer_function(
        do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
        do_shortcode(get_post_field('post_content', $emailPost_id)),
        $class_user->user_email,
      );
    }

      // ---------- ユーザー登録実行 ----------
      // $user = [];
      // $user['user_login'] = $_POST['user_login'];
      // $user['user_nicename'] = $_POST['user_nicename'];
      // $user['display_name'] = $_POST['user_nicename'];
      // $user['user_email'] = $_POST['user_email'];
      // $user['user_pass'] = $_POST['user_pass'];
      // $user['role'] = 'subscriber';
      // $user_id = wp_insert_user($user);

      // ---------- ユーザーメタ登録 ----------
      // update_user_meta($user_id, 'user_status', 'application');

      // if (is_wp_error($user_id)) {
      //   $error['global'] = $user_id->get_error_messages();
      // } else {
      //   // ---------- ログイン実行 ----------
      //   $creds = [];
      //   $creds['user_login'] = $user['user_login'];
      //   $creds['user_password'] = $user['user_pass'];
      //   $creds['remember'] = true;
      //   $user = wp_signon($creds, false);

      //   // ---------- 完了ページへリダイレクト ----------
      //   wp_safe_redirect(home_url() . "/new-users-register/?status=thanks");
      //   exit;
      // }

    }
       // ---------- リダイレクト ----------
       wp_safe_redirect("/new-users-register-thanks");
       exit;
  }

// }