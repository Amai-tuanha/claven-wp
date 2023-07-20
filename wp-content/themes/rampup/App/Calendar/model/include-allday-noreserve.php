<?php
use Carbon\Carbon;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $reserveDate = $_POST['reserve_date'];
  global $wpdb;
  $carbon = new Carbon($reserveDate, 'Asia/Tokyo');
  for ($num = 0; $num < 21; $num++) {
      $noreserve_date = $carbon->copy()->addHour(10)->addMinute($num * 30)->format('Y-m-d H:i:s');

      // ---------- 投稿・固定ページ作成 ----------
      $post_array = array(
          "post_type"      => "reservation",
          "post_title"     => "予約不可",
          "post_status"     => "noreserve",
      );
      $inserted_page_id = wp_insert_post($post_array);
      // ---------- メタ情報追加 ----------
      update_post_meta($inserted_page_id, "reservation_date", $noreserve_date);
  }
  wp_safe_redirect(get_current_link());
}