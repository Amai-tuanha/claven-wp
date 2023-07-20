<?php

/**
 * Template search results page
 * @package WordPress
 * @subpackage I'LL

 */

$stripe_user_data = $wpdb->get_results('SELECT * FROM `wp_fullstripe_subscribers` WHERE email =' . '"' . $user_email . '"');
$get_single_ = $wpdb->get_row('SELECT * FROM `` WHERE email =' . '"' . $_GET["email"] . '"');
get_header() ?>

<link rel="stylesheet"
      href="<?= rampup_css_path('archive-index.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('single-membership.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('component-sidebar.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('search.css') ?>">

<?php
global $wp_query;
$total_results = $wp_query->found_posts;
$rampup_search = get_search_query();
?>
<?php if (is_user_logged_in()) { ?>
<?php } else {
  wp_safe_redirect(home_url());
  exit;
} ?>



<?php if ($_GET["s"] !== "") { ?>
<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<section class="courseWrap">
  <main class="course">

    <section class="membershipSidebarWrap -sp-order-second">
      <?php get_sidebar('membership'); ?>
    </section>

    <section class="course__contentsWrap">
      <div class="course__contents ">
        <?php if (have_posts()) : ?>
        <section class="newpost">
          <h3 class="postlist-title">検索ワード：<span><?php echo get_search_query(); ?></span></h3>
          <p class="post-hittext"><span><?php echo $wp_query->found_posts; ?>件</span>の記事が見つかりました</p>
          <br>
          <div class="newpost_wrap">
            <ul class="course__listPost">




              <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
              <li class="course__post">
                <a href="<?php echo get_permalink(); ?>">
                  <h3 class="course__postTitle">
                          <?php the_title(); ?>
                        </h3>
                  <div class="course__postLink">
                    <figure class="course__postThum"
                            style="background-image: url(<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>)"></figure>

                    <div class="course__postContent">
                      <?php echo mb_substr(get_the_excerpt(), 0, 200) . '...'; ?>
                    </div>
                  </div>

                </a>
              </li>

              <?php endwhile;
                endif; ?>
            </ul>
          </div>
        </section>
        <?php if (function_exists("the_pagenation")) {
            the_pagenation();
          } ?>
        <?php else : ?>
        <section class="newpost">
          <h3 class="postlist-title">検索ワード：<span><?php echo get_search_query(); ?></span></h3>
          <p class="post-hittext">キーワードに当てはまる記事はありませんでした</p>
        </section>
        <?php endif; ?>
      </div>
  </main>
</section>
<?php } else {
  wp_safe_redirect($wp_blog_url);
} ?>

<?php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php") ?>
<?php get_footer();