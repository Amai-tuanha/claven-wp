<?php

use Carbon\Carbon;

$terms = get_the_terms($post->ID, 'membership_taxonomy');
$term_slug = $terms[0]->slug;
$term_name = $terms[0]->name;
$stepColor = $stepColorArray[$term_slug];
$now_formatted = Carbon::now()->format("Y-m-d");
$stepProgress = [];

// ---------- 投稿のループ ----------
$args = [
  'post_type' => 'membership',
  'taxonomy' => 'membership_taxonomy',
  'term' => $term_slug,
  'posts_per_page' => -1,
];
$WP_post = new WP_Query($args);

// ---------- 投稿数取得 ----------
$step_postCount = $WP_post->post_count;
if ($WP_post->have_posts()) {
  while ($WP_post->have_posts()) {
    $WP_post->the_post();
    $step_userProgress = get_user_meta($user_id, $post->post_name, true);
    if ($step_userProgress) {
      add_user_meta($user_id, $post_slug, '', true);
      array_push($stepProgress, $step_userProgress);
    }
  }
  wp_reset_postdata();
}

// ---------- ステップごとの現在の進捗（投稿数） ----------
$stepProgress = count($stepProgress);
if ($stepProgress == $step_postCount) {
  $stepProgress = $stepProgress;
} else {
  $stepProgress = $stepProgress + 1;
}

$stepProgress_percetageEach = 277 / $step_postCount;
$stepProgress_percetage = $stepProgress_percetageEach * $stepProgress;
$stepProgress_percetage = 277 - ($stepProgress_percetage - 20);

?>
<div class="modal__flex">
  <div class="modal__contentLeft">

    <figure class="svgCircleWrap -modal"
            percetage-each="<?= $stepProgress_percetageEach ?>">

      <img class="svgCircle__check <?= $done ?>"
           src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_check_bgBlack_1.svg"
           alt="チェックマーク">

      <?php include(get_theme_file_path() . "/assets/img/common/icon_circle_mypage_1.php") ?>
    </figure>
  </div>

  <button type="button"
          class="membership_finishedButton"
          id="completion">×</button>

  <div class="modal__contentRight">
    <p class="modal__heade"><?= $term_slug ?></p>
    <p class="modal__text -type1">課題を1つクリアしました！</p>
    <p class="modal__text -type2">この調子で次の課題を進めましょう！</p>
  </div>
</div>