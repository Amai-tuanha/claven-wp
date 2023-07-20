<?php
include(get_theme_file_path() . "/App/Manual/model/include-manual-contact.php");
?>

<?php
/**
 * Template Name: システム問い合わせページ
 *
 * @Template Post Type: post, page,
 *
 */


get_header(); ?>

<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<?php if (current_user_can('administrator')) { ?>
  <link rel="stylesheet" href="<?= rampup_css_path('page-manual.css') ?>">
  <link rel="stylesheet" href="<?= rampup_css_path('page-manual-contact.css') ?>">
  <section class="manual">
    <div class="inner">
      <form method="POST" action="<?php get_current_link();?>" id="reserveForm" class="formTemplate">

        <input type="hidden" name="manual-contact">

        <input type="hidden" name="token" value="<?= $token ?>">

        <div class="-tac">
          <h2 class="formTemplate__heading">システムのお問い合わせ</h2>
        </div>

        <div class="formTemplate__formContentWrapper -mt0 -plr8">
          <div class="formTemplate__inputBox">
            <label class="formTemplate__inputLable" for="name">名前<span class="-error">*</span></label>
            <div class="formTemplate__inputItem">
              <input class="formTemplate__input" type="text" name="input_text" id="name" value="">
            </div>
          </div>


          <div class="formTemplate__inputBox">
            <label class="formTemplate__inputLable" for="email">メールアドレス<span class="-error">*</span></label>
            <div class="formTemplate__inputItem">
              <input class="formTemplate__input" type="email" name="input_email" id="email" value="">
            </div>
          </div>

          <div class="formTemplate__inputBox">
            <label class="formTemplate__inputLable" for="tel">電話番号<span class="-error">*</span></label>
            <div class="formTemplate__inputItem">
              <input class="formTemplate__input" type="tel" name="input_tel" id="tel" value="">
            </div>
          </div>

          <div class="formTemplate__inputBox">
            <label class="formTemplate__textareaLable" for="textarea">お問い合わせ内容<span class="-error">*</span></label>
            <div class="formTemplate__inputItem">
              <textarea class="formTemplate__textarea" name="input_textarea" id="textarea" value="" cols="4"></textarea>
            </div>
          </div>
          <?php if ($isValidateError) : ?>
          <ul>
            <?php foreach ($validateErrors as $value) : ?>
              <li>
                <?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
          <div class="formTemplate__cta">
            <a class="cta__button" href="javascript:history.back()" style="margin-right:2rem;">戻る</a>
            <button class="cta__button" type="submit">送信する</button>
          </div>
        </div>
        
        <!-- <a class="contact-backlink" href="javascript:history.back()">戻る</a> -->
      </form>
    </div>
  </section>
<?php } else {
  wp_safe_redirect(home_url());
} ?>
<!--end content-->
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
