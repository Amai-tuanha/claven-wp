<?php

use Carbon\Carbon;

/*--------------------------------------------------
/* メールの関数
/*------------------------------------------------*/

mb_language("japanese");
mb_internal_encoding("utf-8");

// require '../../../App/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;

if (!function_exists('my_PHPmailer_function')) {
  function my_PHPmailer_function(
    $v_subject,
    $v_content,
    $v_email = null,
    $v_google_meet = null,
    $v_file = null,
    $v_CC = null
  ) {
    global
    $site_title,
    $site_gmail,
    $site_gmail_password,
    $site_mail_title,
    $site_mail_list,
    $site_client_id,
    $site_client_secret,
    $site_refresh_token;

    $mail = new PHPMailer(true);
    $CLIENT_ID = $site_client_id;
    $CLIENT_SECRET = $site_client_secret;
    $REFRESH_TOKEN = $site_refresh_token;
    $USER_NAME = $site_gmail;
    try {
      $user_info_array = get_user_by('email', $v_email);
      $user_displayName = $user_info_array->display_name;


      // ---------- 日本語用設定 ----------
      $mail->CharSet = "utf-8";
      $mail->Encoding = "base64";


      // ---------- メールサーバの設定 ----------
      // $mail->SMTPDebug = 2; //デバッグ用メッセージを吐き出す。
      $mail->isSMTP();
      $mail->SMTPAuth = true;
      // $mail->Username = $fromEmail;
      $mail->Password = $site_gmail_password; // jjjKsusa192F


      // ---------- 本番 ----------
      //Gmail 認証情報
      $host = 'smtp.gmail.com';// -- アイコンでなければrelay追加するかも --
      $mail->Host = $host;
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->AuthType = 'XOAUTH2';
      $provider = new Google(
        [
          'clientId' => $CLIENT_ID,
          'clientSecret' => $CLIENT_SECRET,
          ]
        );


      // ---------- 差出人 ----------
      $fromEmail = $site_gmail;
      $fromname = mb_encode_mimeheader("$site_mail_title");


      // ---------- 宛先 ----------
      $to =  $v_email;
      $user_displayName = $user_info_array->display_name;


      // ---------- 件名 ----------
      $subject = $v_subject;


      // ---------- 本文 ----------
      $mail_content = $v_content;
      // $mail_content = str_replace("[Google_Meet]", $v_google_meet, $mail_content);
      $mail_content = str_replace("[Google_Meet]", "<a href=\"$v_google_meet\">$v_google_meet</a>", $mail_content);
      // $mail_content = $mail_content;
      // $message = '<html><body style="white-space: pre-wrap;">';
      // $message .= $mail_content;
      // $message .= "</body></html>";
      $message = $mail_content;


      // ---------- メール設定 ----------
      $mail->isSMTP();
      $mail->SMTPAuth = true;


      // ---------- Pass the OAuth provider instance to PHPMailer ----------
      $mail->setOAuth(
        new OAuth(
          [
            'provider' => $provider,
            'clientId' => $CLIENT_ID,
            'clientSecret' => $CLIENT_SECRET,
            'refreshToken' => $REFRESH_TOKEN,
            'userName' => $USER_NAME,
          ]
        )
      );


      // ---------- 受信者設定 ----------
      $mail->setFrom($fromEmail, $fromname);
      $mail->addAddress($to, $user_displayName);
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $message;


      // ---------- 返信先を設定 ----------
      if ($site_mail_list) {
        if ($v_CC == null) {
          $mail->addReplyTo($site_mail_list, mb_encode_mimeheader("ReplyTo"));
          $mail->addCC($site_mail_list);
        }
      }

      // ---------- メール送信 ----------
      $mail->send();
      // echo '成功' . PHP_EOL;
      if (is_developer()) {
        // throw new Exception('Errorが発生しました。');
        ovd('sucsess');
    }
      return;
    } catch (Exception $e) {
      // echo '失敗: ', $mail->ErrorInfo.PHP_EOL;
      // $error_to="matsuoka1985@yahoo.co.jp";
      $site_url=get_site_url();
      // $error_to_clane= "schoollp.3z4cnb@zapiermail.com";
      $error_to_clane= "k.tanba@clane.co.jp";
      $error_subject_clane ="$site_title({$site_url}) でメールエラーが発生しています。";
      $error_message_clane=<<<EOT
      @channel
      $site_title でメール送信エラーが発生しています。
      EOT;
      mb_send_mail($error_to_clane, $error_subject_clane,$error_message_clane);

      $error_to_client="matsuoka1985@yahoo.co.jp";
      $error_subject_client="$site_title({$site_url}) でメールが上手く送られませんでした。" ;
      $error_message_client=<<<EOT
      $site_title でメールが上手く送られませんでした。
      schoollp.3z4cnb@zapiermail.com
      にお問い合わせください。
      EOT;
      mb_send_mail($error_to_client,$error_subject_client,$error_message_client);
      // wp_mail("matsuoka1985@yahoo.co.jp", "失敗しました", "エラーが起きました。");


      if (is_developer()) {
        ovd($e);
      }

      return false;
    }
  }
}

// ---------- ストライプ決済後 ----------
include(get_theme_file_path() . "/App/Email/model/include-email-payment-card-complete.php");


/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
include get_theme_file_path() . "/App/Email/model/email-shortcodes.php";
include get_theme_file_path() . "/App/Email/model/email-variables.php";
include get_theme_file_path() . "/App/Email/model/email-cron.php";
include get_theme_file_path() . "/App/Email/model/create-page.php";