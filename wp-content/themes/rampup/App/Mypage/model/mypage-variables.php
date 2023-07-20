<?php
$isset_mypageCancel = isset($_POST['mypageCancel']);
$isset_mypageChangeDate = isset($_POST['mypageChangeDate']);

// ---------- デフォルトのユーザーアバター ----------
$user_dafaultAvatar = get_template_directory_uri() ."/assets/img/mypage/icon_defaultAvatar_1.svg";
// ---------- ユーザーアバター ----------
$get_user_avatarURL = get_user_meta( $user_id, "user_avatarURL", true);
$get_user_avatarPOST = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s" . $get_user_avatarURL ) );

if (
  is_null($get_user_avatarURL)
|| empty($get_user_avatarURL)) {
	$user_avatarURL = $user_dafaultAvatar;
} else {
	$user_avatarURL = $get_user_avatarURL;
}

// ---------- 学習カレンダー 期間の順番 ----------
$periods = [
  "" => "changeRecord",
  "days" => "changeRecord",
  "month" => "days",
  "year" => "month",
];