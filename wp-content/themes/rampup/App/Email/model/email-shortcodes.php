<?php

use Carbon\Carbon;

/*--------------------------------------------------
開発メモ
user_nameとかは全部１箇所にまとめる

/*------------------------------------------------*/

/*--------------------------------------------------
/* メールのショートコード
/*------------------------------------------------*/
// ---------- reserve_dt ----------
$reserve_dt = strtotime(date("Y-m-d H:i:s"));

/*--------------------------------------------------
/* 共通ショートコード
/*------------------------------------------------*/
if (!function_exists('email_signature')) {
  function email_signature()
  {

    global $site_title,
      $site_company_address,
      $company_site,
      $site_gmail;

    $company_site_tagged = "<a href='$company_site' >$company_site</a>";
    $site_gmail_tagged = "<a href='mailto:$site_gmail' >$site_gmail</a>";
    return "
**************************************************************
$site_title

Address：$site_company_address
Corporate site：$company_site_tagged
Mail：$site_gmail_tagged
**************************************************************";
  }
}
add_shortcode('email_signature', 'email_signature');

/*--------------------------------------------------
/* ログイン「URL
/*------------------------------------------------*/
if (!function_exists('login_link')) {
  function login_link()
  {
    $login_link = home_url() . '/login/';
    if (is_single()) {
      return home_url() . '/login/';
    } else {
      return "<a href=\"$login_link\">$login_link</a>";
      // return $login_link;
    }
  }
}
add_shortcode('login_link', 'login_link');

/*--------------------------------------------------
/* マイページ「URL
/*------------------------------------------------*/
if (!function_exists('mypage_add_reservation_link')) {
  function mypage_add_reservation_link()
  {
    $mypage_add_reservation_link = home_url() . '/mypage-add-reservation/';
    if (is_single()) {
      return $mypage_add_reservation_link;
    } else {
      return "<a href=\"$mypage_add_reservation_link\">$mypage_add_reservation_link</a>";
      // return $mypage_add_reservation_link;
    }
  }
}
add_shortcode('mypage_add_reservation_link', 'mypage_add_reservation_link');

/*--------------------------------------------------
/* 利用規約URL
/*------------------------------------------------*/
if (!function_exists('terms_of_service_link')) {
  function terms_of_service_link()
  {
    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);

    $terms_of_service_link = home_url('/terms-of-service/?user_id=' . $user_id . '&user_login=' . $class_user->user_login);
    // $terms_of_service_link = home_url('/terms-of-service/?user_email=' . $class_user->user_email);
    if (is_single()) {
      return home_url() . '/terms-of-service/?user_id=';
    } else {
      return "<a href=\"$terms_of_service_link\">$terms_of_service_link</a>";
      // return $terms_of_service_link;
      // return home_url(). '/terms-of-service/?id=' . $user_id . '&user_email=' . $class_user->user_login;
    }
  }
}
add_shortcode('terms_of_service_link', 'terms_of_service_link');

/*--------------------------------------------------
/* 担当者名
/*------------------------------------------------*/
if (!function_exists('person_in_charge')) {
  function person_in_charge()
  {
    $post_id = $_SESSION['post_id'];
    $class_post = new Rampup_Post($post_id);
    $reservation_personInCharge_user_id = $class_post->reservation_personInCharge_user_id;
    $class_user = new Rampup_User($reservation_personInCharge_user_id);
    $person_in_charge = $class_user->user_displayName;
    // get_user_meta($user_id, "user_personInCharge", true);
    if (is_single()) {

      return '担当者';
    } else {

      return $person_in_charge;
    }
  }
}
add_shortcode('person_in_charge', 'person_in_charge');

/*--------------------------------------------------
/* 会社名
/*------------------------------------------------*/
if (!function_exists('company_name')) {
  function company_name()
  {
    global $site_mail_title;
    return $site_mail_title;
  }
}
add_shortcode('company_name', 'company_name');

/*--------------------------------------------------
/* サイトタイトル
/*------------------------------------------------*/
if (!function_exists('site_title')) {
  function site_title()
  {
    global $site_title;
    return $site_title;
  }
}
add_shortcode('site_title', 'site_title');

/*--------------------------------------------------
/* 講座名
/*------------------------------------------------*/
if (!function_exists('course_name')) {
  function course_name()
  {
    global $site_course_name;
    return $site_course_name;
  }
}
add_shortcode('course_name', 'course_name');

