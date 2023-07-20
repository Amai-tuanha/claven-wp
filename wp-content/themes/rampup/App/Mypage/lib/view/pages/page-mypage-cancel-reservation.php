<?php


// do_action('page_mypage_cancel_reservation__include');

/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
// ---------- POST送信の処理をまとめたファイルをインクルード ----------
include(get_theme_file_path() . "/App/Mypage/model/include-mypage-cancel-reservation.php");

/*--------------------------------------------------
/* ヘッダー
/*------------------------------------------------*/
get_header();
?>
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<div class="mypage">
  <div class="inner">

    <section class="formTemplate">

      <form method="POST" action="" id="reserveForm" class="formTemplate">

        <div class="-tac">
          <h2 class="formTemplate__heading">基本情報を入力して予約</h2>
        </div>

        <input type="hidden" name="mypageReservationCancel">
        <input type="hidden" name="wp_get_referer" value="<?= wp_get_referer() ?>">
        <input type="hidden" name="post_id" value="<?= $_GET['post_id'] ?>">
        <?php if (isset($_GET['user_id'])) { ?>
          <input type="hidden" name="user_id" value="<?= $_GET['user_id'] ?>">
        <?php } ?>
        <input type="hidden" name="mypage_reservation" value="<?= carbon_formatting($class_post->reservation_date) ?>">
        <input type="hidden" name="user_email" id="email" readonly value="<?= $user_info_array->user_email ?>">

        <h2 class="-tac">キャンセル日程</h2>
        <h3 class="-tac"><?php echo carbon_formatting($class_post->reservation_date) ?></h3>
        <br>

        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable" for="cancel_reason">キャンセル理由 <span class="-error">*</span></label>
          <div class="formTemplate__inputItem">
            <textarea class="formTemplate__input" required name="cancel_reason" id="cancel_reason" placeholder="キャンセルの理由をご入力ください" cols="30" rows="10"></textarea>
          </div>
        </div>


       

        <label class="<?php echo (!current_user_can('administrator')) ? '-hidden' : ''; ?>" for="emailCheck" style="display:block; text-align:center;" >
        <input type="checkbox" name="emailCheck" id="emailCheck" checked>メールを送信する（管理者のみ表示）
        <br><br>
      </label>
      <div class="formTemplate__cta">


        <a class="cta__button" type="submit" href="javascript:history.back()" style="margin-right:2rem;" >戻る</a>
        <button class="cta__button" onclick="disableButton(event)" type="submit">日程をキャンセル</button>
      </div>
      </form>

    </section>
  </div>
</div>



<script src="<?php echo get_template_directory_uri(); ?>/App/assets/js/form-sent.js"></script>
<!--end content-->
<?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php"
?>
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
