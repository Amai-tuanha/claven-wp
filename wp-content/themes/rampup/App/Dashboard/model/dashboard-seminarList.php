<?php

/**
 * Theme setup.
 *
 */

use Carbon\Carbon;

if (!function_exists('seminar_list_page')) {
  function seminar_list_page()
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

    $subscriber_count = count($user_subscribers_array);
    // ---------- 変数ファイル ----------
    include get_theme_file_path() . "/App/Dashboard/model/dashboard-variables.php";
    include get_theme_file_path() . "/App/Dashboard/model/memberList/memberList-variables.php";

    if (isset($_POST['seminarListPageTrigger'])) {
      $result = Carbon::now()->format("Y-m-d");

      foreach ($_POST as $_postKey => $_postValue) {
        $_post_pieces_array = explode("_", $_postKey);
        // $_post_slug = $_post_pieces_array[0];
        $_post_userID = $_post_pieces_array[1];

        $user_seminar_attendance = get_user_meta($_post_userID, $_postValue, true);
        foreach ($user_subscribers_array as $user) {
          $user_id = $user->id;

          // ---------- 値がなかったら ----------
          if (
            $user_id == $_post_userID &&
            !$user_seminar_attendance
          ) {
            update_user_meta($user_id, $_postValue, $result);
          }

          // ---------- 値があったら ----------
          elseif (
            $user_id == $_post_userID
            && $user_seminar_attendance
          ) {
            update_user_meta($user_id, $_postValue, "");
          }
        }
      }
    } ?>
<link rel="stylesheet"
      href="<?= rampup_css_path('component-dashboard-table.css') ?>">
<!-- <link rel="stylesheet"
      href="<?= rampup_css_path('front.css') ?>"> -->
<link rel="stylesheet"
      href="<?= rampup_css_path('reset.css') ?>">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>

<div id="dashboard"
     class="dashboard -progress">
  ​
  <div class="dashboard__inner">

    <div class="dashboard__seminar">
      <h2>勉強会出席 <?= $subscriber_count . '人' ?></h2>
      <form name="intern_all"
            class="dashboard__form"
            action="<?php echo home_url() . '/wp-admin/admin.php?page=seminar-list-page'; ?>"
            method="POST">
        <input type="hidden"
               name="seminarListPageTrigger">

        <table class="dashboard__table">
          <tbody id="content-width">

            <tr class="dashboard__tableRow -fixed">
              <th class="dashboard__tableHead -fixed03">
                ユーザー名
              </th>

              <?php
                  $seminarList_args = array(
                    'post_type' => 'seminar',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                  );
                  $WP_post = new WP_Query($seminarList_args);
                  if ($WP_post->have_posts()) : while ($WP_post->have_posts()) : $WP_post->the_post(); ?>

              <th class="dashboard__tableHead -fixed02"
                  style="max-width:200px; min-width:200px;">
                <span><?php the_title() ?></span>
              </th>

              <?php endwhile;
                  endif;
                  wp_reset_postdata(); ?>
            </tr>

            <?php
                $user_subscribers_array = get_users([
                  // 'orderby' => 'meta_key',
                  // 'meta_key' => 'user_attendance_count',
                  'role' => 'subscriber', //購読者
                ]);
                foreach ($user_subscribers_array as $user) {
                  $user_id = $user->ID;
                  $user_info_array = get_user_by('id', $user_id); ?>

            <tr class="dashboard__tableRow">
              <td class="dashboard__tableDesc -fixed01">
                <?= $user_info_array->display_name ?>
              </td>

              <?php
                    $attendance_count = 0;
                    $WP_post = new WP_Query($seminarList_args);
                    if ($WP_post->have_posts()) : while ($WP_post->have_posts()) : $WP_post->the_post();

                        $post_id = get_the_ID();
                        $post_slug = $post->post_name;
                      $user_seminar_attendance = get_user_meta($user_id, $post_slug, true);

                        // ---------- 値があったら ----------
                        if ($user_seminar_attendance) {
                          $attendance_count++;
                          update_user_meta($user_id, 'user_attendance_count', $attendance_count);
                          $user_seminar_data = str_replace("-", "/", $user_seminar_attendance);
                        }
                        // ---------- 値がなかったら ----------
                        else {
                          update_user_meta($user_id, 'user_attendance_count', 0);
                          $user_seminar_data = "×";
                        } ?>
              <td class="dashboard__tableDesc">
                <input class="-id_radio"
                       type="checkbox"
                       id="<?= $post_slug . '_' . $user_id; ?>"
                       name="<?= $post_slug . '_' . $user_id; ?>"
                       value="<?= $post_slug; ?>">

                <label class="dashboard__tableIDlabel -seminar <?php echo ($user_seminar_attendance) ? '-true' : ''; ?>"
                       for="<?php echo $post_slug . '_' . $user_id; ?>">
                  <?= $user_seminar_data ?>
                </label>
              </td>
              <?php endwhile;
                    endif;
                    wp_reset_postdata(); ?>
            </tr>
            <?php
                } ?>
          </tbody>

        </table>
        <br>

        <button type="submit">変更する</button>

      </form>
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