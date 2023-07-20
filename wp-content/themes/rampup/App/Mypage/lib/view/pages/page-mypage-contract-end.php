<?php
/*
 * Template Name: マイページ.
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>

<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<?php
if (is_user_logged_in()) {

  // ---------- ファイルインクルード ----------
  include get_theme_file_path() . "/App/Core/app-variables.php";

?>
  <section class="thanks">
    <div class="inner -thanksInner">
      <h1 class="thanks__title">契約期間が終了しました</h1>
      <p class="thanks__text">再度申込の場合はトップページの面談予約からお願いします。
      </p>
      <br>


      <p class="-tac">
        <a href="<?= home_url() ?>">トップページへ戻る</a>
      </p>
      <?php if (is_user_logged_in()) { ?>
        <!-- <p class="-tac">
          <a href="<?= home_url() . '/mypage' ?>">マイページへ戻る</a>
        </p> -->
      <?php } ?>
    </div>
  </section>

  <?php if (is_developer()) { ?>
    <!-- <section class="formTemplate">
  <div class="formTemplate__cta">
    <button class="cta__button"
            onclick="disableButton(event)"
            type="submit">日程をキャンセルする</button>
  </div>
</section> -->
  <?php
  } ?>

<?php
  // ---------- ログインしていなかったら ----------
} else {
  wp_safe_redirect(home_url());
  exit;
}
?>


<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
