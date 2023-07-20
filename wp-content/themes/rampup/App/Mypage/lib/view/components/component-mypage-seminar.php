<link rel="stylesheet" href="<?= rampup_css_path('page-mypage-progress.css') ?>">

<div class="mypageSeminar">

  <h1 class="mypage__mainChildTitle">勉強会の出席</h1>

  <div class="mypageSeminar__stampsWrap">
    <ul class="mypageSeminar__stamps">

      <?php
      $i = 0;
      $seminar_archive_post_id = slug_to_post_id('seminar-archive', "seminar");
      $mypageSeminar_args = array(
        'post_type' => 'seminar',
        'post__not_in' => [$seminar_archive_post_id],
        'posts_per_page' => -1,
      );
      $WP_post = new WP_Query($mypageSeminar_args);
      if ($WP_post->have_posts()) : while ($WP_post->have_posts()) : $WP_post->the_post();
          $i++;

          $post_slug = $post->post_name;
          $user_seminar_attendance = get_user_meta($user_id, $post_slug, true);
      ?>
          <li class="mypageSeminar__stamp" index="<?= $i ?>">
            <span class="mypageSeminar__stampTitle"><?php the_title() ?></span>
            <figure class="mypageSeminar__stampImg <?php echo ($user_seminar_attendance) ? '-show' : ''; ?>">
              <?php include get_theme_file_path() . "/assets/img/mypage/icon_attendance_blue_1.svg" ?>
            </figure>

            <p class="mypageSeminar__stampDate <?php echo ($user_seminar_attendance) ? '-show' : ''; ?>"><?= carbon_formatting($user_seminar_attendance, '') ?></p>
          </li>
      <?php endwhile;
      endif;
      wp_reset_postdata();
      ?>
    </ul>
  </div>

  <ul class="mypageProgress__postsWrap">
    <li class="mypageProgress__postsChild -show" style="display:block !important;">
      <h2 class="mypage__mainChildTitle">勉強会内容</h2>
      <?php
      $WP_post = new WP_Query($mypageSeminar_args);
      if ($WP_post->have_posts()) : while ($WP_post->have_posts()) : $WP_post->the_post();

          $post_slug = $post->post_name;
          $user_seminar_attendance = get_user_meta($user_id, $post_slug, true);
      ?>

          <div class="mypageProgress__post">
            <h3 class="mypageProgress__postTitle js__mypageProgress__postTitle">
              <?php the_title(); ?>
              <?php if ($user_seminar_attendance) { ?>
                <img class="mypageProgress__postContentCheck" src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_check_bgBlack_1.svg" alt="チェックマーク">
              <?php } ?>
            </h3>

            <div class="mypageProgress__postContent ">
              <div class="mypageProgress__postContentText singlePost"><?php echo get_field("overview_textarea") ?></div>
            </div>
          </div>
      <?php endwhile;
      endif;
      wp_reset_postdata();
      ?>
    </li>
  </ul>

</div>
