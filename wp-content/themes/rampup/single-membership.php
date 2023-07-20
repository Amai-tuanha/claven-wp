<?php

/**
 * Template Name: single page
 * @package WordPress
 * @Template Post Type: post, page,
 * @subpackage I'LL
 */
get_header(); ?>

<link rel="stylesheet" href="<?= rampup_css_path('archive-index.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('single-membership.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('component-sidebar.css') ?>">

<?php

use Carbon\Carbon;

$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);

// ---------- 変数ファイル呼び出し ----------
include(get_theme_file_path() . "/App/Core/app-variables.php");
// ---------- マイページヘッダー読み込み ----------
 include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php");
if (
  (is_user_logged_in()) &&
  (current_user_can("administrator") ||
    $class_user->user_status == "paid"
  )
) {
  // ---------- ショートコード呼び出し ----------
  echo do_shortcode('[auto_heading_menu]');

?>
  <section class="courseWrap">

    <main class="course -single">

      <section class="membershipSidebarWrap -sp-order-second">
        <?php get_sidebar('membership'); ?>
      </section>

      <section class="course__contentsWrap">

        <div class="course__contents">
          <?php
          if (have_posts()) {
            the_post();
            // // ---------- ひとつ前の投稿のリンク取得 ----------
            $step_postPrevLink = get_the_permalink(get_next_post()->ID);
            $step_postPrevLink = str_replace(home_url(), '', $step_postPrevLink);

            // ---------- 現在の投稿をリンク取得 ----------
            $step_postLink = str_replace(home_url(), '', get_the_permalink());

            // ---------- admin以外 ----------
            // if (!current_user_can("administrator")) {
              // ---------- 一つ前の課題が終わっていなかったら ----------
              if (
                is_nullorempty(get_user_meta($user_id, get_post(get_next_post()->ID)->post_name, true)) &&
                $step_postPrevLink !== $step_postLink
                ) {
                  wp_safe_redirect( wp_get_referer(  ) );
                  exit;
                }
            // }
          ?>
            <div class="singlePost -ahm-target">

              <h1><?php the_title(); ?></h1>

              <?php if (has_post_thumbnail()) { ?>
                <figure class="singlePost__thumbnail">
                  <img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>" alt="サムネイル">
                </figure>
              <?php } ?>

              <div class="singlePost__contents">
                <?php the_content(); ?>

                <?php
                if (is_singular('membership')) {
                  $post_slug = $post->post_name;
                  $my_id = get_current_user_id();

                  if (isset($_POST['membership_finished'])) {
                    update_user_meta($my_id, $post_slug, $_POST['membership_finished']);
                    wp_safe_redirect(get_permalink() . '#membership__finishedForm');
                  }

                  // echo get_current_user_id();
                ?>
                  <form action="<?php echo get_permalink(); ?>" method="POST" class="-mojs-parent" id="membership__finishedForm">

                    <?php if (get_user_meta($user_id, $post_slug, true)) { ?>
                      <input type="hidden" name="membership_finished" value="">
                      <button class="button__type1 -complete" type="submit">完了済み</button>

                    <?php } else { ?>
                      <input type="hidden" name="membership_finished" value="<?php echo Carbon::now()->format("Y-m-d"); ?>">
                      <button class="button__type1 js__modal__trigger" modal-trigger="-js-mypage-animation" id="completion" type="button">完了済みにする</button>
                    <?php } ?>
                  </form>
                  <!-- <br><br><br> -->
                  <div class="course__pagination">

                    <div class="course__paginationChild">
                      <?php
                      if (get_next_post()) {
                        $next_post = get_next_post();
                      ?>

                        <a class="course__paginationLink" style="background-image: url(<?php echo $thum_data[0]; ?>)" ; href="<?php echo get_permalink($next_post->ID); ?>">
                          <span class="course__paginationText">
                            << 前の課題</span>
                              <p class="course__paginationTitle"><?php echo $next_post->post_title ?></p>
                        </a>
                      <?php } ?>
                    </div>

                    <div class="course__paginationChild -nextPost">
                      <?php
                      $notLast = $wp_query->query["name"] !== "allblue";
                      if (get_previous_post() && $notLast) {
                        $previous_post = get_previous_post();
                      ?>
                        <a class="course__paginationLink" href="<?php echo get_permalink($previous_post->ID); ?>">
                          <span class="course__paginationText">次の課題 >></span>
                          <p class="course__paginationTitle"><?php echo $previous_post->post_title ?></p>
                        </a>
                      <?php } ?>
                    </div>

                  </div>

                  <script>
                    $('.button__type1.-complete').on("click", function() {
                      if (window.confirm('「未完了」に状態を戻しますか？')) {
                        // ---------- OKならフォーム送信 ----------
                        $('#assignment__form').submit();
                      }
                    })
                  </script>
                <?php } ?>

              </div>

            </div>
          <?php
          } ?>
        </div>

      </section>

      <!-- <section class="mypageSidebar">
      <? //php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-sidebar.php")
      ?>
    </section> -->
    </main>
  </section>
<?php

} else {
  wp_safe_redirect(home_url() . '/login/');
} ?>



<?php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php") ?>
<?php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-redirectWithNoTimer.php") ?>


<?php get_footer();
