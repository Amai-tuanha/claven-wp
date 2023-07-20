<?php

/**
 * Theme setup.
 *
 */

use Carbon\Carbon;

if (!function_exists('users_progress_page')) {
  function users_progress_page()
  {

    // ---------- 変数 ----------
    global $post,
      $wpdb,
      $clane_email1,
      $clane_email2,
      $amount_int,
      $tax,
      $rand_number,
      $order_id,
      $user_subscribers_array;
    // ---------- 変数ファイル ----------
    include get_theme_file_path() . "/App/Dashboard/model/dashboard-variables.php";
    include get_theme_file_path() . "/App/Dashboard/model/memberList/memberList-variables.php"; ?>
    <link rel="stylesheet" href="<?= rampup_css_path('component-dashboard-table.css') ?>">
    <!-- <link rel="stylesheet"
      href="<?= rampup_css_path('front.css') ?>"> -->
    <link rel="stylesheet" href="<?= rampup_css_path('reset.css') ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <div id="dashboard" class="dashboard -progress">
      
      <div class="dashboard__inner">

        <div class="dashboard__membership">
          <h2>学習進捗</h2>

          <table class="dashboard__table">
            <tbody id="content-width">
              <tr class="dashboard__tableRow">
                <th class="dashboard__tableHead -fixed01">
                  名前
                </th>
                <?php
                global $stepColorArray;
                $membership_args = [
                  'post_type' => 'membership',
                  'post_status' => 'publish',
                  'order' => 'DESC',
                  'posts_per_page' => -1,
                ];
                $WP_post = new WP_Query($membership_args);
                if ($WP_post->have_posts()) {
                  while ($WP_post->have_posts()) {
                    $WP_post->the_post();

                    $previous_post_id = get_previous_post()->ID;
                    $post_term = get_the_terms(get_the_ID(), 'membership_taxonomy')[0];
                    $previous_post_term = get_the_terms($previous_post_id, 'membership_taxonomy')[0];

                    $stepColor = $stepColorArray[$post_term->slug]; ?>

                    <th class="dashboard__tableHead -fixed02" style="max-width:200px; min-width:200px; background-color:<?= $stepColor ?>;">
                      <span><?php echo str_replace("　", "", get_the_title()); ?></span>
                    </th>

                    <?php if (
                      ($post_term->slug && $previous_post_term->slug) &&
                      ($post_term->slug != $previous_post_term->slug)
                    ) { ?>

                      <th class="dashboard__tableHead -fixed02 -break">
                        <span><?php echo '~' . $post_term->slug ?></span>
                      </th>

                    <?php } ?>
                <?php
                  }
                }
                wp_reset_postdata(); ?>

              </tr>
              <?php foreach ($user_subscribers_array as $user) {
                $user_id = $user->ID;
                $user_info_array = get_user_by('id', $user_id); ?>
                <tr class="dashboard__tableRow ">

                  <td class="dashboard__tableDesc -fixed02">
                    <?php echo trim($user->display_name); ?>
                  </td>

                  <?php if ($WP_post->have_posts()) {
                    while ($WP_post->have_posts()) {
                      $WP_post->the_post();
                      $post_slug = $post->post_name;
                      $user_membership_check = get_user_meta($user_id, $post_slug, true);

                      $previous_post_id = get_previous_post()->ID;
                      $post_term = get_the_terms(get_the_ID(), 'membership_taxonomy')[0];
                      $previous_post_term = get_the_terms($previous_post_id, 'membership_taxonomy')[0];
                      $user_membership_check_carbon = new Carbon((int) $user_membership_check);
                      $user_membership_formatted = carbon_formatting($user_membership_check_carbon, '');
                      // echo " $test <br> ";
                      // echo '<pre>';
                      // var_dump($user_membership_check);
                      // echo '</pre>';
                  ?>
                      <td class="dashboard__tableDesc
                          <?= ($user_membership_check) ? '-done' : ''; ?>">
                        <?php //= $post_slug;
                        ?>
                        <?php echo ($user_membership_check) ? $user_membership_formatted : '未完了'; ?>
                      </td>

                      <?php if (
                        ($post_term->slug && $previous_post_term->slug) &&
                        ($post_term->slug != $previous_post_term->slug)
                      ) { ?>

                        <td class="dashboard__tableDesc -break"></td>

                      <?php } ?>

                  <?php
                    }
                  }
                  wp_reset_postdata(); ?>
                </tr>
              <?php
              } ?>

            </tbody>
          </table>
        </div>

      </div>
      <script>
        let cw = $("#content-width").innerWidth();
        // console.log(cw);
        let ew = $('#dashboard').css('width', cw + 'px');
        // console.log(ew);
      </script>
  <?php
  }
}