/*--------------------------------------------------
/* スタイル当てるショートコード(ボックス型)
/*------------------------------------------------*/
if (!function_exists('div')) {
  function div($atts, $content = null)
  {
    // ---------- 初期値 ----------
    $atts = shortcode_atts(array(
      "space" => "pre-wrap",
    ), $atts);

    // ---------- 返り値 ----------
    return '<div
         class="emailText__div"
         style=" white-space : ' . $atts['space'] . ';
          ">' . do_shortcode($content) . '</div>';
  }
}
add_shortcode('div', 'div');

/*--------------------------------------------------
/* 共通ショートコードここまで
/*------------------------------------------------*/

/*--------------------------------------------------
/* ユーザーの情報（名前、メールアドレスなど）
/*------------------------------------------------*/
// ---------- ユーザーネーム ----------
if (!function_exists('user_name')) {
  function user_name()
  {
    global $user_subscribers_array, $reminder_user_displayName;

    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);
    // ---------- 投稿ページだったら ----------
    if (is_single()) {
      return '山田太郎';
    }
    // ---------- ユーザー詳細設定ページだったら ----------
    elseif ($_SERVER['SCRIPT_NAME'] == "/wp-admin/user-edit.php") {
      return $_SESSION['user_displayName'];
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_page("add-meeting-thanks")) {
      return $class_user->user_displayName;
    }
    // ---------- パスワードを忘れた場合 ----------
    elseif (is_page("forgot-password")) {
      return $class_user->user_displayName;
    }
    // ---------- コントロールパネル ユーザー編集画面 ----------
    elseif (is_page("controlpanel-user-edit")) {
      return $class_user->user_displayName;
    }
    // ---------- mypageだったら ----------
    elseif (isset($_POST["singelReservationFormTrigger"])) {
      return $_SESSION["user_displayName"];
    }
    // ---------- マイページキャンセルページ ----------
    elseif (is_page("mypage-cancel-reservation")) {
      return $class_user->user_displayName;
    }
    // ---------- クレジットカードページj ----------
    elseif (is_page('payment-card')) {
      return $class_user->user_displayName;
    }
    // ---------- 新規ユーザー登録だったら ----------
    elseif (is_page('new-users-register')) {
      return $class_user->user_displayName;
    }
    // ---------- cronの場合 ----------
    else {
      return $reminder_user_displayName;
    }
  }
}
add_shortcode('user_name', 'user_name');

// ---------- 電話番号 ----------
if (!function_exists('user_tel')) {
  function user_tel()
  {
    global $user_subscribers_array, $reminder_user_displayName;

    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);
    // ---------- 投稿ページだったら ----------
    if (is_single()) {
      return '山田太郎';
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_page("add-meeting-thanks")) {
      return $class_user->user_tel;
    }
    // ---------- author.php ----------
    elseif (is_author()) {
      return $class_user->user_tel;
    }
    // ---------- パスワードを忘れた場合 ----------
    elseif (is_page("forgot-password")) {
      return $class_user->user_tel;
    }
    // ---------- mypageだったら ----------
    elseif (isset($_POST["singelReservationFormTrigger"])) {
      return $class_user->user_tel;
    }
    // ---------- mypageだったら ----------
    elseif (is_page('payment-card')) {
      return $class_user->user_tel;
    }
    // ---------- 新規登録ユーザーだったら ----------
    elseif (is_page('new-users-register')) {
      return $class_user->user_tel;
    }
    // ---------- cronの場合 ----------
    else {
      return $reminder_user_displayName;
    }
  }
}
add_shortcode('user_tel', 'user_tel');

// ---------- ログインemail ----------
if (!function_exists('user_email')) {
  function user_email()
  {
    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);
    if (is_single()) {
      return 'example@clane.co.jp';
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_page("add-meeting-thanks")) {
      return $class_user->user_email;
    }
    // ---------- 新規ユーザー登録ページ ----------
    elseif (is_page("new-users-register")) {
      return $class_user->user_email;
    } else {
      return $class_user->user_email;
    }
  }
}
add_shortcode('user_email', 'user_email');


// ---------- 面談内容 ----------
if (!function_exists('user_question')) {
  function user_question()
  {
    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);
    if (is_single()) {
      return '受講したい';
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_page("add-meeting-thanks")) {
      return $class_user->user_question;
    }
    // ---------- 新規ユーザー登録ページ ----------
  }
}
add_shortcode('user_question', 'user_question');
// // ---------- ログイン user_pass ----------
// function user_pass()
// {
//     if (is_single()) {
//         return 'intern-1-1111';
//     } else {
//         return $_POST['user_login'];
//     }
// }
// add_shortcode('user_pass', 'user_pass');

