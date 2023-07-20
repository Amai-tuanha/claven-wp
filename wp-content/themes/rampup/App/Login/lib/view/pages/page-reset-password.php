<?php
// use Carbon\Carbon;
//  do_action('page_reset_password__send_form'); 
include(get_theme_file_path() . "/App/Login/model/include-reset-password.php");
 ?>
<?php

/**
 * Template Name: パスワードリセット
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>

<div class="inner">
  <?php if (
    isset($_GET) &&
    $user_resetPassword_key &&
    $_GET['key'] == $user_resetPassword_key
  ) {
  ?>
  <form method="POST"
        action=""
        id="reserveForm"
        class="formTemplate">

    <input type="hidden"
           name="resetPassword"
           value="<?php echo get_user_by('login', $_GET['login'])->user_email; ?>">

    <div class="-tac">
      <h2 class="formTemplate__heading">パスワードリセット</h2>
    </div>
    <?php if (isset($error_text)) {
        echo $error_text;
      }
      ?>

    <div class="formTemplate__inputBox">
      <label class="formTemplate__inputLable"
             for="user_displayName">新しいパスワード<span class="-error">*</span></label>
      <div class="formTemplate__inputItem">
        <input class="formTemplate__input"
               type="password"
               name="new_password"
               id="user_displayName"
               value="">
      </div>
    </div>


    <div class="formTemplate__cta">
      <button class="cta__button"
              type="submit"
              onclick="disableButton(event)">変更する</button>
    </div>

    <!-- <a class="contact-backlink" href="javascript:history.back()" style="">戻る</a> -->
  </form>

  <?php } else { ?>
  <form id="reserveForm"
        class="formTemplate">
    <div class="-tac">
      <h2 class="formTemplate__heading">エラー</h2>
    </div>
    <div class="formTemplate__cta">
      <p class="formTemplate__send">無効なページです。<br>最初からやりなおしてください。</p>
    </div>
  </form>

  <?php } ?>

</div>


<!--end content-->
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
