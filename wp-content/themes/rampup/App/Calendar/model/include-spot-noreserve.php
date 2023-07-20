<?php
require_once get_template_directory() . '/App/Calendar/model/Calendar.php';
require_once get_template_directory() . '/App/vendor/autoload.php';

use Carbon\Carbon;

// $which = "";
// $state = 0;
// $weekJaList = ["日", "月", "火", "水", "木", "金", "土"];
// $dt = new Calendar($which, $state);
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
// ovd($_POST);

$which = '';
$state = 0;
$weekJaList = ['日', '月', '火', '水', '木', '金', '土'];
$calendarClass = new Calendar($which, $state);
$this_month = carbon::today()->format("m");
$booking_array = $_POST['booking'];
$cancel_array = $_POST['cancel'];
global $site_numberOfCalendarCell, $site_reservation_personInCharge_user_id;

// $reservation_personInCharge_user_id_GET = $_POST['reservation_personInCharge_user_id'];
if (isset($_GET['reservation_personInCharge_user_id'])) {
  $reservation_personInCharge_user_id = (int)esc_attr($_GET['reservation_personInCharge_user_id']);
} else {
  $reservation_personInCharge_user_id = (int)$site_reservation_personInCharge_user_id;
}
// $reservation_personInCharge_user_id_GET = $_GET['reservation_personInCharge_user_id'];
// $reservation_personInCharge_user_id_esc = esc_attr($reservation_personInCharge_user_id_GET);

global $wpdb;
// ovd($booking_array);

$today = Carbon::today()->format("Y-m-d");
if (isset($_GET['reservation_personInCharge_user_id'])) {
  $reservation_personInCharge_user_id = (int)esc_attr($_GET['reservation_personInCharge_user_id']);
} else {
  $reservation_personInCharge_user_id = (int)$site_reservation_personInCharge_user_id;
}


// /*--------------------------------------------------
// /* 予約不可の設定した投稿を呼び出す
// /*------------------------------------------------*/
// if (isset($cancel_array)) {
//   $noreserve_args = array(
//     'post_type' => 'reservation',
//     'posts_per_page' => -1,
//     'meta_key' => 'reservation_date',
//     'post_status' => 'noreserve',
//     'meta_query' => [
//       [
//         'key'     => 'reservation_date',
//         'value'   => '2022-06-22',
//         'type'   => 'DATE',
//         'compare' => '>=',
//       ]
//     ],
//   );
//   $noreserve_post_array = get_posts($noreserve_args);
//   foreach ($noreserve_post_array as $noreserve_post) {
//     // ---------- 変数定義 ----------
//     $reservation_post_id = $noreserve_post->ID;
//     $target_reservation_personInCharge_user_id = (int)get_post_meta($reservation_post_id, 'reservation_personInCharge_user_id', true);
//     $reservation_date = (string)get_post_meta($reservation_post_id, 'reservation_date', true);
//     // ovd($reservation_date);


//     foreach ($cancel_array as $cancel) {
//       // if ($booking_array === $reservation_date) {
//       // ovd('test');
//       // ovd($cancel);
//       if ($cancel === $reservation_date) {
//         // ---------- 削除する投稿のメタデータ配列 ----------
//         $reservation_post_meta_array = [
//           "reservation_personInCharge_user_id" => $target_reservation_personInCharge_user_id,
//           "reservation_date" => $reservation_date,
//           "reservation_duration" => $site_numberOfCalendarCell,
//         ];

//         // ---------- 投稿のメタ情報の削除 ----------
//         foreach ($reservation_post_meta_array as $reservation_post_meta_key => $reservation_post_meta_value) {
//           delete_post_meta($reservation_post_id, $reservation_post_meta_key, $reservation_post_meta_value);
//         }

//         $delete_post_args = array(
//           'ID'           => $reservation_post_id,
//           'post_status'   => 'trash',
//         );
//         wp_update_post($delete_post_args);

