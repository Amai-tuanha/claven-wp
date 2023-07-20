<?php

/**
 * Template Name: マイページ　面談日程追加ページ.
 *
 * @Template Post Type: post, page,
 *
 */
get_header();
?>

<?php
$user_id = get_current_user_id();

$class_user = new Rampup_User($user_id);
if (
  $class_user->user_status == "before_contract"
  || $class_user->user_status == "application"
  || $class_user->user_status == "paid"
  || ($class_user->user_status == "rest" && find_closest_reservation_date($user_id))
) {
?>
<link rel="stylesheet"
      href="<?= rampup_css_path('page-mypage.css') ?>">
<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<div class="mypage">
  <div class="inner">
    <?php
      // ---------- カレンダーのコンポーネント出力 ----------
      include(get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar.php");
      ?>
  </div>
</div>

<!--end content-->
<?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php"
  ?>
<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php } else {
  // wp_safe_redirect(home_url());
} ?>
<?php get_footer();