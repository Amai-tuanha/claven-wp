<?php
//  use Carbon\Carbon;
//  do_action("page_login__send_form");
include(get_theme_file_path() . "/App/Login/model/include-login.php");
?>

<?php
/**
 * Template Name: ログインページ
 *
 * @Template Post Type: post, page,
 *
 */

get_header(); ?>
<link rel="stylesheet" href="<?= rampup_css_path('form-common.css') ?> ">

<div class="inner">
  <?php if (is_user_logged_in()) { ?>
    <form method="POST" action="" id="reserveForm" class="formTemplate">

      <input type="hidden" name="userLoginForm">

      <div class="-tac">
        <h2 class="formTemplate__heading">ログアウト画面</h2>
      </div>
      <?php if (isset($error_text)) { ?>
        <div class="-tac"><?= $error_text ?></div><br>
      <?php } ?>


      <div class="formTemplate__cta">
        <a class="cta__button" onclick="disableButton(event)" href="<?= wp_logout_url('/login') ?>">ログアウト</a>
      </div>

      <!-- <a class="contact-backlink" href="javascript:history.back()" style="">戻る</a> -->
    </form>
  <?php } else { ?>

    <form method="POST" action="" id="reserveForm" class="formTemplate">

      <input type="hidden" name="userLoginForm">

      <div class="-tac">
        <!-- <h2 class="formTemplate__heading">ログイン画面</h2> -->
        <h2 class="formTemplate__heading -login__logo"><img src="<?php echo get_template_directory_uri() ?>/assets/img/logo-black.svg" alt="<?= $site_title ?>"></h2>
      </div>
      <?php if (isset($error_text)) { ?>
        <div class="-tac"><?= $error_text ?></div><br>
      <?php } ?>
      <div class="formTemplate__formContentWrapper -login">
        <div class="formTemplate__inputBox" style="margin-bottom:2.8rem;">
          <label class="formTemplate__inputLable" for="user_displayName">ユーザー名<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <input class="formTemplate__input" type="text" name="user_login" id="user_displayName" value="">
          </div>
        </div>

        <div class="formTemplate__inputBox" style="margin-bottom:2.8rem;">
          <label class="formTemplate__inputLable" for="user_displayName">パスワード<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <input class="formTemplate__input" type="password" name="user_password" id="user_displayName" value="">
          </div>
        </div>


        <div class="formTemplate__cta -first">
          <button class="cta__button" type="submit" onclick="disableButton(event)" style="margin-bottom:2.8rem;">ログイン</button>
        </div>
        <div class="formTemplate__cta">
          <a class="linkText -center" href="<?= home_url() . '/forgot-password' ?>">パスワードをお忘れですか？</a>

        </div>
      </div>

      <!-- <a class="contact-backlink" href="javascript:history.back()" style="">戻る</a> -->
    </form>
  <?php } ?>
</div>


<!--end content-->
<?php get_footer();
