<?php include(get_theme_file_path() . "/App/Payment/model/include-bank.php"); ?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?= rampup_css_path('page-cardPayment.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<?php
// ---------- マイページヘッダー読み込み ----------
include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php"); ?>

<section class="-mb8" style="margin-top:8rem;">
  <div class="inner">
    <ol class="step -mb8">
      <li class="userPolicy">利用規約</li>
      <li class="is-current">決済</li>
    </ol>
    <h2 class="payment__title"><?= $site_course_name ?>｜お申し込み</h2>
    <br><br>
    <div class="formTemplate__formContentWrapper" style="background-color: white; padding:6rem 12rem 6rem; border-radius: 10px; box-shadow: 0 0 6px #c6c6c6;"  >
      <h3 class="payment__minTitle">銀行振込決済画面</h3>
      <br>

      <p class="payment__text">下記のフォームに必要事項およびクレジットカード情報を記入して、決済ボタンをクリックしてください。 <br>
        決済が完了しましたら、担当者が確認の上ご連絡いたします。万が一、決済がうまくいかない、担当者からの連絡が無い場合はお手数ですがお電話（<a href="tel:03-6665-0884">03-6665-0884</a>）にてご連絡ください。</p>
      <br>
      <br>
      <p class="payment__text">■振込口座</p>
      <p class="payment__text" style="white-space:pre-wrap;"><?= $site_bank_data ?></p>
      <br>
      <p class="payment__text">■支払い金額の内訳</p>
      <p class="payment__text"><?= $first_payment ?></p>
      <p class="payment__text"><?= $other_payment ?></p>
      <br>
      <!-- <p class="payment__text">期限：<?= $response_deadline_formatted ?></p> -->
      <br>
      <p class="payment__text">メールにも同様の記載がございますので、こちらの画面まで行きましたらウィンドウを閉じても問題ありません。</p>
    </div>
</section>






<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
