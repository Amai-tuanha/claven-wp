<?php

/**
 * Template Name: 初回面談フォームページ
 *
 * @Template Post Type: post, page,
 *
 */

// require_once get_template_directory() . '/App/GoogleService/GoogleCalendar.php';
// echo '<pre>';
// var_dump(wp_get_referer());
// echo '</pre>';
// do_action('page_add_meeting_form__send_form');
include(get_theme_file_path() . "/App/Calendar/model/include-add-meeting-form.php");
use Carbon\Carbon;
?>


<?php get_header(); ?>

<div class="inner">
  <form method="POST" action="" id="reserveForm" class="formTemplate">

    <input type="hidden" name="addMeetingForm">

    <input type="hidden" name="reservation_personInCharge_user_id" value="<?= $reservation_personInCharge_user_id_esc ?>">
    <input type="hidden" name="token" value="<?= $token ?>">

    <input type="hidden" name="user_login" value="<?php echo 'user-' . $wpUsers_DB_rowCount . '-' . $user_randomNumber ?>">
    <?php if ($user_id) { ?>
      <input type="hidden" name="user_id" value="<?= $user_id ?>">
    <?php } ?>

    <div class="-tac">
      <h2 class="formTemplate__heading">面談日程を作成</h2>
    </div>
    <div style="display:flex; justify-content:center;">
      <div class="formTemplate__inputBox -aic" style="border-radius:10px;   padding:2rem 6rem;  width:64%;">
        <span>面談希望日</span>
        <p><?= Carbon::parse(htmlspecialchars($_GET['booking']))->copy()->format('Y年m月d日 H:i'). '〜'; ?></p>
        <input class="formTemplate__input -reserveDate" type="hidden" name="reservation_date" id="reservation_date" readonly value="<?php echo htmlspecialchars($_GET['booking']); ?>">
      </div>
    </div>


    <div class="formTemplate__formContentWrapper">
    <?php if (!$class_user->user_status) { ?>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable" for="user_lastName">姓<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <input class="formTemplate__input" type="text" name="user_lastName" id="user_lastName" value="<?= $class_user->user_lastName ?>">
            <?php if ('blank' === $error['user_lastName']) { ?>
              <p class="error_msg">※苗字をご記入下さい</p>
            <?php } ?>

          </div>
        </div>
      <?php } else { ?>
        <input class="formTemplate__input" type="hidden" name="user_lastName" id="user_lastName" value="<?= $class_user->user_lastName ?>">
      <?php } ?>

      <?php if (!$class_user->user_status) { ?>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable" for="user_firstName">名<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <input class="formTemplate__input" type="text" name="user_firstName" id="user_firstName" value="<?= $class_user->user_firstName ?>">
            <?php if ('blank' === $error['user_firstName']) { ?>
              <p class="error_msg">※お名前をご記入下さい</p>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <input class="formTemplate__input" type="hidden" name="user_firstName" id="user_lastName" value="<?= $class_user->user_firstName ?>">
      <?php } ?>

      <div class="formTemplate__inputBox">
        <label class="formTemplate__inputLable" for="user_email">メールアドレス<span class="-error">*</span></label>
        <div class="formTemplate__inputItem">
          <input class="formTemplate__input" type="email" name="user_email" id="user_email" <?php echo ($class_user->user_email) ? 'readonly' : ''; ?> value="<?= $class_user->user_email ?>">
          <?php if ('blank' === $error['user_email']) { ?>
            <p class="error_msg">※メールアドレスをご記入下さい</p>
          <?php } ?>
        </div>
      </div>

      <?php if (!$class_user->user_status) { ?>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable" for="user_tel">電話番号<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <input class="formTemplate__input" type="tel" name="user_tel" value="<?= $class_user->user_tel ?>">
            <?php if ('blank' === $error['user_tel']) { ?>
              <p class="error_msg">※電話番号をご記入下さい</p>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <input class="formTemplate__input" type="hidden" name="user_tel" value="<?= $class_user->user_tel ?>">
      <?php } ?>

      <?php if (!$class_user->user_status) { ?>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable" for="user_question">面談内容<span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <label for="user_question1">
              <input type="radio" name="user_question" id="user_question1" value="受講したい" checked="checked">受講したい
            </label>

            <label for="user_question2">
              <input type="radio" name="user_question" id="user_question2" value="質問したい">質問・相談したい
            </label>

            <label for="user_question3">
              <input type="radio" name="user_question" id="user_question3" value="その他">その他
            </label>
          </div>
        </div>
      <?php } else { ?>
        <input class="formTemplate__input" type="hidden" name="user_question" value="<?= $class_user->user_question ?>">
      <?php } ?>
      <?php
      /*
      <div class="formTemplate__inputBox">
        <label class="formTemplate__inputLable" for="reservation_date">面談希望日</label>
        <div class="formTemplate__inputItem">
          <input class="formTemplate__input" type="datetime" name="reservation_date" id="reservation_date" readonly value="<?php echo htmlspecialchars($_GET['booking']); ?>">
        </div>
      </div>
      */
      ?>

      <?php if (current_user_can('administrator')) {
        $user_get_email = $class_user->user_get_email($calendar_action);
      ?>
        <div class="formTemplate__cta">
          <button class="js__modal__trigger" type="button">
            <a href="<?= $user_get_email['slug'] ?>" target="_blank" rel="noopener">
              <?= $user_get_email['post_title'] ?>
              (クリックでプレビュー)
            </a>
          </button>
        </div>
        <br>
      <?php } ?>

      <label class="<?php echo (!current_user_can('administrator')) ? '-hidden' : ''; ?>" for="emailCheck" style="display:block; text-align:center;" >
        <input type="checkbox" name="emailCheck" id="emailCheck" checked>メールを送信する（管理者のみ表示）
        <br><br>
      </label>
      <div class="formTemplate__cta">


        <a class="cta__button" type="submit" href="javascript:history.back()" style="margin-right:2rem;" >戻る</a>
        <button class="cta__button" type="submit" onclick="disableButton(event)">送信する</button>
      </div>
      </div>

      <!-- <a class="contact-backlink" href="javascript:history.back()">戻る</a> -->
  </form>
</div>





<!--end content-->
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