// ---------- ユーザーログイン ----------
if (!function_exists('user_login')) {
  function user_login()
  {

    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);
    if (is_single()) {
      // ---------- 管理画面内 メール編集画面だった場合 ----------
      return 'user-1-1111';
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_page("add-meeting-thanks")) {
      return $class_user->user_login;
    }
    // ---------- 新規ユーザー登録ページ ----------
    elseif (is_page("new-users-register")) {
      return $class_user->user_login;
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_author()) {
      return $class_user->user_login;
    }
    // ---------- コントロールパネルユーザー編集ページ ----------
    elseif (is_page('controlpanel-user-edit')) {
      return $class_user->user_login;
    }
    // ---------- パスワードを忘れた方ページ ----------
    elseif (is_page("forgot-password")) {
      return $_SESSION['user_login'];
    }
    // // ---------- 管理画面 ユーザー詳細ページから ----------
    // elseif ($_SERVER['SCRIPT_NAME'] == "/wp-admin/user-edit.php") {
    //     return $_POST['user_login' . $_POST['user_id']];
    // }
  }
}
add_shortcode('user_login', 'user_login');

/*--------------------------------------------------
/* ユーザーの情報（名前、メールアドレスなど）ここまで
/*------------------------------------------------*/

/*--------------------------------------------------
/* 決済に関係するショートコード
/*------------------------------------------------*/

// ---------- カード決済リンク ----------
if (!function_exists('card_payment_link')) {
  function card_payment_link()
  {
    $user_id = $_SESSION['user_id'];
    $class_user = new Rampup_User($user_id);
    $card_payment_link = home_url() . '/payment-card/?user_id=' . $user_id . '&user_login=' . $class_user->user_login;
    if (is_single()) {
      return $card_payment_link;
    } else {
      return "<a href=\"$card_payment_link\">$card_payment_link</a>";
      // return $card_payment_link;
    }
  }
}
add_shortcode('card_payment_link', 'card_payment_link');

// ---------- 分割回数 ----------
if (!function_exists('user_numberOfDivision')) {
  function user_numberOfDivision()
  {
    if (is_single()) {
      return '1';
    } elseif ($_SERVER['SCRIPT_NAME'] == "/wp-admin/user-edit.php") {
      return $_SESSION['user_numberOfDivision'];
    }
  }
}
add_shortcode('user_numberOfDivision', 'user_numberOfDivision');

// ---------- カード決済文章 ----------
if (!function_exists('payment_detail')) {
  function payment_detail()
  {
    if (is_single()) {
      return "Aプラン : 339,900円（分割手数料 : 9900円）
             支払い回数 : 3回

             支払い金額の内訳
             1回目 : 119,900円
             2回目以降 : 110,000円";
    } else {
      $user_id = $_SESSION['user_id'];
      $class_user = new Rampup_User($user_id);
      $total_price = num($class_user->setupFee + $class_user->splitAmmount_total);
      $paymentPlan_name = $class_user->user_paymentPlan_name;
      $cancellationCount = $class_user->cancellationCount;
      $splitAmmount_total = num($class_user->splitAmmount_total);
      $setupFee = num($class_user->setupFee);

      if ($class_user->cancellationCount == 1) {
        $first_payment = '一括振込: ' . num($class_user->total_price) . '円';
        $other_payment = '';
      } else {
        $first_payment = '1回目: ' . num($class_user->first_payment) . '円';
        $other_payment = '2回目以降: ' . num($class_user->splitAmmount) . '円';
      }

      // ---------- 決済結果詳細 ----------
      $payment_detail  = "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
      $payment_detail .= "$paymentPlan_name\n";
      $payment_detail .= "支払い回数 : $cancellationCount 回\n";
      $payment_detail .= "支払い金額の内訳\n";
      $payment_detail .= "$first_payment\n";
      $payment_detail .= ($other_payment) ? "$other_payment\n" : '';
      $payment_detail .= "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";

      return $payment_detail;
    }
  }
}
add_shortcode('payment_detail', 'payment_detail');

// ---------- 口座情報 ----------
if (!function_exists('bank_account_info')) {
  function bank_account_info()
  {
    global 
    $site_bank_data;
    if (is_single()) {
      return
        $site_bank_data;
      } else {
      return
        $site_bank_data;
    }
  }
}
add_shortcode('bank_account_info', 'bank_account_info');


