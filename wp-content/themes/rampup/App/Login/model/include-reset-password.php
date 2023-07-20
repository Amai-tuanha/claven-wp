<?php
use Carbon\Carbon;
// add_action('page_reset_password__send_form' , 'page_reset_password__send_form');
// function page_reset_password__send_form() {
  /*--------------------------------------------------
  /* 30分たったらキー削除
  /*------------------------------------------------*/
  
  // $url = $_SERVER['REQUEST_URI'];
  // if(strstr($url,'reset-password')==true){
    //   wp_safe_redirect(home_url() . '/login');
    // }elseif(strstr($url,'reset-password/?login=' . $user_id )==true){
      // }
      
      //URLにhogeが含まれていたら表示する内容
      // use Carbon\Carbon;
      

// $che = new Carbon('2018-08-08 19:15:00');
// echo $now->diffInMinutes($second); // 10
/*--------------------------------------------------
/* パスワードリセット
/*------------------------------------------------*/
// ---------- ユーザー情報 ----------
$user_info_array = get_user_by('login', $_GET['login']);
$user_id = $user_info_array->id;

// ---------- メール送信時にDBのユーザーテーブルに保存したキーを取得 ----------
$user_resetPassword_key = get_user_meta($user_id, 'user_resetPassword_key', true);
$user_resetPassword_submittedTime = get_user_meta($user_id, 'user_resetPassword_submittedTime', true);

// ---------- リンクした時点の時刻を取得しフォーマットする ----------
$now = new Carbon('now');
$user_resetPassword_submittedTime_carbon = new Carbon($user_resetPassword_submittedTime);
// var_dump($user_resetPassword_submittedTime_carbon_formatted);
// ---------- リセットリンクを送信した日時　- 現在時刻 ----------
$user_resetPassword_differenceTime = $now->diffInMinutes($user_resetPassword_submittedTime_carbon);


// ---------- リセットリンクを送信して30分経ったら無効なリンクにページ遷移する ----------
if ($user_resetPassword_differenceTime >= 30 && $user_resetPassword_key) {
  update_user_meta($user_id, 'user_resetPassword_key', '');
  wp_safe_redirect(home_url() . '/reset-password');
  exit;
};

// ---------- パスワードリセット ----------
if (isset($_POST['new_password'])) {
  wp_update_user([
    'user_pass' => $_POST['new_password'],
    'ID' => $user_id,
  ]);

  // ---------- パスワードリセットのIDを消す ----------
  update_user_meta($user_id, 'user_resetPassword_key', '');
  // ---------- リダイレクト ----------
  wp_safe_redirect(home_url() . '/login/');
  exit;
}


// }

?>
