<?php

use Carbon\Carbon;
use Google\Service\Blogger\Blog;
// add_action('page_controlpanel_meeting_list__send_form', 'page_controlpanel_meeting_list__send_form');
// function page_controlpanel_meeting_list__send_form() {
global
	$user_status_translation_array;
$get_posts_array;
$reservation_post_status_array;
global
	$user_administrators_array;
$user_status_translation_array;
// ---------- 検索するテーブルのステータスを指定 ----------

$search_target_db_tables_array = [
	'postmeta',
	'posts',
	'usermeta',
	'users',
];
// ---------- 変数定義 ----------
$trriger_GET = esc_attr($_GET['serchFormTrigger']);
$post_status_GET = esc_attr($_GET['post_status']);
$status_GET = esc_attr($_GET['user_status']);
$contract_date_GET = esc_attr($_GET['contract_date']);
$personInCharge_GET = esc_attr($_GET['personInCharge']);
$reservation_date_GET = esc_attr($_GET['reservation_date']);
$keyword_GET = esc_attr($_GET['keyword']);
$escape_reservation_date_GET ='/' . $reservation_date_GET . '/';
$post_status_jap = $reservation_post_status_array[$post_status_GET];
$match_post_id_array = [];
$all_post_id_array = [];


// ---------- 送信された情報をユーザーメタで更新 ----------
$url = get_current_link();
$parsed_url = parse_url($url);
$parsed_query = parse_url($url, PHP_URL_QUERY);
$query_array = [];
parse_str($parsed_query, $query_array);
array_shift($query_array);

// ---------- kennsaku  ----------


$post_status_GET = $_GET['post_status'];
if (isset($_POST['postStatusTrigger'])) {
	// ---------- 変数定義 ----------

	// ---------- ユーザーメタアップデート ----------
	$my_post = array(
		'ID'           => $_POST['post_id'],
		'post_status'   => $_POST['post_status'],
	);
	// データベースにある投稿を更新する
	wp_update_post($my_post);



	wp_safe_redirect(get_current_link());
	exit;
}

// if ('GET' === $_SERVER['REQUEST_METHOD']) {

// 	/*--------------------------------------------------
// 	/* reservation_dateのフィルター
// 	/*------------------------------------------------*/
// 	if (isset($_GET['reservation_date']) && $reservation_date_GET !== '') {
// 		$reservation_post_array = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}postmeta` WHERE meta_key = 'reservation_date' AND meta_value LIKE '$reservation_date_GET%' ");

// 		$reservation_post_id_array = [];
// 		foreach ($reservation_post_array as $reservation_post) {
// 			array_push($reservation_post_id_array, $reservation_post->post_id);
// 		}
// 	}
// }


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