<?php

use Carbon\Carbon;
// ---------- グローバル変数 ----------
global
    $post,
    $reservation_type_translation_array,
    $reservation_reminder_emails_preset_array,
    $user_status_translation_array,
    $user_paymentMethod_array,
    $user_numberOfDivision_array,
    $user_paymentPlan_array;

// ---------- GoogoleCalrendar ----------
require_once get_template_directory() . '/App/GoogleService/GoogleCalendar.php';
require_once get_template_directory() . '/App/GoogleService/GoogleSheet.php';

$post_id = $_GET["post_id"];
$user_id = $_GET["user_id"];
$class_post = new Rampup_Post($post_id);
$class_user = new Rampup_User($user_id);


/*--------------------------------------------------
    /* マイページからのキャンセルの場合
    /*------------------------------------------------*/
if (isset($_POST["mypageReservationCancel"])) {
    // ---------- 変数定義 ----------
    $post_id = $_POST["post_id"];
    $user_id = $_POST["user_id"];
    $emailCheck = $_POST["emailCheck"];
    $wp_get_referer = $_POST["wp_get_referer"];
    $cancelReason = $_POST['cancel_reason'];
    $class_post = new Rampup_Post($post_id);
    $class_user = new Rampup_User($user_id);
    $reservation_type = get_post_meta($post_id, "reservation_type", true);

    // ---------- ユーザーの変数 ----------
    $user_info_array = get_user_by('id', $user_id);
    $user_displayName = $user_info_array->display_name;
    $user_tel = get_user_meta($user_id, "user_tel", true);
    $user_status = get_user_meta($user_id, "user_status", true);

    // ---------- 投稿の変数 ----------
    $reservation_date = $_POST["reservation_date"];
    $post_stauts = get_post_status($post_id);
    $reservation_title = $reservation_type_translation_array[$reservation_type];

    // ---------- セッション ----------
    session_start();
    $_SESSION['post_status'] = $post_status;
    $_SESSION['reservation_date'] = $reservation_date;
    $_SESSION['post_id'] = $post_id;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_displayName'] = $user_displayName;
    $_SESSION['user_email'] = $class_user->user_email;
    $_SESSION['user_tel'] = $user_tel;
    $_SESSION['reservation_title'] = $reservation_title;
    $_SESSION['mypage_reservation'] = $_POST['mypage_reservation'];

    // ---------- メール送信 (キャンセルメール) ----------
    if (isset($emailCheck)) {
        $emailPost_id = slug_to_post_id('email-mypage-cancel');
        my_PHPmailer_function(
            do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
            do_shortcode(get_post_field('post_content', $emailPost_id)),
            $class_user->user_email,
            null,
            null
        );
    }

    // ---------- 投稿をステータスをキャンセルに変更 ----------
    $my_post = array(
        'ID' => $post_id,
        'post_type' => 'reservation',
        'post_status' => 'cancelled',
    );
    wp_update_post($my_post);

    // ---------- カレンダー削除 ----------
    $reservation_meet_id = get_post_meta($post_id, "reservation_meet_id", true);
    $client = new GoogleCalendar;
    $client->deleteCalendar($reservation_meet_id);

    // ---------- 投稿のメタデータ追加 ----------
    update_post_meta($post_id, 'cancel_reason', $cancelReason);
    // $reservation_date_cancelled = get_post_meta($post_id, "reservation_date", true);
    // update_post_meta($post_id, 'reservation_date_cancelled', $reservation_date_cancelled);

    // ---------- メタデータを削除 ----------
    delete_post_meta($post_id, 'reservation_date');
    delete_post_meta($post_id, 'reservation_meet_url');
    delete_post_meta($post_id, 'reservation_meet_id');

    // ---------- 直近の面談予定日を取得 ----------
    $user_reservation_date = find_closest_reservation_date($user_id);

    // ---------- ユーザーステータス変更 ----------
    if (
        $user_status == 'before_contract' || // 契約前
        $user_status == 'application'
    ) {
        if (!$user_reservation_date) {
            update_user_meta($user_id, 'user_status', 'cancelled');
        }
    }

    // ---------- リダイレクト ----------
    wp_safe_redirect($wp_get_referer);
    exit;
}