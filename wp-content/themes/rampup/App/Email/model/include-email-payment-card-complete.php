<?php

use Carbon\Carbon;
/*--------------------------------------------------
/* ストライプ決済後のメール
/*------------------------------------------------*/

if (!function_exists('change_status_after_stripe')) {
    function change_status_after_stripe($params)
    {
        // ---------- グローバル関数 ----------
        global $site_title,
            $site_company_address,
            $site_mail_title,
            $company_site,
            $site_company_name,
            $site_gmail,
            $user_paymentPlan_array;

        // ---------- 共通の情報 ----------
        $login_link = home_url() . '/mypage/';

        // ---------- ユーザーの諸々の情報 ----------
        $user_email = $params['email'];
        $user_info_array = get_user_by('email', $user_email);
        $class_user = new Rampup_User($user_email, 'email');
        $user_id = $class_user->user_id;
        $person_in_charge = $class_user->user_personInCharge;
        $user_displayName = $class_user->user_displayName;
        $user_login = $class_user->user_login;
        $now = new Carbon('now');
        $now_formatted = $now->format('Y-m-d');
        $splitAmmount_total = num($class_user->splitAmmount_total);
        $setupFee = num($class_user->setupFee);
        $cancellationCount = $class_user->cancellationCount;

        // ---------- 投稿の情報 ----------
        $post_id = find_closest_reservation_date($user_id, 'id');
        $class_post = new Rampup_Post($post_id);
        $reservation_date = carbon_formatting($class_post->reservation_date);
        $reservation_meet_url = $class_post->reservation_meet_url;
        $paymentPlan_name = $class_user->user_paymentPlan_name;
        $total_price = num($class_user->setupFee + $class_user->splitAmmount_total);
        $reservation_personInCharge_user_id = $class_post->reservation_personInCharge_user_id;
        $co_admin = new Rampup_User($reservation_personInCharge_user_id);
        $person_in_charge = $co_admin->user_displayName;

        if ($cancellationCount == 1) {
            $first_payment = num($class_user->total_price) . '円';
            $other_payment = '';
        } else {
            $first_payment = '1回目: ' . num($class_user->first_payment) . '円';
            $other_payment = '2回目以降: ' . num($class_user->splitAmmount) . '円';
        }

        // ---------- 決済結果詳細 ----------
        //  $payment_detail = "◎分割 $class_user->cancellationCount 回
        //  $first_payment 円
        //  $other_payment 円
        // -----------------------------------------------------
        // お支払い金額合計（$total_price 円）
        //  受講料: $splitAmmount_total 円
        //  手数料: $setupFee 円";
        $payment_detail = "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
        $payment_detail .= "$paymentPlan_name\n";
        $payment_detail .= "支払い回数 : $cancellationCount 回\n";
        $payment_detail .= "\n";
        $payment_detail .= "支払い金額の内訳\n";
        $payment_detail .= "$first_payment\n";
        $payment_detail .= ($other_payment) ? "$other_payment\n" : '';
        $payment_detail .= "＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";

        // ---------- メール処理 ----------
        mb_language("ja");
        mb_internal_encoding("utf-8");

        $subject = "【 $site_title 】ご決済いただきありがとうございました";

        $mail_content  = "<div style='white-space : pre-wrap;'>";
        $mail_content .= "$user_displayName 様  \n";
        $mail_content .= " \n";
        $mail_content .= "お世話になっております、" . $site_mail_title . "の$person_in_charge" . "です。 \n";
        $mail_content .= " \n";
        $mail_content .= "下記の通り決済が完了しました。 \n";
        $mail_content .= "$payment_detail \n";
        $mail_content .= " \n";
        $mail_content .= "次はいよいよスタートミーティングの開始となります。 \n";
        $mail_content .= "以下の日時となります。 \n";
        $mail_content .= " \n";
        $mail_content .= "■予約日時 \n";
        $mail_content .= "$reservation_date \n";
        $mail_content .= " \n";
        $mail_content .= "お時間になりましたら、以下のリンクをクリックしてご接続ください。 \n";
        $mail_content .= "-------------------------------- \n";
        $mail_content .= "Google Meetsに参加する \n";
        $mail_content .= "$reservation_meet_url \n";
        $mail_content .= "-------------------------------- \n";
        $mail_content .= " \n";
        $mail_content .= "なお会員ページから、スタートミーティングの日程変更が可能です。 \n";
        $mail_content .= "また、チュートリアルから講座を開始できます。 \n";
        $mail_content .= " \n";
        $mail_content .= "■会員ページURL \n";
        // $mail_content .= "<a href=\"$login_link\">$login_link</a> \n";
        $mail_content .= "$login_link \n";
        $mail_content .= " \n";
        $mail_content .= "ID: $user_login \n";
        $mail_content .= "PW: $user_login \n";
        $mail_content .= " \n";
        $mail_content .= " \n";
        $mail_content .= "長い学習期間となりますが、$user_displayName 様の学習を徹底サポートいたしますので、 \n";
        $mail_content .= "よろしくお願いいたします。 \n";
        $mail_content .= " \n";
        $mail_content .= "またご質問・ご不明点等がありましたら、 \n";
        $mail_content .= "メール及び電話にてお気軽ご連絡ください。 \n";
        $mail_content .= " \n";
        $mail_content .= "************************************************************** \n";
        $mail_content .= "$site_title \n";
        $mail_content .= " \n";
        $mail_content .= "Address：$site_company_address \n";
        $mail_content .= "Corporate site：$company_site \n";
        $mail_content .= "Mail：$site_gmail \n";
        $mail_content .= "************************************************************** \n";
        $mail_content .= "</div>";

        my_PHPmailer_function(
            $subject,
            $mail_content,
            $user_email,
            null,
            null
        );

        // $emailPost_id = slug_to_post_id("email-payment-card-complete");
        // my_PHPmailer_function(
        //     do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
        //     // do_shortcode(get_post_field('post_content', $emailPost_id)),
        //     do_shortcode(get_post_field('post_content', $emailPost_id)),
        //     $user_email,
        //     $reservation_meet_url,
        //     null
        //   );

        // ---------- DBの値変更 ----------
        update_user_meta($user_id, "user_status", "paid");
        if (empty($class_user->user_contractDate)) {
            update_user_meta($user_id, 'user_contractDate', $now_formatted);
        }

        return true;
    }
}
add_action('fullstripe_after_subscription_charge', 'change_status_after_stripe', 10, 5);