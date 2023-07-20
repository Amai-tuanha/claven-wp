<?php
/*--------------------------------------------------
/* 開発側でWPに関する関数を定義したり、設定を編集するファイルです
/*------------------------------------------------*/


// ---------- ロゴのリンク変更 ----------
if (!function_exists('my_login_logo_url')) {
  function my_login_logo_url()
  {
    return home_url();
  }
  add_filter('login_headerurl', 'my_login_logo_url');
}


/**
 * リダイレクト関数がheaderの下でも動くように
 */
if (!function_exists('do_output_buffer')) {
  function do_output_buffer()
  {
    ob_start();
  }
  add_action('init', 'do_output_buffer');
}

/**
 * 全ページ共通のjs読み込み()
 */
if (!function_exists('rampup_load_js')) {
  function rampup_load_js()
  {
    wp_enqueue_script('component-disableButton-js', get_stylesheet_directory_uri() . '/assets/js/component-disableButton.js', null, false, true);
    wp_enqueue_script('onChengeSendForm-js', get_stylesheet_directory_uri() . '/assets/js/onChengeSendForm.js', null, false, true);
    wp_enqueue_script('accordion-js', get_stylesheet_directory_uri() . '/assets/js/accordion.js', null, false, true);

    // ---------- wp_headで読み込む ----------
    wp_enqueue_script('resetLocalStorage-js', get_stylesheet_directory_uri() . '/assets/js/resetLocalStorage.js', null, false, false);
  }
  add_action('wp_enqueue_scripts', 'rampup_load_js');
}

/**
 * 全ページ共通のcss読み込み(wp-headで読み込まれるもの)
 */
if (!function_exists('rampup_load_css')) {
  function rampup_load_css()
  {
    wp_enqueue_style('App-style', rampup_css_path('App-style.css'), [], '1.0.3');
    wp_enqueue_style('form-common', rampup_css_path('form-common.css'), [], '1.0.3');
    wp_enqueue_style('component-disableButton-css', rampup_css_path('component-disableButton.css'), [], '1.0.3');
    wp_enqueue_style('fontawsome-cdn', 'https://use.fontawesome.com/releases/v5.10.2/css/all.css', [], '1.0.3');
    wp_enqueue_style('fontawsome-js', 'https://kit.fontawesome.com/f0fc03e17c.js', [], '1.0.3');
    wp_enqueue_style('fontawsome-js', 'https://kit.fontawesome.com/f0fc03e17c.js', [], '1.0.3');
  }
  add_action('wp_enqueue_scripts', 'rampup_load_css');
}

/**
 * wpの既存のメールの関数の宛先を削除（wpから勝手にメールが送られないようにする）
 */
add_filter('wp_mail', 'rampup_disabling_emails', 10, 1);
 if (!function_exists('rampup_disabling_emails')){
   function rampup_disabling_emails($args)
   {
     unset($args['to']);
     return $args;
   }
}

/*--------------------------------------------------
/* 管理画面ユーザー一覧にカラム追加 /wp-admin/users.php
/*------------------------------------------------*/
// ---------- タイトル ----------
if (!function_exists('new_modify_user_table_child')){
  function new_modify_user_table_child($column)
  {
    $column['user_seminar_attendance_type'] = '勉強会';
    unset($column['name']);
  
    return $column;
  }
}
add_filter('manage_users_columns', 'new_modify_user_table_child', 10, 1);

// ---------- 各ユーザーの値 ----------
if (!function_exists('new_modify_user_table_child_row')){
  function new_modify_user_table_child_row($val, $column_name, $user_id)
  {
    $class_user = new Rampup_User($user_id);
  
    // ---------- グローバル変数 ----------
    global $user_status_translation_array, $wpdb, $user_seminar_attendance_type_array;
  
  
    switch ($column_name) {
      case 'user_seminar_attendance_type':
        return $user_seminar_attendance_type_array[$class_user->user_seminar_attendance_type];
      default:
    }
    return $val;
  }
}
add_filter('manage_users_custom_column', 'new_modify_user_table_child_row', 10, 3);



/*--------------------------------------------------
/* 値がなかったら管理者のステータスを常に決済済みにする
/*------------------------------------------------*/
$user_admins = get_users([
  'role' => 'administrator', //管理者
]);
foreach ($user_admins as $user_admin) {
  $user_id = $user_admin->ID;
  if (!get_user_meta($user_id, 'user_status', true)) {
    update_user_meta($user_id, "user_status", "paid");
  }
}

/*--------------------------------------------------
/* 新規ユーザーの内管理者権限のユーザーのステータスを決裁済にする
/*------------------------------------------------*/
add_action('user_register', 'Rampup_User_register'); // 新規ユーザが登録された直後にフックする
if (!function_exists('Rampup_User_register')) {
  function Rampup_User_register($user_id)
  {
    $user_role = get_userdata($user_id)->roles; //ユーザデータが管理者のIDを取得
    if ($user_role == 'administrator') {
      update_user_meta($user_id, 'user_status', 'paid');
    } else {
      update_user_meta($user_id, 'user_status', 'before_contract');
    }
  }
}

/*--------------------------------------------------
/* Wordpressの管理画面のユーザー一覧をホバーしたときに出てくるメニュを追加
/*------------------------------------------------*/
function rampup_custom_user_action_row($actions, $user_object)
{
// ---------- 追加 ----------
  $actions['controlpanel-user-info'] = "<a class='cgc_ub_edit_badges' href='" . home_url("/controlpanel-user-info/?user_id=$user_object->ID") . "' target=\"_blank\" rel=\"noopener\">ユーザー詳細</a>";

  // ---------- 削除 ----------
  if (isset($actions['view'])) {
    unset($actions['view']);
  }
  if (isset($actions['resetpassword'])) {
    unset($actions['resetpassword']);
  }
  return $actions;
}
add_filter('user_row_actions', 'rampup_custom_user_action_row', 10, 2);