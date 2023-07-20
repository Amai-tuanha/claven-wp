<?php

use Carbon\Carbon;
// do_action('page_controlpanel_dashboard__send_form');
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-dashboard.php");



/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-seminar-attendance.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">


<div class="project__wrap">
  <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>


  <div class="project__contents -dashboard">

    <div class="contents__width">

      <div class="dashboard__seminar">

        <h2>勉強会出席 <?= $subscriber_count . '人' ?></h2>
        <form name="intern_all" class="dashboard__form" action="<?php echo home_url() . '/controlpanel-seminar-attendance'; ?>" method="POST">
          <input type="hidden" name="seminarListPageTrigger">

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
                  'order' => 'ASC',
                );
                $WP_post = new WP_Query($seminarList_args);
                if ($WP_post->have_posts()) : while ($WP_post->have_posts()) : $WP_post->the_post();

                ?>

                    <th class="dashboard__tableHead -fixed02" style="max-width:200px; min-width:200px;">
                      <span><?php the_title() ?></span>
                    </th>

                <?php endwhile;
                endif;
                wp_reset_postdata();
                ?>
              </tr>

              <?php
              $user_paid_list_array = [];
              foreach ($user_subscribers_array as $user_subscribers) {
                $user_id = $user_subscribers->ID;
                $class_user = new Rampup_User($user_id);
                if ($class_user->user_status === 'paid') {
                  $user_ID_and_contractDate_array = [
                    'ID'           => $user_id,
                    'contractDate' => $class_user->user_contractDate
                  ];
                  array_push($user_paid_list_array, $user_ID_and_contractDate_array);
                }
              }
              $class_sort = new Rampup_sort();
              if (!is_nullorempty($user_ID_and_contractDate_array)) {
                $user_paid_list_array_sort = $class_sort->sortByKey('contractDate', SORT_DESC, $user_paid_list_array);
                // echo '<pre>';
                // var_dump($user_paid_list_array_sort);
                // echo '</pre>';
                foreach ($user_paid_list_array_sort as $user_paid_list) {
                  $user_paid_id = $user_paid_list['ID'];
                  $class_user = new Rampup_User($user_paid_id);
                  // $user_info_array = get_user_by('id', $user_id);
              ?>

                  <tr class="dashboard__tableRow">
                    <td class="dashboard__tableDesc -fixed01">
                      <?= $class_user->user_displayName ?>
                    </td>

                    <?php
                    $attendance_count = 0;
                    $WP_post = new WP_Query($seminarList_args);
                    if ($WP_post->have_posts()) : while ($WP_post->have_posts()) : $WP_post->the_post();
                        $post_id = get_the_ID();
                        $post_slug = $post->post_name;
                        $user_seminar_attendance = get_user_meta($user_paid_id, $post_slug, true);

                        // ---------- 値があったら ----------
                        if ($user_seminar_attendance) {
                          $attendance_count++;
                          update_user_meta($user_paid_id, 'user_attendance_count', $attendance_count);
                          $user_seminar_data = carbon_formatting($user_seminar_attendance, '', 'Y年');
                        }
                        // ---------- 値がなかったら ----------
                        else {
                          update_user_meta($user_paid_id, 'user_attendance_count', 0);
                          $user_seminar_data = "×";
                        }

                    ?>
                        <td class="dashboard__tableDesc">
                          <input class="-id_radio" type="checkbox" id="<?= $post_slug . '_' . $user_paid_id; ?>" name="<?= $post_slug . '_' . $user_paid_id; ?>" value="<?= $post_slug; ?>">

                          <label class="dashboard__tableIDlabel -seminar <?php echo ($user_seminar_attendance) ? '-true' : ''; ?>" for="<?php echo $post_slug . '_' . $user_paid_id; ?>">
                            <?= $user_seminar_data ?>
                          </label>
                        </td>
                    <?php endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                  </tr>
              <?php }
              } ?>
            </tbody>

          </table>
          <br>

          <button class="send" style="margin-left: 0;" type="submit">変更する</button>

        </form>
      </div>


    </div>


  </div>


</div>
<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<?php get_footer();
