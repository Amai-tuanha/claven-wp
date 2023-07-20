<?php



include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php");


$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);

global $post;
$slug = $post->post_name;
echo '<pre style="text-align: right; margin-top: 8rem;">';
var_dump($slug);
echo '</pre>';

// if ($slug === "mypage") {
//   wp_safe_redirect("/mypage-change-reservation/");
//   exit;
// }

if (is_user_logged_in()) {

  // ---------- 商談予定、失注(見込みあり）、失注(見込みなし） ----------
  if (
    $class_user->user_status == "cancelled"
  ) {
    wp_safe_redirect(home_url());
    exit;
  }
  // ---------- 契約前の時 もしくは休止中で面談日程がある人 ----------
  elseif (
    $class_user->user_status == "before_contract" ||
    ($class_user->user_status == "rest" &&
      find_closest_reservation_date($user_id))
  ) {
    wp_safe_redirect(home_url() . '/mypage-change-reservation');
    exit;
  }
  // ---------- 申し込みの時 ----------
  elseif ($class_user->user_status == "application") {
    // echo "現在、運営者が操作中です。操作が完了するまでしばらくお待ちください。";
    wp_safe_redirect(home_url() . '/mypage-change-reservation');
    exit;
  }
  // ---------- 休止中で面談予約がないとき ----------
  elseif ($class_user->user_status == "rest" && !find_closest_reservation_date($user_id)) {
    wp_safe_redirect(home_url() . '/mypage-contract-end');
  }
  // ---------- 決裁済みで、利用規約に同意がなかったら ----------
  elseif (
    $class_user->user_status == "paid" &&
    !$class_user->user_termsOfService
  ) {
    wp_safe_redirect($class_user->user_termsOfService_link());
    exit;
  }
  // ---------- 契約後もしくは決済隅の時 ----------
  elseif ($class_user->user_status == "paid" &&
  $class_user->user_termsOfService === 'on') {
    wp_safe_redirect(home_url('/mypage-timer'));
    exit;
  }
  // ---------- 契約後もしくは決済隅の時 ----------
  // elseif ($class_user->user_status == "paid") {
  //   wp_safe_redirect(home_url('/mypage-timer'));
  //   exit;
  // }
  // ---------- ログインしていなかったら ----------
} else {
  wp_safe_redirect(home_url() . '/login');
  exit;
}


