<?php 
use Carbon\Carbon;
global $wpdb;
// -------- --  投稿の情報を出力するクラス ----------
class Rampup_Post
{
  public function __construct($post_id)
  {
    // ---------- wp postの情報 ----------
    $get_post = get_post($post_id);
    $this->post_id = $get_post->ID;
    $this->post_type = $get_post->post_type;
    $this->post_status = $get_post->post_status;
    $this->post_title = get_post_field('post_title', $post_id);

    // ---------- メタ情報 ----------
    $this->reservation_date = get_post_meta($post_id, "reservation_date", true);
    $this->reservation_user_id = get_post_meta($post_id, "reservation_user_id", true);
    $this->cancel_reason = get_post_meta($post_id, "cancel_reason", true);

    $this->reminder_before_contract_2day = get_post_meta($post_id, "reminder_before_contract_2day", true);
    $this->reminder_before_contract_dday = get_post_meta($post_id, "reminder_before_contract_dday", true);
    $this->reminder_after_contract_1day = get_post_meta($post_id, "reminder_after_contract_1day", true);

    $this->reservation_meet_url = get_post_meta($post_id, "reservation_meet_url", true);
    $this->reservation_meet_id = get_post_meta($post_id, "reservation_meet_id", true);

    $this->reservation_personInCharge_user_id = get_post_meta($post_id, "reservation_personInCharge_user_id", true);
  }
}


