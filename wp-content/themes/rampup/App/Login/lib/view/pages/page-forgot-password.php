<?php
include(get_theme_file_path() . "/App/Login/model/include-forgot-password.php");
?>

<?php

/**
 * Template Name: パスワードを忘れた方へ
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>




<div class="inner">
  <?php if ($_GET['form'] == 'sent') { ?>
    <form id="reserveForm" class="formTemplate">
      <div class="-tac">
        <h2 class="formTemplate__heading">送信完了</h2>
      </div>
      <div class="formTemplate__cta">
        <p class="formTemplate__send" style="line-height:3;" >メールを送信しました。<br>メールに添付されているURLをクリックしてパスワードリセットの手続きを行ってください。<br><a class="formTemplate__backToLogInButton" href="<?php echo home_url("/login/"); ?>" style="margin-top:5.4rem; display:inline-block;">ログイン画面へ戻る</a>
        </p>
      </div>
    </form>
  <?php } else { ?>

    <form method="POST" action="" id="reserveForm" class="formTemplate">

      <input type="hidden" name="forgotPassword">

      <div class="-tac">
        <h2 class="formTemplate__heading">パスワードを忘れた方へ</h2>
      </div>
      <div class="formTemplate__formContentWrapper">
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable" for="user_displayName">メールアドレス<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <input required class="formTemplate__input" type="email" name="user_email" value="" required>
          </div>
        </div>
        <?php if (isset($error_text)) { ?>
          <div class="-tac -error"><?= $error_text ?></div><br>
        <?php } ?>
        <div class="formTemplate__cta">
          <a class="cta__button -backToLogin" href="<?php echo home_url("/login/"); ?>">ログイン画面へ戻る</a>
          <button class="cta__button" type="submit" onclick="disableButton(event)">メール送信</button>
        </div>
      </div>
    </form>
  <?php } ?>


</div>



<!--end content-->
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