//         // wp_delete_post($reservation_post_id, true);
//       }
//     }
//   }
// }

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  if (isset($_GET['which'])) {
    $which = $_GET['which'];
    $state = $_GET['state'];
    $dt = new Calendar($which, $state);
    $_GET['state'] = $dt->status;
  };
} elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
  // ---------- 予約用のポスト送信があった場合 ----------
  if (isset($booking_array)) {
    foreach ($booking_array as $booking) {
      // ---------- 投稿・固定ページ作成 ----------
      $post_array = [
        "post_type"      => "reservation",
        "post_title"     => "予約不可",
        "post_status"     => "noreserve",
      ];
      $inserted_page_id = wp_insert_post($post_array);
      // ---------- 投稿メタデータ配列 ----------
      $reservation_post_meta_array = [
        "reservation_personInCharge_user_id" => $reservation_personInCharge_user_id,
        "reservation_date" => $booking,
        "reservation_duration" => $site_numberOfCalendarCell,
      ];

      // ---------- メタ情報登録実行 ----------
      foreach ($reservation_post_meta_array as $reservation_post_meta_key => $reservation_post_meta_value) {
        update_post_meta($inserted_page_id, $reservation_post_meta_key, $reservation_post_meta_value);
      }
    }
  } 
  // ---------- 取り消し用のポスト送信があった場合 ----------
  if (isset($cancel_array)) {
    // ---------- キャンセルのポスト送信があった場合 ----------
    $noreserve_args = array(
      'post_type' => 'reservation',
      'posts_per_page' => -1,
      'meta_key' => 'reservation_date',
      'post_status' => 'noreserve',
      'meta_query' => [
        [
          'key'     => 'reservation_date',
          'value'   => '2022-06-22',
          'type'   => 'DATE',
          'compare' => '>=',
        ]
      ],
    );
    $noreserve_post_array = get_posts($noreserve_args);
    foreach ($noreserve_post_array as $noreserve_post) {
      // ---------- 変数定義 ----------
      $reservation_post_id = $noreserve_post->ID;
      $target_reservation_personInCharge_user_id = (int)get_post_meta($reservation_post_id, 'reservation_personInCharge_user_id', true);
      $reservation_date = (string)get_post_meta($reservation_post_id, 'reservation_date', true);


      foreach ($cancel_array as $cancel) {
        if ($cancel === $reservation_date) {
          // ---------- 削除する投稿のメタデータ配列 ----------
          $reservation_post_meta_array = [
            "reservation_personInCharge_user_id" => $target_reservation_personInCharge_user_id,
            "reservation_date" => $reservation_date,
            "reservation_duration" => $site_numberOfCalendarCell,
          ];

          // ---------- 投稿のメタ情報の削除 ----------
          foreach ($reservation_post_meta_array as $reservation_post_meta_key => $reservation_post_meta_value) {
            delete_post_meta($reservation_post_id, $reservation_post_meta_key, $reservation_post_meta_value);
          }
          wp_delete_post($reservation_post_id, true);
        }
      }
    }
  }
  // elseif (isset($_POST['destroy'])) {
  //   $reserve_dt = $_POST['destroy'];
  //   $wpdb->delete($wpdb->prefix . 'postmeta', array(
  //     'meta_value' => $reserve_dt
  //   ));

  // };
  // ---------- 予約・取り消しのどちらか（分岐条件の理由：カレンダーの週切り替えで反応しないようにするため。） ----------
  if(isset($booking_array) || isset($cancel_array)){
    wp_safe_redirect(get_current_link());
    exit;
  }
}
// ---------- 前の週や次の週に切り替えるのに使っている ----------
if ('POST' === $_SERVER['REQUEST_METHOD']) {
  if (isset($_POST['which'])) {
    $which = $_POST['which'];
    $state = $_POST['state'];
    $calendarClass = new Calendar($which, $state);
    $_POST['state'] = $calendarClass->status;
  }
}