// ---------- 振込口座情報 ----------
if (!function_exists('bank_single_price')) {
  function bank_single_price()
  {
    if (is_single()) {
      return '330,000';
    } elseif ($_SERVER['SCRIPT_NAME'] == "/wp-admin/user-edit.php") {
      if ('with_discount' === $_SESSION['user_paymentPlan']) {
        // ---------- 即決の場合（319,000） ----------
        return '319,000';
      } else {
        // ---------- 即決以外の場合（330,000） ----------
        return '330,000';
      }
    }
  }
}
add_shortcode('bank_single_price', 'bank_single_price');

// ---------- 振込口座文章 ----------
if (!function_exists('bank_sentence')) {

  function bank_sentence()
  {
    if (is_single()) {
      return '300,000円+税にて、以下の口座にお振り込みくださいませ。';
    } elseif (is_author()) {
      $user_id = $_SESSION['user_id'];
      $class_user = new Rampup_User($user_id);
      $first_payment = num($class_user->first_payment) . '円';
      $total_price = num($class_user->total_price) . '円';
      $bank_sentence = '';
      if ($class_user->cancellationCount == '1') {
        $bank_sentence .= '契約に関しては銀行振り込みとのことで承っております。';
        $bank_sentence .= $total_price . "にて、以下の口座にお振り込みくださいませ。";
      } else {
        $bank_sentence .= "契約に関しては $class_user->cancellationCount 回の口座引落とのことで承っております。\n";
        $bank_sentence .= "初回のみ$first_payment をお振り込みいただき、次月以降口座引き落としとさせていただきます。\n";
        $bank_sentence .= "口座振替の用紙を送付させていただきますので、ご確認の上ご返送ください。\n";
        $bank_sentence .= "なお、初回のお振り込みに関しては下記の口座によろしくお願いします。";
      }
      return $bank_sentence;
    }
  }
}
add_shortcode('bank_sentence', 'bank_sentence');

/*--------------------------------------------------
/* 決済に関係するショートコード ここまで
/*------------------------------------------------*/

/*--------------------------------------------------
/* 予約に関するショートコード（初回面談、スタートミーティング）
/*------------------------------------------------*/

// ---------- 面談日時 ----------
if (!function_exists('reservation_date')) {
  function reservation_date()
  {
    global
      $weekday_jap,
      $reminder_post_id;

    $post_id = $_SESSION['post_id'];
    $class_post = new Rampup_Post($post_id);
    if (is_single()) {
      return '01月01日 01時01分';
    }
    // ---------- 管理画面の場合 ----------
    elseif ($_SERVER['SCRIPT_NAME'] == "/wp-admin/user-edit.php") {
      return carbon_formatting($_POST["user_updateSchdule"]);
    }
    // ---------- 初回面談申し込みページの場合 ----------
    elseif (is_page("add-meeting-thanks")) {
      return carbon_formatting($class_post->reservation_date);
    }
    // ---------- マイページキャンセルページの場合 ----------
    elseif (is_page("mypage-cancel-reservation")) {
      return carbon_formatting($class_post->reservation_date);
    }
    // ----------  コントロールパネルユーザー情報編集画面 ----------
    elseif (is_page('controlpanel-user-edit')) {
      return carbon_formatting($class_post->reservation_date);
    }
    // ---------- cronの場合 ----------
    else {
      return carbon_formatting(get_post_meta($reminder_post_id, "reservation_date", true));
    }
  }
}
add_shortcode('reservation_date', 'reservation_date');

// // ---------- マイページ予定変更　面談日時 ----------
//  if (!function_exists('mypage_reservation')){
// function mypage_reservation()
// {
//     global $weekday_jap;

//     if (is_single()) {
//         return '01月01日 01時01分';
//     }
//     // ---------- マイページからのキャンセル ----------
//     elseif (
//         isset($_POST["mypageReservationCancel"]) &&
//         isset($_POST["singelReservationFormTrigger"])
//     ) {
//         return $_POST['mypage_reservation'];
//     }
//     // ---------- マイページからの日程変更 ----------
//     elseif (isset($_POST["singelReservationFormTrigger"])) {
//         $reservation_date = $_SESSION['reservation_date'];
//         return carbon_formatting($reservation_date);
//     }
//     // // ---------- マイページ 日程変更 ----------
//     // elseif (
//     //     is_page("mypage-date-change")
//     //     && isset($_POST['mypageChangeDate'])
//     // ) {
//     //     return carbon_formatting($_POST["user_updateSchdule"]);
//     // }
// }
// add_shortcode('mypage_reservation', 'mypage_reservation');
// }

