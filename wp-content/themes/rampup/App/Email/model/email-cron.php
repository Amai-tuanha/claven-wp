<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);

require_once $path . 'wp-load.php';

global $reservation_post_active_status_array;

use Carbon\Carbon;

// // ---------- テスト送信 (消さない) ----------
// my_PHPmailer_function(
//     'クロンテスト件名',
//     'クロンテスト本文',
//     'kawakatsu@clane.co.jp'
// );

// $today = new Carbon('today');
$today_9am = Carbon::today()->format('Y-m-d 9:00:00');
$today = new Carbon($today_9am);
$now = new Carbon('now');
// ---------- 面談日程 ----------
$i = 0;


$mypage_reservation_args = array(
    'post_type' => 'reservation',
    'post_status' => ['scheduled', 'published'],
    // 'post_status' => 'any',
    'order' => 'ASC',
    'orderby' => 'meta_value',
    'meta_key' => 'reservation_date',
    'posts_per_page' => -1,
);
$WP_post = new WP_Query($mypage_reservation_args);
if ($WP_post->have_posts()) {
    while ($WP_post->have_posts()) {
        $i++;
        $WP_post->the_post();

        global $reminder_post_id, $reminder_user_displayName;


        // ---------- 投稿の情報 ----------
        $post_id = get_the_ID();
        $reminder_post_id = $post_id;
        $reservation_user_id = get_post_meta($post_id, "reservation_user_id", true);
        $reservation_date = get_post_meta($post_id, "reservation_date", true); // ---------- こいつのせいで誤作動する。 ----------
        $reservation_meet_url = get_post_meta($post_id, "reservation_meet_url", true);
        $reservation_date_carbon = new carbon($reservation_date);
        $reservation_reminder_emails_preset = get_post_meta($post_id, 'reservation_reminder_emails_preset', true);
        $reservation_date_diff = $reservation_date_carbon->diffInDays($today);


        // ---------- リマインダー ----------
        $reminder_before_contract_2day = get_post_meta($post_id, 'reminder_before_contract_2day', true);
        $reminder_before_contract_dday = get_post_meta($post_id, 'reminder_before_contract_dday', true);
        $reminder_after_contract_1day = get_post_meta($post_id, 'reminder_after_contract_1day', true);

        // ---------- ユーザーの情報 ----------
        $user_id = get_post_meta($post_id, "reservation_user_id", true);
        $user_info_array = get_user_by('id', $user_id);
        $user_email = $user_info_array->user_email;
        $reminder_user_displayName = $user_info_array->display_name;

        if (
            $now > $today // 朝九時以降に送信
        ) {
            // ---------- 契約前、二日前リマインダー ----------
            if (
                $reservation_date_diff == 2 &&
                !$reminder_before_contract_2day &&
                $reservation_reminder_emails_preset == 'before_contract'
            ) {
                // // ---------- テスト送信 (消さない) ----------
                // my_PHPmailer_function(
                //     'クロンテスト件名',
                //     'クロンテスト本文',
                //     'kawakatsu@clane.co.jp'
                // );
                $emailPost_id = slug_to_post_id("email-before-contract-reminder2days");
                my_PHPmailer_function(
                    do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
                    do_shortcode(get_post_field('post_content', $emailPost_id)),
                    $user_email,
                    $reservation_meet_url,
                    null
                );


                update_post_meta($post_id, 'reminder_before_contract_2day', 'sent');
            }
            // ---------- 契約前、当日リマインダー ----------
            elseif (
                $reservation_date_diff == 0 &&
                !$reminder_before_contract_dday &&
                $reservation_reminder_emails_preset == 'before_contract'
            ) {
                $emailPost_id = slug_to_post_id("email-before-contract-reminderdday");
                my_PHPmailer_function(
                    do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
                    do_shortcode(get_post_field('post_content', $emailPost_id)),
                    $user_email,
                    $reservation_meet_url,
                    null
                );

                update_post_meta($post_id, 'reminder_before_contract_dday', 'sent');
            }
            // ---------- 契約後、前日リマインダー ----------
            elseif (
                $reservation_date_diff == 1 &&
                !$reminder_after_contract_1day &&
                $reservation_reminder_emails_preset == 'after_contract'
            ) {
                $emailPost_id = slug_to_post_id("email-after-contract-reminder1day");
                my_PHPmailer_function(
                    do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
                    do_shortcode(get_post_field('post_content', $emailPost_id)),
                    $user_email,
                    $reservation_meet_url,
                    null
                );

                update_post_meta($post_id, 'reminder_after_contract_1day', 'sent');
            }
        }
    }
}
wp_reset_postdata();