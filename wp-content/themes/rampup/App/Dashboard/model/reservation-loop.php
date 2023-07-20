<link rel="stylesheet"
      href="<?= rampup_css_path('customPost-reservation.css') ?>">



<?php

use Carbon\Carbon;

$now = new carbon('now');
global $reservation_post_active_status_array;

// // ---------- 面談日程ループ ----------
// include(get_theme_file_path() . "/App/Dashboard/model/reservation-loop.php");

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
if ($WP_post->have_posts()) {
  while ($WP_post->have_posts()) {
    $WP_post->the_post();
    $post_id = get_the_ID();
    $class_post = new Rampup_Post($post_id);
    $reservation_date_carbon = new carbon($class_post->reservation_date);
    $i++;
    // ---------- 最初だけ ----------
    if (
      $reservation_date_carbon > $now // 未来の日程
    ) {
      // ---------- 投稿情報をインクルード ----------
      include(get_theme_file_path() . "/App/Dashboard/lib/view/pages/single-reservation-info-component.php");
    }
  }
}
// ---------- カレンダーモーダルインクルード ----------
include(get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar-modal.php");
wp_reset_postdata();
