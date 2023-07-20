<?php
// include(get_theme_file_path() . "/App/Login/model/include-login.php");
?>

<?php
/**
 * Template Name: マニュアルページ
 *
 * @Template Post Type: post, page,
 *
 */


get_header(); ?>
<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<?php if (current_user_can('administrator')) { ?>
  <link rel="stylesheet" href="<?= rampup_css_path('page-manual.css') ?>">
  <section class="manual">
    <div class="inner">
      <h2 class="manual__title">説明マニュアル</h2>
      <div class="manual__content">
        <h3 class="manual__subTitle">お客様フロー</h3>
        <h4 class="manual__index">お申し込み〜決済完了</h4>
        <video src="<?= rampup_video_path('customer_userflow.mp4') ?>" controls class="manual__video"></video>
        <p class="manual__text">お客様のお申し込みから決済を完了するまでの一連のフローについて説明しています。</p>
      </div>
      <div class="manual__content">
        <h3 class="manual__subTitle">運営者フロー</h3>
        <h4 class="manual__index">面談開始まで</h4>
        <video src="<?= rampup_video_path('admin_user_before_meeting.mp4') ?>" controls class="manual__video"></video>
        <p class="manual__text">運営者で行う面談前のフローについて説明しています。</p>
      </div>
      <div class="manual__content">
        <h4 class="manual__index">面談終了後</h4>
        <video src="<?= rampup_video_path('admin_aftermeeting_flow.mp4') ?>" controls class="manual__video"></video>
        <p class="manual__text">面談終了後に行える、「再面談予約、決済情報の送信方法」のフローについて説明しています。</p>
      </div>
      <div class="manual__content">
        <h4 class="manual__index">決済後</h4>
        <video src="<?= rampup_video_path('admin_after_payment.mp4') ?>" controls class="manual__video"></video>
        <p class="manual__text">銀行決済のお客様がコンテンツ閲覧するためのフローについて説明しています。</p>
      </div>
    </div>
  </section>
<?php } else {
  wp_safe_redirect(home_url());
} ?>

<!--end content-->
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
