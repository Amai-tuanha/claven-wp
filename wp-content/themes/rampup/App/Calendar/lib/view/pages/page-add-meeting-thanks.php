<?php

/**
 * Template Name: サンクスページ
 * @package WordPress
 * @Template Post Type: post,page,
 * @subpackage I'LL
 */

// use Carbon\Carbon;
// do_action('page_add_meeting_thanks__process_form');
include(get_theme_file_path() . "/App/Calendar/model/include-add-meeting-thanks.php");
?>

<?php get_header(); ?>
<script>
  window.localStorage.setItem('add-meeting-form', 'submitted');
</script>



<?php if (wp_get_referer()) { ?>

  <!--content-->
  <section class="thanks">
    <div class="inner -thanksInner">
      <h1 class="thanks__title">お問い合わせを受け付けました。</h1>
      <p class="thanks__text">1〜3営業日以内に、担当者よりご返信させていただきます。</p><br>
      <p class="-tac">
        <a class="-url" href="<?= home_url() ?>">トップページへ戻る</a>
      </p>
      <?php if (is_user_logged_in()) { ?>
        <p class="-tac" class="-tac">
          <a class="-url" href="<?= home_url() . '/mypage' ?>">マイページへ戻る</a>
        </p>
      <?php } ?>

      <?php if (current_user_can("administrator")) { ?>
        <p class="-tac">
          <a class="-url" href="<?= home_url() . '/controlpanel-user-edit/?user_id=' . $user_id; ?>">基本情報編集画面へ戻る</a>
        </p>
      <?php } ?>
    </div>
  </section>

<?php
} else {
  wp_safe_redirect(home_url());
}
?>




<?php include get_theme_file_path() . "/footer-default.php" ?>
<?php get_footer();
