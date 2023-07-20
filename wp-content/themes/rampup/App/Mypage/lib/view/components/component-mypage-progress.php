<link rel="stylesheet"
      href="<?= rampup_css_path('page-mypage-progress.css') ?>">


<div class="mypageProgress">
  <h1 class="mypage__mainChildTitle">学習課題</h1>

  <p class="mypageProgress__subTitle">全体の進捗率</p>

  <div class="mypageProgress__bar">

    <?php
    $args = [
      'post_type' => 'membership',
      'posts_per_page' => -1,
    ];
    $WP_post = new WP_Query($args);
    // ---------- 投稿数取得 ----------
    $step_postCount = $WP_post->post_count;

    // ---------- 全体進捗 ----------
    $userProgressAll = [];
    if ($WP_post->have_posts()) {
      while ($WP_post->have_posts()) {
        $WP_post->the_post();

        // ---------- スラッグを変換 ----------
        $postSlug = $post->post_name;

        // ---------- 変数定義 ----------
        $userProgress = get_user_meta($user_id, $postSlug, true);

        // ---------- ユーザーメタと配列に追加 ----------
        if ($userProgress !== "") {
          add_user_meta($user_id, $post_slug, '', true);
          array_push($userProgressAll, $userProgress);
        }
      }
    }
    wp_reset_postdata();

    // ---------- 現在の進捗（投稿数） ----------
    $userProgressAll = count($userProgressAll);

    // ---------- 現在の進捗（パーセンテージ） ----------
    $progress_percetage = round(($userProgressAll / $step_postCount) * 100);
    // $progress_percetage = 100;

    if ($progress_percetage == 100) {
      $max_width = "100%";
      $allDone = "-allDone";
    }
    $i = 1;
    while ($i <= 11) {
      $i++; ?>
    <p class="mypageProgress__barDotLines  <?= $allDone ?>"></p>
    <?php } ?>
    <div class="mypageProgress__stroke <?= $allDone ?>"
         style="width : <?= $progress_percetage ?>%; max-width: <?= $max_width ?>; ">
      <figure class="mypageProgress__strokePercentage">
        <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_up_triangle_blue_1.svg"
             alt="上矢印">
        <span><?= $progress_percetage ?>%</span>
      </figure>
    </div>

    <?php if ($allDone) { ?>
    <p class="mypageProgress__messageWrap <?= $allDone ?>">
        <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_deco_yellow_1.svg" alt="黄色紙吹雪">
        <span class="mypageProgress__message">
          全ての課題が終わりました！お疲れ様です!</span>
        <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_deco_yellow_2.svg" alt="黄色紙吹雪">
      </p>
    <?php } ?>
  </div>

  <ul class="mypageProgress__postsWrap">
    <?php
    $terms = get_terms('membership_taxonomy');
    $stepIndex = -1;
    foreach ($terms as $term) {
      $stepIndex++;
      $term_order = $term->term_order;
      $term_slug = $term->slug;
      $term_name = $term->name;
      $term_next = (int)$term_order + 1;
      $term_prev = (int)$term_order - 1;
      $stepPrev = $terms[$stepIndex - 1];
      $stepNext = $terms[$stepIndex + 1];
    ?>
    <?php
      // echo '<pre>';
      // var_dump($term_slug);
      // // var_dump($terms);
      // echo '</pre>';
      ?>
    <li class="mypageProgress__postsChild"
        id="<?= $term->slug ?>">
      <div class="mypage__mainChildTitleFlex">
        <h2 class="mypage__mainChildTitle"><?= strtoupper($term_slug) ?>　<span class="-primary"><?= $term_name ?></span>
          </h2>

        <div class="mypage__mainChildTitleButtons">

          <?php if ($stepPrev) { ?>
          <a href="<?php echo "#$stepPrev->slug" ?>"
             class="mypage__button -progressPrevNext">
            <img class="mypage__buttonArrow -prev"
                 src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_left_triangle_white_1.svg"
                 alt="左矢印">
            <?= $stepPrev->slug ?>
          </a>
          <?php } ?>


          <?php if ($stepNext) { ?>
          <a href="<?php echo "#$stepNext->slug" ?>"
             class="mypage__button -progressPrevNext -progressNext">
            <?= $stepNext->slug ?>
            <img class="mypage__buttonArrow -next"
                 src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_right_triangle_white_1.svg"
                 alt="右矢印">
          </a>
          <?php } ?>
        </div>
      </div>

      <?php
        $args = [
          'post_type' => 'membership',
          'taxonomy' => 'membership_taxonomy',
          //  'order' => 'DESC',
          'term' => $term->slug,
          'posts_per_page' => -1,
          'post_parent' => 0,
          //  'post_status' => 'any',
        ];
        $WP_post = new WP_Query($args);
        if ($WP_post->have_posts()) {

          $i = 0;
          while ($WP_post->have_posts()) {
            $i++;
            $WP_post->the_post();

            // ---------- ひとつ前の投稿の情報取得 ----------
            $step_postPrevLink = get_next_post()->post_name;
            $step_postPrevID = get_next_post()->ID;

            // ---------- 現在の投稿の情報取得 ----------
            $step_postLink = $post->post_name;
            $step_postID = get_the_ID();

            // ---------- タイトル変換 ----------
            $title = str_replace("CHAPTER$i", "", get_the_title());
            $title = str_replace("　", "", $title);
        ?>

      <div class="mypageProgress__post">
        <h3 class="mypageProgress__postTitle js__mypageProgress__postTitle">
                <span class="mypageProgress__postContentChapter">CHAPTER <?= $i ?></span>
                <span class="mypageProgress__postContentLine"></span>
                <span class="mypageProgress__postContentTitle"><?= $title ?></span>
                <?php
                // ---------- 現在の投稿をリンク取得 ----------
                if (get_user_meta($user_id, $step_postLink, true)) { ?>
                  <img class="mypageProgress__postContentCheck" src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_check_bgBlack_1.svg" alt="チェックマーク">
                <?php } ?>

              </h3>

        <div class="mypageProgress__postContent ">
          <div class="mypageProgress__postContentText singlePost ">
            <?php echo get_field("overview_textarea"); ?>
          </div>


          <?php
                // ---------- ひとつ前の課題が終わっていたら ----------
                if (
                  get_user_meta($user_id, $step_postPrevLink, true)
                  || !$step_postPrevID
                ) { ?>
          <a href="<?php echo get_permalink(); ?>"
             class="mypage__button js__modal__trigger"
             modal-trigger="-js-startCourse">課題を進める <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_externalLink_white_1.svg"
                 alt="外部リンク"></a>
          <?php } else { ?>
          <span class="mypage__button -disabled"
                modal-trigger="-js-startCourse">前の課題を終わらせよう</span>
          <?php } ?>
        </div>
      </div>

      <?php } ?>
      <?php }
        wp_reset_postdata(); ?>
    </li>
    <?php } ?>
  </ul>

</div>