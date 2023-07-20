<?php
global $wpdb;
/*--------------------------------------------------
/* 開発側の全共通 変数や配列を定義するファイルです
/*------------------------------------------------*/
// ---------- carbonの準備 ----------
use Carbon\Carbon;

include(get_theme_file_path() . "/App/vendor/autoload.php");

/*--------------------------------------------------
/* サイト固有の値
/*------------------------------------------------*/
$site_title = get_bloginfo('name');

/*--------------------------------------------------
/* サイト固有の値 【設定画面】
/*------------------------------------------------*/
$company_site = home_url();
$site_default_contractTerm = get_option('site_default_contractTerm');
$site_default_contractEnd = get_option('site_default_contractEnd');
$site_default_contractEndCheck = get_option('site_default_contractEndCheck');
$site_mail_list = get_option('site_mail_list');
$site_company_name = get_option('site_company_name');
$site_company_address = get_option('site_company_address');
$site_course_name = get_option('site_course_name');
$site_bank_data = get_option('site_bank_data');

/*--------------------------------------------------
/* サイト固有の値 【Google連携】
/*------------------------------------------------*/
$site_gmail = get_option('site_gmail');
$site_gmail_password = get_option('site_gmail_password');
$site_mail_title = get_option('site_mail_title');
$site_calendar_id = get_option('site_calendar_id');
$site_client_id = get_option('site_client_id');
$site_client_secret = get_option('site_client_secret');
$site_refresh_token = get_option('site_refresh_token');
$site_secret_key = get_option('site_secret_key');
$site_google_calendar_email_array = json_decode(get_option('site_google_calendar_email_array'));

/*--------------------------------------------------
/* サイト固有の値 【RAMPUPカレンダーの設定】
/*------------------------------------------------*/
$site_opening_time = (get_option('site_opening_time')) ? get_option('site_opening_time') : '10:00';
$site_closing_time = (get_option('site_closing_time')) ? get_option('site_closing_time') : '20:00';
$site_closing_time_sub30Min = Carbon::parse($site_closing_time)->subMinutes(30)->format('H:i');
$site_numberOfCalendarCell = (get_option('site_numberOfCalendarCell')) ? get_option('site_numberOfCalendarCell') : '60';
$site_reservation_personInCharge_user_id = get_option('site_reservation_personInCharge_user_id');
$site_reservation_personInCharge_user_id_array = json_decode(get_option('site_reservation_personInCharge_user_id_array'));


/*--------------------------------------------------
/* 面談の名前
/*------------------------------------------------*/
$meetingBeforeContract_title = "初回面談";
$meetingAfterContract_title = "スタートミーティング";

// ---------- wpのブログページのURL ----------
$wp_blog_url = get_permalink(get_option('page_for_posts'));

// ---------- 曜日 ----------
$weekday_jap = [
  '日', //0
  '月', //1
  '火', //2
  '水', //3
  '木', //4
  '金', //5
  '土', //6
];

// ---------- ユーザーの情報 ----------
$user_id = get_current_user_id();
$user_info_array = get_user_by('id', $user_id);
$user_email = $user_info_array->user_email;
$user_displayName = $user_info_array->display_name;

// ---------- ユーザーメタ情報 ----------
// $reservationPost_id = get_user_meta($user_id, "reservationPost_id", true);

// $user_administrator_emails_array

// ---------- ユーザーステータスの種類と日本語訳 ----------
$user_status_translation_array = [
  "before_contract" => "カウンセリング", //初期ステータス
  "cancelled" => "キャンセル", //自動
  "application" => "仮契約", //手動
  "paid" => "決済済み", //手動・自動
  "rest" => "休止中", //手動
];
// ---------- 支払い方法の種類と日本語訳 ----------
$user_paymentMethod_array = [
  // "yet" => "未決定",
  "card" => "カード",
  "bank" => "銀行",
];
// ---------- 分割回数の種類と日本語訳 ----------
$user_numberOfDivision_array = [
  // "yet" => "未決定",
  "1" => 1,
  "3" => 3,
  "6" => 6,
  "12" => 12,
];

// ---------- 金額プラン ----------
$user_paymentPlan_array = [];
global $wpdb;
$stripe_decoratedPlans = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'fullstripe_subscription_forms`');
foreach ($stripe_decoratedPlans as $stripe_decoratedPlan) {
  if (json_decode($stripe_decoratedPlan->decoratedPlans)) {
    $user_paymentPlan_array[$stripe_decoratedPlan->name] = json_decode($stripe_decoratedPlan->decoratedPlans);
  }
}

// ---------- 分割回数の種類と日本語訳 ----------
// $user_paymentPlan_array = [
//   "without_discount" => "割引なし",
//   "with_discount" => "割引あり",
// ];
// ---------- 性別 ----------
$user_gender_array = [
  "male" => "男性",
  "female" => "女性",
  "other" => "その他",
];
// ---------- 流入経路 ----------
$user_inflowRoute_array = [
  'listing_ads' => 'リスティング広告',
  'display_ads' => 'ディスプレイ広告',
  'instagram_ads' => 'インスタグラム広告',
  'facebook_ads' => 'フェイスブック広告',
  'introduce' => '紹介',
  'member_introduce' => '会員からの紹介',
  'twitter' => 'Twitter',
  'acquaintance_introduce' => '既存の知り合いからの紹介',
  'customer' => '顧客',

];

// ----------  ----------
$reservation_post_status_array = [
  'scheduled' => '実施予定',
  'done' => '実施済み',
  'cancelled' => 'キャンセル',

];

// ---------- 今日 ----------
$today = new Carbon("today");


// ---------- ステップごとの色 ----------
$stepColorArray = [
  "step0" => "#E3B0D4",
  "step1" => "#90D2D8",
  "step2" => "#edbb80",
  "step3" => "#88CE9B",
  "step4" => "#D5A5E6",
  "step5" => "#E8A8A8",
  "step6" => "#EAD681",
  "step7" => "#9CA4E3"
];
// ---------- wp_users　テーブルのemailのカラムを縦に取得 ----------
$allUsersEmail_array = $wpdb->get_col("SELECT user_email FROM $wpdb->users");

// ---------- カスタム投稿「面談日程」投稿ステータスの配列 ----------
$reservation_type_translation_array = [
  "before_contract" => $meetingBeforeContract_title,
  "after_contract" => $meetingAfterContract_title,
  "lost" => "失注",
  "cancelled" => "キャンセル",
];

// ---------- カスタム投稿「面談日程」のアクティブなステータス ----------
$reservation_post_active_status_array = [
  'scheduled',
  'publish'
];
