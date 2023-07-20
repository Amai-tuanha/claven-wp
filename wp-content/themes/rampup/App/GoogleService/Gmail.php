<?php
/*--------------------------------------------------
/* このクラスは現在(2022/01/31)使っていません
/* 使う可能性があるので残して置いてます
/*------------------------------------------------*/

// require __DIR__ . '/../vendor/autoload.php';
require_once(get_theme_file_path() . "/App/vendor/autoload.php");
define('CLIENT_SECRET_PATH', __DIR__ . '/SecretKey.json');

use Carbon\Carbon;

class Gmail
{
    private $client;
    private $service;

    public function __construct()
    {
        $instance = new Google_Client();
        $instance->setApplicationName('Gmail API PHP Quickstart');
        $instance->setScopes(Google_Service_Gmail::MAIL_GOOGLE_COM);
        $instance->setAuthConfig(CLIENT_SECRET_PATH);
        $instance->setSubject("info@clane.co.jp");
        $this->client = $instance;
        $this->service = new Google_Service_Gmail($this->client);
        // 各メールサーバー設定
        $this->$mail = new PHPMailer();
        $this->$mail->CharSet = "iso-2022-jp";
        $this->$mail->CharSet = "utf-8";
        $this->$mail->Encoding = "7bit";
        $this->$mail->Encoding = "base64";
        $this->$mail->CharSet = "UTF-8";
        $this->$mail->From = 'info@gmail.com';
        $this->$mail->FromName = "CLANE インターンシップ講座";
        $this->$mail->AddCC('admin@hogehoge.net', 'Admin');
        $this->$mail->AddReplyTo('h.mitsuya@clane.co.jp', "三矢宏貴");
        $this->$mail->isHTML(true);
        $this->$mail->isSMTP();
        $this->$mail->preSend();
    }

    public function sendMessage($to, $subject, $content)
    {
        $this->$mail->AddAddress($to);
        $this->$mail->Subject = $subject;
        $this->$mail->Body = $content;
        $mime = $this->$mail->getSentMIMEMessage();
        $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
        $mensaje = new Google_Service_Gmail_Message();
        $mensaje->setRaw($mime);
        $this->service->users_messages->send("me", $mensaje);
    }
}