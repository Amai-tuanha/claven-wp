<?php
  global $site_default_contractTerm;

  use Carbon\Carbon;

  include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-user-edit.php");
  /**
   * Template Name: マイページ 面談日程変更ページ.
   *
   * @Template Post Type: post, page,
   *
   */
  get_header();
  ?>

<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('component-modal.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('customPost-reservation.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-user-edit.css') ?>">


<?php
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);
if (
  $class_user->user_status == "before_contract"
  || $class_user->user_status == "application"
  || $class_user->user_status == "paid"
  || ($class_user->user_status == "rest" && find_closest_reservation_date($user_id))
) {
  include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php");
?>

  <div class="mypage">
    <div class="inner">
      <div class="mypage__meeting-edit">
        <?php                  
        $user_id = get_current_user_id();
        global $reservation_post_active_status_array;

        // ---------- 面談日程 ----------
        $i = 0;
        $b = 0;
        $reservation_args = array(
          'post_type' => 'reservation',
          'post_status' => $reservation_post_active_status_array,
          'order' => 'ASC',
          'orderby' => 'meta_value',
          'meta_key' => 'reservation_date',
          'meta_query' => [
            [
              'key'     => 'reservation_user_id',
              'value'   => $user_id,
              'compare' => '=',
            ]
          ],
          'posts_per_page' => -1,
        );
        $WP_post = new WP_Query($reservation_args);
        $count = 0;
        if ($WP_post->have_posts()) {
          while ($WP_post->have_posts()) {
            $WP_post->the_post();
            $post_id = get_the_ID();
            $class_post = new Rampup_Post($post_id);
            $reservation_date_carbon = new carbon($class_post->reservation_date);

            if (
              $reservation_date_carbon > $now // 未来の日程
            ) {
              $count++;
              // ---------- 投稿情報をインクルード ----------
              include(get_theme_file_path() . "/App/Dashboard/lib/view/pages/single-reservation-info-component.php");
            }
          }

        }
        if($count === 0){
          echo '<p>現在、予約中の面談はありません。</p>';
        }
        //   // ---------- カレンダーモーダルインクルード ----------
        include(get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar-modal.php");
        wp_reset_postdata();


        ?>
      </div>
    </div>
  </div>


  <script src="<?php echo get_template_directory_uri(); ?>/App/assets/js/form-sent.js"></script>
  <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php"
  ?>
  <?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php } else {
  wp_safe_redirect(home_url());
} ?>
<?php get_footer();
