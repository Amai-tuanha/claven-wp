<?php

/**
 * Template Name: single page
 * @package WordPress
 * @Template Post Type: post, page,
 * @subpackage I'LL

 */
// ---------- インターンサイトのコードが入ってます。 ----------
get_header(); 
 ?>
<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
  <link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet"
      href="<?php echo get_template_directory_uri() ?>/assets/css/single.css' ) ?> ">

<div class="inner" style="padding-top:4rem; padding-bottom:8rem;">
  <?php
    $user_id = get_current_user_id();
    $class_user = new Rampup_User($user_id);
  if($class_user->user_status === 'paid'){

    if (have_posts()) {
        the_post();
          // ---------- ひとつ前の投稿のリンク取得 ----------
          $step_postPrevLink = get_the_permalink(get_next_post()->ID);
          $step_postPrevLink = str_replace(home_url(), '', $step_postPrevLink);

          // ---------- 現在の投稿をリンク取得 ----------
          $step_postLink = str_replace(home_url(), '', get_the_permalink());
          // $user_id = get_current_user_id();
          // ---------- admin以外 ----------
          // if (!current_user_can("administrator")) {
          //   // ---------- 一つ前の課題が終わっていなかったら ----------
          //   if (
          //     !get_user_meta($user_id, $step_postPrevLink, true) &&
          //     $step_postPrevLink !== $step_postLink) {
          //     wp_safe_redirect( wp_get_referer(  ) );
          //     exit;
          //   }
          // }
        ?>
  <div class="singlePost">

    <h1><?php the_title(); ?></h1>

    <?php if (has_post_thumbnail()) {?>
    <figure class="singlePost__thumbnail">
      <img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>"
           alt="サムネイル">
    </figure>
    <?php } ?>

    <div class="singlePost__contents">
      <?php echo do_shortcode(get_the_content()) ?>
    </div>
  </div>
  <?php } 
}else{
  wp_safe_redirect(home_url());
}?>
</div>
</div>
</section>
</main>

<?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php" ?>
  <?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();