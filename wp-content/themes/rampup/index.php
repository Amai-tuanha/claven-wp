<?php



/**
 * Main template.
 *
 * @since I'LL Pro 1.0
 */
get_header(); ?>
<link rel="stylesheet" href="<?= rampup_css_path('archive-index.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('single-membership.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('component-sidebar.css') ?>">

<!-- mv  -->
<?php
global
  $my_start_mtg_st,
  $my_start_mtg;
$tag = get_queried_object();
$tag_slug = $tag->slug;
$tag_name = $tag->name;

$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);
?>
<!-- end mv  -->

<?php
// ---------- 変数ファイル呼び出し ----------
include get_theme_file_path() . "/App/Core/app-variables.php";

// ---------- マイページヘッダー読み込み ----------
include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php");
// ---------- ユーザーがログインしていてステータスが「intern」だったら ----------
// ---------- もしくはadminだったら ----------
if (
  current_user_can("administrator")
  || $class_user->user_status == "paid"
) {
?>

  <section class="courseWrap">
    <main class="course">

      <section class="membershipSidebarWrap -sp-order-second">
        <?php get_sidebar('membership'); ?>
      </section>

      <section class="course__contentsWrap">
        <figure class="course__mv">
          <img src="/wp-content/uploads/2021/08/mv6.png" alt="practical exercize">
        </figure>

        <div class="course__contents">
          <ul class="course__list">
            <?php
            $terms = get_terms('membership_taxonomy', 'hide_empty=0');
            foreach ($terms as $term) {
            ?>
              <li class="course__listCat">

                <span class="course__listCatMintitle <?php echo '-' . $term->slug; ?>">
                  <?php echo $term->name; ?>
                </span>
                <p class="course__listCatTitle">
                  <?php
                  if ('step0' === $term->slug) {
                    echo 'チュートリアル';
                  } elseif ('step1' === $term->slug) {
                    echo 'HTML, CSS基礎';
                  } elseif ('step2' === $term->slug) {
                    echo 'WordPress基礎';
                  } elseif ('step3' === $term->slug) {
                    echo '静的ページコーディング';
                  } elseif ('step4' === $term->slug) {
                    echo '静的ページコーディング';
                  } elseif ('step5' === $term->slug) {
                    echo '動的ページコーディング';
                  } elseif ('step6' === $term->slug) {
                    echo '表示チェック';
                  } elseif ('step7' === $term->slug) {
                    echo '最終課題';
                  } ?>
                </p>

                <ul class="course__listPost">
                  <?php
                  $args = [
                    'post_type' => 'membership',
                    'taxonomy' => 'membership_taxonomy',
                    'term' => $term->slug,
                    'posts_per_page' => -1,
                  ];
                  $WP_post = new WP_Query($args);
                  if ($WP_post->have_posts()) {
                    while ($WP_post->have_posts()) {
                      $WP_post->the_post(); ?>

                      <li class="course__post">
                        <a class="course__postLink" href="<?php echo get_permalink(); ?>">
                          <figure class="course__postThum" style="background-image: url(<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>)"></figure>

                          <h3 class="course__postTitle">
                            <?php the_title(); ?>
                          </h3>
                        </a>
                      </li>
                  <?php
                    }
                  }
                  wp_reset_postdata(); ?>
                </ul>

              </li>


            <?php
            } ?>
          </ul>
        </div>

      </section>

      <!-- <section class="mypageSidebar">
        <? //php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-sidebar.php")
        ?>
      </section> -->
    </main>
  </section>

<?php } else { ?>

  <main class="course" style="padding : 80px 0 ;">
    <div class="inner">
      <h1>決済完了後、閲覧が可能になります。</h1>
    </div>
  </main>
<?php } ?>



<?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php" ?>
<?php get_footer();
