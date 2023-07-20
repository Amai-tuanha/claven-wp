<?php

$stepProgress = [];

// ---------- 投稿のループ ----------
$args = [
    'post_type' => 'membership',
    'taxonomy' => 'membership_taxonomy',
    'term' => $term->slug,
    'posts_per_page' => -1,
// 'post_status' => 'any',
];
$WP_post = new WP_Query($args);

// ---------- 投稿数取得 ----------
$step_postCount = $WP_post->post_count;
if ($WP_post->have_posts()) {
    while ($WP_post->have_posts()) {
        $WP_post->the_post();
        $step_userProgress = get_user_meta($user_id, $post->post_name, true);
        if ($step_userProgress) {
            add_user_meta($user_id, $post->post_name, '', true);
            array_push($stepProgress, $step_userProgress);
        }
    }
    wp_reset_postdata();
}

// ---------- ステップごとの現在の進捗（投稿数） ----------
$stepProgress = count($stepProgress);

// ---------- ステップごとの現在の進捗（パーセンテージ） ----------
if ($step_postCount !== 0) {
    $stepProgress_percetageEach = 277 / $step_postCount;
    $stepProgress_percetage = $stepProgress_percetageEach * $stepProgress;
    $stepProgress_percetage = 277 - ($stepProgress_percetage - 20);
} else {
}

if ($stepProgress >= $step_postCount) {
    $done = "-done";
} else {
    $done = "";
}

?>
<li class="mypageSidebar__circlesChild">

  <a href="<?php echo ($current_slug == "mypage-progress") ? "#$term->slug" : "/mypage-progress#$term->slug"; ?>"
     class="svgCircleWrap -sidebar">

    <img class="svgCircle__check <?=$done?>"
         src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_check_bgBlack_1.svg"
         alt="チェックマーク">

    <?php include get_theme_file_path() . "/assets/img/common/icon_circle_mypage_1.php"?>

  </a>

  <span class="mypageSidebar__circlesCat"><?=$term->name?></span>
</li>