// ---------- マイページキャンセル理由 ----------
if (!function_exists('mypage_cancel_reason')) {
  function mypage_cancel_reason()
  {
    global $weekday_jap;

    if (is_single()) {
      return 'キャンセル理由が入ります';
    }
    // ---------- マイページからのキャンセル ----------
    elseif (isset($_POST["mypageReservationCancel"])) {
      return $_POST["cancel_reason"];
    }
    // // ---------- マイページ 日程変更 ----------
    // elseif (
    //     is_page("mypage-date-change")
    //     && isset($_POST['mypageChangeDate'])
    // ) {
    //     return carbon_formatting($_POST["user_updateSchdule"]);
    // }
  }
}
add_shortcode('mypage_cancel_reason', 'mypage_cancel_reason');

// ---------- マイページキャンセル時の件名 ----------
if (!function_exists('mypage_cancel_subject')) {
  function mypage_cancel_subject()
  {
    global $wpdb;
    $get_single_reserves = $wpdb->get_row('SELECT * FROM `reserves` WHERE email =' . '"' . $_POST["email"] . '"');
    $reserve_dt = strtotime($get_single_reserves->reserve_dt);
    $today = strtotime(date("Y/m/d"));

    if (is_single()) {
      // ---------- 管理画面内 メール編集画面だった場合 ----------
      return '無料カウンセリング';
    } elseif (is_page("mypage-date-change")) {
      return $_POST["mypageCancel_subject"];
    }
    // ---------- マイページからのキャンセル ----------
    elseif (is_page("mypage-cancel-reservation")) {
      return $_POST["reservation_date"];
    }
  }
}
add_shortcode('mypage_cancel_subject', 'mypage_cancel_subject');

// // ---------- スタートミーティング日時 ----------
// function meetingAfterContract_date()
// {
//     global $weekday_jap, $reminder_post_id;

//     if (is_single()) {
//         return '01月01日 01時01分';
//     }
//     // ---------- 顧客管理画面だったら ----------
//     elseif ($_SERVER['SCRIPT_NAME'] == "/wp-admin/user-edit.php") {
//         return carbon_formatting($_POST['user_meetingAfterContract_date' . $_POST['user_id']]);
//     }
//     // ---------- cronの場合 ----------
//     else {
//         return carbon_formatting(get_post_meta($reminder_post_id, "reservation_date", true));
//     }
// }
// add_shortcode('meetingAfterContract_date', 'meetingAfterContract_date');

