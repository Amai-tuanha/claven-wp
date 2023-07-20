<?php

use Carbon\Carbon;
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
global
  $user_administrators_array;
  $user_status_translation_array;


// ---------- 検索するテーブルのステータスを指定 ----------

$keyword_target_db_tables_array = [
  'postmeta',
  'posts',
  'usermeta',
  'users',
];
// ---------- 購読者のみのユーザーID一覧 ----------

$user_subscribers_object = get_users([
  'role' => 'subscriber', //購読者
]);
$user_subscribers_array = [];
foreach ($user_subscribers_object as $user_subscribers) {
  $user_subscribers_id = $user_subscribers->ID;
  array_push($user_subscribers_array, $user_subscribers_id);
}

// ---------- 変数定義 ----------
$trriger_GET = $_GET['serchFormTrigger'];
$status_GET = $_GET['user_status'];
$contract_date_GET = $_GET['contract_date'];
$personInCharge_GET = $_GET['personInCharge'];
$reservation_date_GET = $_GET['reservation_date'];
$keyword_GET = $_GET['keyword'];
$escape_contract_date_GET = '/' . $contract_date_GET . '/';
$escape_reservation_date_GET = '/' . $reservation_date_GET . '/';
$escape_personInCharge_GET = '/' . $personInCharge_GET . '/';


// ---------- 送信された情報をユーザーメタで更新 ----------
$url = get_current_link();
$parsed_url = parse_url($url);
$parsed_query = parse_url($url, PHP_URL_QUERY);
$query_array = [];
parse_str($parsed_query, $query_array);
array_shift($query_array);
// if (is_nullorempty($contract_date_GET)) {
//   unset($contract_date_GET);
// }
// if (is_nullorempty($reservation_date_GET)) {
//   unset($reservation_date_GET);
// }


// ---------- GETの有無で表示するステータスを分岐 ----------
if (isset($status_GET)) {
  $user_status_array = [];
  array_push($user_status_array, $query_array['user_status']);
  // $user_status_array = $query_array['user_status'];
} else {
  $user_status_array = [];
  foreach ($user_status_translation_array as $key => $value) {
    array_push($user_status_array, $key);
  }
}

// ---------- 条件分岐 ----------
$onlyAdmin = current_user_can("administrator") || current_user_can("operator");


// ---------- キーワード検索 ----------
if (isset($keyword_GET)) {

  // ---------- メールアドレス検索用SQL ----------

  $keyword_user_email_array = $wpdb->get_results("SELECT `ID`, `user_email`, `display_name` FROM `wp_users` WHERE `user_email` LIKE '%$keyword_GET%' ORDER BY `ID` ASC", ARRAY_A);

  // ---------- 苗字検索用SQL ----------
  $keyword_user_lastName_array = $wpdb->get_results("SELECT `user_id`, `meta_key`, `meta_value` FROM `wp_usermeta` WHERE `meta_key` LIKE 'user_lastName' AND `meta_value` LIKE '%$keyword_GET%' ORDER BY `user_id` ASC", ARRAY_A);

  // ---------- 名前検索用SQL ----------
  $keyword_user_firstName_array = $wpdb->get_results("SELECT `user_id`, `meta_key`, `meta_value` FROM `wp_usermeta` WHERE `meta_key` LIKE 'user_firstName' AND `meta_value` LIKE '%$keyword_GET%' ORDER BY `user_id` ASC", ARRAY_A);

  // ---------- 契約日検索用SQL ----------
  $keyword_user_contractDate_array = $wpdb->get_results("SELECT `user_id`, `meta_key`, `meta_value` FROM `wp_usermeta` WHERE `meta_key` LIKE 'user_contractDate' AND `meta_value` LIKE '%$keyword_GET%' ORDER BY `user_id` ASC", ARRAY_A);

  // ---------- 面談日検索用SQL ----------
  $keyword_reservation_date_array = $wpdb->get_results("SELECT `post_id`, `meta_key`, `meta_value` FROM `wp_postmeta` WHERE `meta_key` LIKE 'reservation_date' AND `meta_value` LIKE '%$keyword_GET%' ORDER BY `post_id` ASC", ARRAY_A);

  // ---------- 担当者検索用SQL ----------
  $keyword_user_personInCharge_array = $wpdb->get_results("SELECT `user_id`, `meta_key`, `meta_value` FROM `wp_usermeta` WHERE `meta_key` LIKE 'user_personInCharge' AND `meta_value` LIKE '%$keyword_GET%' ORDER BY `user_id` ASC", ARRAY_A);
}

// ---------- キーワード検索に一致したユーザーIDの配列を生成 ----------

$user_id_array = [];

if (!is_nullorempty($keyword_GET)) {

  // ---------- メールアドレス一致ユーザーのID摘出 ----------
  foreach ($keyword_user_email_array as $keyword_user_email) {
    if (!in_array($keyword_user_email['ID'], $user_id_array)) {
      array_push($user_id_array, $keyword_user_email['ID']);
    }
  }

  // ---------- 苗字一致ユーザーのID摘出 ----------
  foreach ($keyword_user_lastName_array as $keyword_user_lastName) {
    if (!in_array($keyword_user_lastName['user_id'], $user_id_array)) {
      array_push($user_id_array, $keyword_user_lastName['user_id']);
    }
  }

  // ---------- 名前一致ユーザーのID摘出 ----------
  foreach ($keyword_user_firstName_array as $keyword_user_firstName) {
    if (!in_array($keyword_user_firstName['user_id'], $user_id_array)) {
      array_push($user_id_array, $keyword_user_firstName['user_id']);
    }
  }

  // ---------- 契約日一致ユーザーのID摘出 ----------
  foreach ($keyword_user_contractDate_array as $keyword_user_contractDate) {
    if (!in_array($keyword_user_contractDate['user_id'], $user_id_array)) {
      array_push($user_id_array, $keyword_user_contractDate['user_id']);
    }
  }

  // ---------- 担当者名一致ユーザーのID摘出 ----------
  foreach ($keyword_user_personInCharge_array as $keyword_user_personInCharge) {
    if (!in_array($keyword_user_personInCharge['user_id'], $user_id_array)) {
      array_push($user_id_array, $keyword_user_personInCharge['user_id']);
    }
  }
  // ---------- ミーティング実施予定の面談日程一致ユーザーのID摘出 ----------
  foreach ($keyword_reservation_date_array as $keyword_reservation_date) {
    $key_post_id = $keyword_reservation_date['post_id'];
    $class_post = new Rampup_Post($key_post_id);
    $post_status = $class_post->post_status;
    $key_user_id = $class_post->reservation_user_id;
    if (!in_array($key_user_id, $user_id_array) && $post_status == 'scheduled') {
      array_push($user_id_array, $key_user_id);
    }
  }

  // ---------- 検索結果のなかで、購読者のユーザーIDのみの配列として取得 ----------
  $keyword_subscribers_user_id_array = array_intersect($user_id_array, $user_subscribers_array);
  $user_list_count = count($keyword_subscribers_user_id_array);
} elseif (is_nullorempty($keyword_GET)) {
  $user_list_count = count(rampup_get_user_by_meta('user_status', $user_status_array));
}