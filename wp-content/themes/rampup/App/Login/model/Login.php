<?php

include_once get_theme_file_path() . "/App/Login/model/create-page.php";
// ---------- wp-adminにきたら/loginにリダイレクトさせる 管理者以外 ----------
global $pagenow;
if (
  'wp-login.php' === $pagenow &&
  !current_user_can('administrator')
) {
  wp_redirect(home_url() . '/login/');
}


// ---------- 寄稿者と購読者の時はadmin barを非表示 ----------
add_filter('show_admin_bar', 'hide_admin_bar');
 if (!function_exists('hide_admin_bar')){
   function hide_admin_bar($content)
   {
     return current_user_can('publish_posts') ? $content : false;
   }
}

// ---------- 寄稿者と購読者の時は管理画面に入れないようにする ----------
add_action('auth_redirect', 'custom_user_login_redirect');
 if (!function_exists('custom_user_login_redirect')){
   function custom_user_login_redirect($user_id)
   {
     $user = get_userdata($user_id);
     if ($user->roles[0]  !== 'administrator') {
       wp_redirect(home_url() . '/mypage/');
       exit();
     }
   }
}