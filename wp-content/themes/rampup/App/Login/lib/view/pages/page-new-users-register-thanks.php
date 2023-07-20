<?php
include(get_theme_file_path() . "/App/Login/model/include-new-users-register-thanks.php");
?>
<?php
/**
 * Template Name: 新規ユーザー登録フォームサンクスページ
 * @package WordPress
 * @Template Post Type: post, page,
 * @subpackage I'LL
 */

get_header(); ?>

<!--content-->
<section class="thanks">
  <div class="inner -thanksInner">
    <h1 class="thanks__title">ユーザー登録完了メールを送信しました。</h1>
    <p class="thanks__text">ログイン情報はメールよりご確認ください。</p>
    <br>


    <p class="-tac">
        <a href="<?= home_url() ?>">トップページへ戻る</a>
      </p>
    <?php if (is_user_logged_in()) { ?>
    <p class="-tac">
          <a href="<?= home_url() . '/mypage' ?>">マイページへ戻る</a>
        </p>
    <?php } ?>
  </div>
</section>

<!--end content-->
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
