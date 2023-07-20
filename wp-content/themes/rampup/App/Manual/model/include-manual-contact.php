<?php

//任意入力項目の配列が空の場合のエラーメッセージ制御
error_reporting(E_ALL ^ E_NOTICE);

//サーバーサイドバリデーション
$postData = $_POST;
$isValidateError = true;
$validateErrors = array();
if(isset($_POST['manual-contact'])){


function formValidation($postData)
{
  //エラーメッセージ初期化
  $validateErrors = array();

  //必須項目チェック対象指定
  $requiredCheck = array(
    '全角テキスト' => $postData['input_text'],
    // 'カタカナ' => $postData['input_kana'],
    'メールアドレス' => $postData['input_email'],
    '電話番号' => $postData['input_tel'],
    // 'URL' => $postData['input_url'],
    // '郵便番号' => $postData['input_zipcode'],
    // 'ラジオボタン' => $postData['input_radio'],
    // 'チェックボックス' => $postData['input_checkbox'],
    // 'セレクトボックス' => $postData['input_selectbox'],
    'お問い合わせ内容' => $postData['input_textarea']
  );

  //全角文字チェック対象指定
  $doubleByteCheck = array(
    'お名前' => $postData['input_text']
  );

  //全角カタカナチェック対象指定
  // $doubleByteKanaCheck = array(
  //   'カタカナ' => $postData['input_kana']
  // );

  //メールアドレスフォーマットチェック対象指定
  $emailFormatCheck = array(
    'メールアドレス' => $postData['input_email']
  );

  //電話・FAX番号フォーマットチェック対象指定
  $telFormatCheck = array(
    '電話番号' => $postData['input_tel']
  );

  // //URLフォーマットチェック対象指定
  // $urlFormatCheck = array(
  //   'URL' => $postData['input_url']
  // );

  // //郵便番号フォーマットチェック対象指定
  // $zipcodeFormatCheck = array(
  //   '郵便番号' => $postData['input_zipcode']
  // );

  //必須項目バリデートチェック
  foreach ($requiredCheck as $key => $value) {
    if (empty($value)) {
      $validateErrors[] = $key . 'の項目は必須入力です';
    }
  }

  //全角文字バリデートチェック
  // foreach ($doubleByteCheck as $key => $value) {
  //   if (!preg_match('/^[ぁ-んァ-ヶー一-龠 　rnt]+$/', $value)) {
  //     $validateErrors[] = $key . 'の項目はすべて全角文字で入力してください';
  //   }
  // }

  // //全角カタカナ文字バリデートチェック
  // foreach ($doubleByteKanaCheck as $key => $value) {
  //   if(!preg_match('/^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/',$value)) {
  //     $validateErrors[] = $key.'の項目はすべて全角カタカナで入力してください';
  //   }
  // }

  //メールアドレスバリデートチェック
  foreach ($emailFormatCheck as $key => $value) {
    if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/', $value) && array_search('メールアドレスの項目は必須入力です', $validateErrors, false) === false) {
      
      $validateErrors[] = $key . 'を正しく入力してください';
    }
  }

  //電話番号バリデートチェック
  // foreach ($telFormatCheck as $key => $value) {
    // if (!preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/', $value) && array_search('電話番号の項目は必須入力です', $validateErrors, false) === false) {
    //   $validateErrors[] = $key . 'を正しく入力してください';
    // }
  // }

  // //URLバリデートチェック
  // foreach ($urlFormatCheck as $key => $value) {
  //   if(!preg_match('/http(s)?://([w-]+.)+[w-]+(/[w-./?%&=]*)?/',$value)) {
  //     $validateErrors[] = $key.'を正しく入力してください';
  //   }
  // }

  // //郵便番号バリデートチェック
  // foreach ($zipcodeFormatCheck as $key => $value) {
  //   if(!preg_match('/^d{3}[-]d{4}$|^d{7}$/',$value)) {
  //     $validateErrors[] = $key.'を正しく入力してください';
  //   }
  // }

  return $validateErrors;
}
$validateErrors = formValidation($postData);

if (empty($validateErrors)) {
  $input_name = esc_attr($postData['input_text']);
  $input_email = esc_attr($postData['input_email']);
  $input_tel = esc_attr($postData['input_tel']);
  $input_textarea = esc_attr($postData['input_textarea']);
  $subject = 'システムについてのお問い合わせ';
  $admin_email = 'k.tanba@clane.co.jp';
  // $admin_email = 'schoollp.3z4cnb@zapiermail.com';
  global $site_title;
  $site_url = get_site_url();

  $email_customer_content = <<<EOT
  [div]
  RAMPUPのご利用ありがとうございます。
  下記内容でお問い合わせ承りました。
  
  お名前：$input_name
  メールアドレス：$input_email
  電話番号：$input_tel
  お問い合わせ内容：$input_textarea

  2稼働日以内に担当者よりご返事いたしますので、もうしばらくお待ちください。

  **************************************************************
  株式会社CLANE
    
  Address：〒102-0074 東京都千代田区九段南2丁目7-1喜京家ビル3F
  Corporate site：https://clane-intern.com
  Mail：info@clane.co.jp
  **************************************************************
  [/div]
  EOT;

  $email_admin_content = <<<EOT
  [div]
  @channel\n
  RAMPUPのシステムについて、下記内容の問い合わせがあります。\n\n

  サイトタイトル：$site_title\n
  サイトURL：$site_url\n
  お名前：$input_name\n
  メールアドレス：$input_email\n
  電話番号：$input_tel\n
  お問い合わせ内容：$input_textarea\n\n

  [/div]
  EOT;

  // お客さん用返信メール
  my_PHPmailer_function(
    do_shortcode($subject),
    do_shortcode($email_customer_content),
    $input_email,
    null,
    null
  );

  // 問い合わせ用メール
  my_PHPmailer_function(
    do_shortcode($subject),
    do_shortcode($email_admin_content),
    $admin_email,
    null,
    null
  );
  wp_safe_redirect(get_current_link());
  exit;

} else {
  $isValidateError = true;
}
}