// ---------- 返答期限（スタートミーティングの３日前） ----------
if (!function_exists('response_deadline')) {
  function response_deadline()
  {
    global $weekday_jap;

    if (is_single()) {
      return '1月1日 11時11分';
    }
    // ---------- 初回面談申し込み固定ページ ----------
    elseif (is_page("add-meeting-thanks")) {
      $post_id = $_SESSION['post_id'];
      $class_post = new Rampup_Post($post_id);
      $reservation_date = $class_post->reservation_date;
      $reservation_date_carbon = new Carbon($reservation_date);
      $today = new Carbon('today');
      $diffs = $reservation_date_carbon->diffInDays($today);
      // ---------- 差が3日以上だったら ----------
      if ($diffs > 3) {
        $response_deadline = $reservation_date_carbon->subDays(3);
      }
      // ---------- 差が3日未満だったら ----------
      else {
        $response_deadline = $reservation_date_carbon->subDays(1);
      }
      $response_deadline_formatted = carbon_formatting($response_deadline, "", "", true);
    }
    // ---------- コントロールパネルのエディットページ ----------
    elseif (is_page("controlpanel-user-edit")) {
      $post_id = $_SESSION['post_id'];
      $class_post = new Rampup_Post($post_id);
      $reservation_date = $class_post->reservation_date;
      $reservation_date_carbon = new Carbon($reservation_date);
      $today = new Carbon('today');
      $diffs = $reservation_date_carbon->diffInDays($today);
      // ---------- 差が3日以上だったら ----------
      if ($diffs > 3) {
        $response_deadline = $reservation_date_carbon->subDays(3);
      }
      // ---------- 差が3日未満だったら ----------
      else {
        $response_deadline = $reservation_date_carbon->subDays(1);
      }
      $response_deadline_formatted = carbon_formatting($response_deadline, "", "", true);
    }
    // ---------- author.phpページ ----------
    elseif (is_author()) {
      $post_id = $_SESSION['post_id'];
      $class_post = new Rampup_Post($post_id);
      $reservation_date = $class_post->reservation_date;
      $reservation_date_carbon = new Carbon($reservation_date);
      $today = new Carbon('today');
      $diffs = $reservation_date_carbon->diffInDays($today);
      // ---------- 差が3日以上だったら ----------
      if ($diffs > 3) {
        $response_deadline = $reservation_date_carbon->subDays(3);
      }
      // ---------- 差が3日未満だったら ----------
      else {
        $response_deadline = $reservation_date_carbon->subDays(1);
      }
      $response_deadline_formatted = carbon_formatting($response_deadline, "", "", true);
    }
    // // ---------- マイページ スタートミーティングの日時変更の時 ----------
    // elseif (isset($_POST["singelReservationFormTrigger"])) {
    //     $reservation_date = $_SESSION['reservation_date'];
    //     $reservation_date_carbon = new Carbon($reservation_date);
    //     $today = new Carbon('today');
    //     $diffs = $reservation_date_carbon->diffInDays($today);
    //     // ---------- 差が3日以上だったら ----------
    //     if ($diffs > 3) {
    //         $response_deadline = $reservation_date_carbon->subDays(3);
    //     }
    //     // ---------- 差が3日未満だったら ----------
    //     else {
    //         $response_deadline = $reservation_date_carbon->subDays(1);
    //     }
    //     $response_deadline_formatted = carbon_formatting($response_deadline, "19:00");
    //     // $response_deadline_rtv =
    //     //     "■ご決済について\r\n" .
    //     //     "契約・入金が完了いたしましたら、\r\n" .
    //     //     "こちらのメールにご返信いただけますようお願いします。\r\n" .
    //     //     "回答期限 : $response_deadline_formatted まで";
    // }
    return $response_deadline_formatted;
  }
}
add_shortcode('response_deadline', 'response_deadline');

/*--------------------------------------------------
/* カード決済に関連するショートコード
/*------------------------------------------------*/

// ---------- Google_MeetのURL ----------
if (!function_exists('Google_Meet')) {
  function Google_Meet()
  {
    global $reserve_row;
    if (is_single()) {
      return '[Google_Meet]';
    } else {
      return '[Google_Meet]';
    }
  }
}
add_shortcode('Google_Meet', 'Google_Meet');

// // ---------- スタイル当てるショートコード(ボックス型) ----------
// function style($atts, $content = null)
// {
//     // ---------- 初期値 ----------
//     $atts = shortcode_atts(array(
//     "color" => "black",
//     "weight" => "normal",
//     "deco" => "none",
//     "bg" => "transparent",
//     ), $atts);

//     // ---------- 返り値 ----------
//     return '<span style="
//     color : ' . $atts['color'] . ';
//     font-weight : ' . $atts['weight'] . ';
//     text-decoration : ' . $atts['deco'] . ';
//     background-color : ' . $atts['bg'] . ';
//      ">' . do_shortcode($content)  . '</span>';
// }
// add_shortcode('style', 'style');

/*--------------------------------------------------
/* リセットパスワード用のショートコード
/*------------------------------------------------*/

// ---------- パスワード変更用のパラメーターURL ----------
if (!function_exists('url_to_send')) {
  function url_to_send()
  {
    $url_param = '?login=' . $_SESSION['user_login'] . '&key=' . $_SESSION['param_id'];

    $url_to_send = "<a href=" . home_url() . '/reset-password' . $url_param . ">" . home_url() . '/reset-password' . $url_param . "</a>";
    // $url_to_send = home_url() . '/reset-password' . $url_param;

    if (is_single()) {
      // ---------- 管理画面内 メール編集画面だった場合 ----------
      return '<a href=' . home_url() . '/reset-password?login=test&key=105479297>' . home_url() . '/reset-password?login=test&key=105479297</a>';
      // return   home_url() . '/reset-password?login=test&key=105479297';
    } elseif (is_page("forgot-password")) {
      // ---------- パスワードを忘れた場合の画面だった場合 ----------
      return $url_to_send;
    }
  }
}
add_shortcode('url_to_send', 'url_to_send');
