<?php get_header(); ?>
<link rel="stylesheet" href="<?= rampup_css_path('page-cardPayment.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('form-common.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">

<?php
global
  $site_course_name;
// ---------- マイページヘッダー読み込み ----------
include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php"); ?>

<section class="payment">
  <div class="inner">
    <ol class="step -mb8 -mt0">
      <li>利用規約</li>
      <li class="is-current">決済</li>
      <li>完了</li>
    </ol>
    <?php
    $user_id_GET = $_GET['user_id'];
    $user_login_GET = $_GET['user_login'];
    if (Rampup_User_match($user_id_GET, $user_login_GET)) { ?>


      <h2 class="payment__title"><?= $site_course_name ?>｜お申し込み</h2>
      <br><br>

      <h3 class="payment__minTitle">クレジットカード決済画面</h3>
      <br>

      <p class="payment__text">下記のフォームに必要事項およびクレジットカード情報を記入して、決済ボタンをクリックしてください。 <br>
        決済が完了しましたら、担当者が確認の上ご連絡いたします。万が一、決済がうまくいかない、担当者からの連絡が無い場合はお手数ですがお電話（<a href="tel:03-6665-0884">03-6665-0884</a>）にてご連絡ください。</p>
      <br>
      <br>

    <?php
      $user_id = $user_id_GET;
      $class_user = new Rampup_User($user_id);

      // ---------- ストライプフォーム呼び出し ----------
      echo '<h3 class="payment__minTitle" style="margin-bottom:1em;"><span class="plan__name"></span></h3>';
      echo do_shortcode('[fullstripe_form name="' . $class_user->user_paymentPlan . '" type="inline_subscription"]');
    } else { ?>
      <h2 class="payment__title">このURLは無効です。<br>
        メール添付のURLから再度ログインしてください。
      </h2>
      <br><br>
    <?php } ?>
  </div>
</section>
<script>
  $('.wpfs-form').removeClass('wpfs-w-60');

  // ---------- 特定の値に事前入力 ----------
  $('input[name="wpfs-custom-input[]"]').val('<?= $class_user->user_login ?>').attr('readonly', '').addClass('-inputHidden');
  $('input[name="wpfs-custom-input[]"]').parent().hide();
  $('input[name="wpfs-card-holder-email"]').val('<?php echo $class_user->user_email ?>').attr('readonly', '').addClass('-inputHidden');


  planName = $('.wpfs-subscription-plan-hidden[name="wpfs-plan"]').attr('data-wpfs-plan-name');

  $('.plan__name').text(planName);

  $('.-inputHidden').parent().css({
    // 'visibility' : 'hidden',
    // 'height' : '0',
    // 'padding' : '0',
    // 'margin' : '0',
  })
</script>
<?php include get_theme_file_path() . "/footer-default.php" ?>
<?php get_footer();