/**
 * ユーザーの情報を出力するクラス
 *
 * @param $user ユーザーの値（idやemailなど）
 * @param $type ユーザーオブジェクトのフィールド（id, email, loginが指定可能）
 */
   class Rampup_User 
   {
     public function __construct($user, $type = 'id')
     {
       global $user_paymentPlan_array;
       // ---------- wp usersの情報 ----------
       $user_info_array = get_user_by($type, $user);
       $user_id = $user_info_array->ID;
       $this->user_id = $user_id;
       $this->user_email = $user_info_array->user_email;
       $this->user_displayName = $user_info_array->display_name;
       $this->user_login = $user_info_array->user_login;
   
       // ---------- メタ情報 ----------
       $this->user_lastName = get_user_meta($user_id, "user_lastName", true);
       $this->user_firstName = get_user_meta($user_id, "user_firstName", true);
       $this->user_question = get_user_meta($user_id, "user_question", true);
       $this->user_nickname = get_user_meta($user_id, "user_nickname", true);
       $this->user_tel = get_user_meta($user_id, "user_tel", true);
       $this->user_postNumber = get_user_meta($user_id, "user_postNumber", true);
       $this->user_address = get_user_meta($user_id, "user_address", true);
       $this->user_gender = get_user_meta($user_id, "user_gender", true);
       $this->user_status = get_user_meta($user_id, "user_status", true);
       $this->user_paymentMethod = get_user_meta($user_id, "user_paymentMethod", true);
       $this->user_birthday = get_user_meta($user_id, "user_birthday", true);
       $this->user_profession = get_user_meta($user_id, "user_profession", true);
       $this->user_customerDetails = get_user_meta($user_id, "user_customerDetails", true);
       $this->user_numberOfDivision = get_user_meta($user_id, "user_numberOfDivision", true);
       $this->user_paymentMethod = get_user_meta($user_id, "user_paymentMethod", true);
       $this->user_paymentPlan = get_user_meta($user_id, "user_paymentPlan", true);
       $this->user_contractTerm = get_user_meta($user_id, "user_contractTerm", true);
       $this->user_seminar_attendance_type = get_user_meta($user_id, "user_seminar_attendance_type", true);
       $this->user_applicationDate = get_user_meta($user_id, "user_applicationDate", true);
       $this->user_contractDate = get_user_meta($user_id, "user_contractDate", true);
       $this->user_personInCharge = get_user_meta($user_id, "user_personInCharge", true);
       $this->user_interviewContent = get_user_meta($user_id, "user_interviewContent", true);
       $this->user_remarks = get_user_meta($user_id, "user_remarks", true);
       $this->user_termsOfService = get_user_meta($user_id, "user_termsOfService", true);
       $this->user_attendance_count = get_user_meta($user_id, "user_attendance_count", true);
       $this->user_postalcode_first = get_user_meta($user_id, "user_postalcode_first", true);
       $this->user_postalcode_last = get_user_meta($user_id, "user_postalcode_last", true);
       $this->user_region = get_user_meta($user_id, "user_region", true);
       $this->user_locality = get_user_meta($user_id, "user_locality", true);
       $this->user_chome = get_user_meta($user_id, "user_chome", true);
       $this->user_building = get_user_meta($user_id, "user_building", true);
       $this->user_dob_year = get_user_meta($user_id, "user_dob_year", true);
       $this->user_dob_month = get_user_meta($user_id, "user_dob_month", true);
       $this->user_dob_date = get_user_meta($user_id, "user_dob_date", true);
       $user_avatarURL = get_user_meta($user_id, "user_avatarURL", true);
       $this->user_avatarURL = ($user_avatarURL) ? $user_avatarURL : get_template_directory_uri() . '/assets/img/mypage/icon_defaultAvatar_1.svg';
       $this->user_inflowRoute = get_user_meta($user_id, "user_inflowRoute", true);
       $this->user_seminar_attendance_type = get_user_meta($user_id, "user_seminar_attendance_type", true);
   
   
       // ---------- stripe決済の情報 ----------
       $this->user_stripe_decoratedPlan = $user_paymentPlan_array[$user_info_array->user_paymentPlan][0];
       $this->user_paymentPlan_name = $this->user_stripe_decoratedPlan->name;
       $this->setupFee = $this->user_stripe_decoratedPlan->setupFee;
       $this->splitAmmount = $this->user_stripe_decoratedPlan->price;
       $this->cancellationCount = $this->user_stripe_decoratedPlan->cancellationCount;
       $this->splitAmmount_total = $this->splitAmmount * $this->cancellationCount;
       $this->total_price = $this->setupFee + $this->splitAmmount_total;
       $this->first_payment = $this->setupFee + $this->splitAmmount;
       if ($this->setupFee) {
         $this->commission = round(($this->setupFee / $this->total_price) * 100);
       } else {
         $this->commission = 0;
       }
   
       // ---------- 契約終了日の情報 ----------
       $nowRest = new Carbon('today');
       $contractDate = $this->user_contractDate;
       $contractDate_carbon = new Carbon($contractDate);
       // $nowRest_formatted = $nowRest->format('Y-m-d');
       $contractTerm = $contractDate_carbon->addMonth($this->user_contractTerm);
       // $contractTerm_formatted = $contractTerm->format('Y-m-d');
       $this->user_contractEnd = $nowRest->diffInDays($contractTerm, false);
     }
   
     // ---------- 各ユーザーのクレジットカードのURL ----------
     public function user_card_payment_link($param = '')
     {
       return home_url('/payment-card' . '/?user_id=' . $this->user_id . '&user_login=' . $this->user_login . $param);
     }
   
     // ---------- 各ユーザーの利用規約ページのURL ----------
     public function user_termsOfService_link($param = '')
     {
       return home_url('/terms-of-service' . '/?user_id=' . $this->user_id . '&user_login=' . $this->user_login . $param);
     }
   
     // ---------- ユーザーの情報から送信メールを送る ----------
     public function user_get_email($calendar_action)
     {
       if ($calendar_action == 'update_calendar') {
         $emailPost_id = slug_to_post_id("email-mypage-change-schedule");
       } elseif ($calendar_action == 'add_calendar') {
         // ---------- 日程追加 契約前 ----------
         if (
           $this->user_status == 'before_contract' || // 初回商談
           $this->user_status == 'rest' || // 休止中
           $this->user_status == 'cancelled' // キャンセル
         ) {
           $emailPost_id = slug_to_post_id("email-before-contract");
         }
   
         // ---------- 日程追加 契約後 ----------
         elseif (
           $this->user_status == 'application' || // 申し込み
           $this->user_status == 'paid'
         ) {
           $emailPost_id = slug_to_post_id("email-after-contract-thanks");
         }
       }
   
       $post_title = get_post_field('post_title', $emailPost_id);
   
       return [
         'slug' => get_permalink($emailPost_id),
         'post_title' => get_post_field('post_title', $emailPost_id),
         'post_content' => get_post_field('post_content', $emailPost_id),
         'post_id' => $emailPost_id,
       ];
     }
   }

   /**
 * 面談日程に関する関数をまとめたもの
 *
 * @param $
 */
class Rampup_reservation
{

  function post_count($args_added = [])
  {
    $args_initial = [
      'post_type' => 'reservation',
      'posts_per_page' => -1,
    ];
    $args = array_merge($args_added, $args_initial);
    $WP_post = new WP_Query($args);
    $post_count = $WP_post->post_count;
    return $post_count;
  }
}

   /**
 * ソート関数をまとめたもの
 *
 * @param $
 */
class Rampup_sort
{
  public function sortByKey($key_name, $sort_order, $array) {
    foreach ($array as $key => $value) {
      $standard_key_array[$key] = $value[$key_name];
    }
    
    array_multisort($standard_key_array, $sort_order, $array);
    
    return $array;
  }
}
