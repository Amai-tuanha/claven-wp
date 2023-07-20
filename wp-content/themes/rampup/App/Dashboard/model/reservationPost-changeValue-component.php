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

$referer = wp_get_referer();

$post_id = $_GET['post_id'];
$class_post = new Rampup_Post($post_id);

// ---------- GoogoleCalrendar ----------
require_once get_template_directory() . '/App/GoogleService/GoogleCalendar.php';
require_once get_template_directory() . '/App/GoogleService/GoogleSheet.php';


if (isset($_POST["singelReservationFormTrigger"])) {

    // ---------- 変数定義 ----------
    $post_id = $_POST["post_id"];
    $user_id = $_POST["user_id"];
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

    /*--------------------------------------------------
    /* マイページからのキャンセルの場合
    /*------------------------------------------------*/
    if (isset($_POST["mypageReservationCancel"])) {
        $cancelReason = $_POST['cancel_reason'];

        $_SESSION['mypage_reservation'] = $_POST['mypage_reservation'];

        // ---------- メール送信 (キャンセルメール) ----------
        $emailPost_id = slug_to_post_id('email-mypage-cancel');
        my_PHPmailer_function(
            do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
            do_shortcode(get_post_field('post_content', $emailPost_id)),
            $class_user->user_email,
            null,
            null
        );

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
        $reservation_date_cancelled = get_post_meta($post_id, "reservation_date", true);
        update_post_meta($post_id, 'cancel_reason', $cancelReason);
        update_post_meta($post_id, 'reservation_date_cancelled', $reservation_date_cancelled);

        // ---------- メタデータを削除 ----------
        delete_post_meta($post_id, 'reservation_date');
        delete_post_meta($post_id, 'reservation_meet_url');
        delete_post_meta($post_id, 'reservation_meet_id');

        // ---------- 直近の面談予定日を取得 ----------
        $user_reservation_date = find_closest_reservation_date($user_id);
        // ---------- ユーザーステータス変更 ----------
        if (
            $user_status == 'before_contract' // 契約前
            // $user_status == 'application'
        ) {
            if (!$user_reservation_date) {
                update_user_meta($user_id, 'user_status', 'cancelled');
            }
        }

        // ---------- リダイレクト ----------
        wp_safe_redirect("/mypage-change-reservation/?form=sent");
        exit;
    }

    foreach ($_POST as $_POST_key => $_POST_value) {
        // ---------- $_POSTの中で「reservation_」が ----------
        // ---------- 含まれているもの全てを投稿メタに保存 ----------
        if (strpos($_POST_key, 'reservation_') !== false) {
            update_post_meta($post_id, $_POST_key, $_POST_value);
        }
    }

    /*--------------------------------------------------
    /* リダイレクト
    /*------------------------------------------------*/
    wp_safe_redirect(get_author_posts_url($class_user->user_id) . "/?googleCalendar=true");
    exit;
}

if ($_GET["googleCalendar"] == "true") {
    $_POST["singelReservationFormTrigger"] = 'true';
    session_start();
    $post_id = $_SESSION['post_id'];
    $class_post = new Rampup_Post($post_id);
    $class_user = new Rampup_User($class_post->reservation_user_id);

    // ---------- リマインダーをリセットする ----------
    reset_reminder($post_id);

    // ---------- インスタンス呼び出し ----------
    $client = new GoogleCalendar;

    // ---------- 新規作成の場合 ----------
    if (!$reservation_meet_id) {
        $meet_info = $client->addCalendar(
            $user_displayName,
            $user_email,
            $user_tel,
            null,
            $reservation_date,
            $post_id
        );

        $reservation_meet_url = $meet_info[0];
        $reservation_meet_id = $meet_info[1];

        // ---------- 投稿に登録 ----------
        update_post_meta($post_id, "reservation_meet_url", $reservation_meet_url);
        update_post_meta($post_id, "reservation_meet_id", $reservation_meet_id);
    }
    // ---------- 日程変更の場合 ----------
    else {
        $meet_info = $client->updateCalendar(
            $reservation_meet_id,
            $user_displayName,
            $user_email,
            $user_tel,
            null,
            $reservation_date,
            $post_id
        );

        $reservation_meet_url = get_post_meta($post_id, 'reservation_meet_url', true);
    }

    // ---------- 投稿をアップデート ----------
    $my_post = array(
        'ID' => $post_id,
        'post_title' => $class_user->user_displayName . "様の$reservation_title",
    );
    wp_update_post($my_post);

    // ---------- 日程変更のメール送る ----------
    $emailPost_id = slug_to_post_id('email-mypage-change-schedule');
    my_PHPmailer_function(
        do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
        do_shortcode(get_post_field('post_content', $emailPost_id)),
        $user_email,
        $reservation_meet_url,
        null
    );

    // if ($_GET["send-email"] == "true") {
    //     $user_id = get_post_meta($post_id, "reservation_user_id", true);
    //     $user_info_array = get_user_by('id', $user_id);
    //     $user_email = $user_info_array->user_email;

    //     $reservation_email_slug = get_post_meta($post_id, "reservation_email_slug", true);
    //     $reservation_meet_url = get_post_meta($post_id, "reservation_meet_url", true);

    //     // ---------- メール送信 ----------
    //     $emailPost_id = slug_to_post_id($reservation_email_slug);
    //     my_PHPmailer_function(
    //         do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
    //         do_shortcode(get_post_field('post_content', $emailPost_id)),
    //         $user_email,
    //         $reservation_meet_url,
    //         null
    //     );
    // }

    session_unset();
    wp_safe_redirect(get_permalink() . "/?form=sent");
}