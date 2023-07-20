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

<link rel="stylesheet"
      href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet"
      href="<?= rampup_css_path('page-controlpanel-dashboard.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('controlpanel-header.css') ?>">

<div class="project__wrap">
<?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>
  <div class="project__contents -dashboard">

    <div class="contents__width">

      <div class="dashboard__membership">
        <h2>学習進捗 <?= $subscriber_count . '人' ?></h2>

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
                'order' => 'ASC',
                'posts_per_page' => -1,
              ];
              $WP_post = new WP_Query($membership_args);
              if ($WP_post->have_posts()) {
                while ($WP_post->have_posts()) {
                  $WP_post->the_post();

                  $previous_post_id = get_previous_post()->ID;
                  $post_term = get_the_terms(get_the_ID(), 'membership_taxonomy')[0];
                  $previous_post_term = get_the_terms($previous_post_id, 'membership_taxonomy')[0];

                  $stepColor = $stepColorArray[$post_term->slug];
              ?>

              <th class="dashboard__tableHead -fixed02"
                  style="max-width:200px; min-width:200px; background-color:<?= $stepColor ?>;">
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

                  ?>
              <?php }
              }
              wp_reset_postdata(); ?>

            </tr>

            <?php
            foreach ($user_subscribers_array as $user) {
              $user_id = $user->ID;
              $user_info_array = get_user_by('id', $user_id);
            ?>
            <tr class="dashboard__tableRow ">

              <td class="dashboard__tableDesc -fixed02">
                <?php echo trim($user->display_name); ?>
              </td>

              <?php if ($WP_post->have_posts()) {
                  while ($WP_post->have_posts()) {
                    $WP_post->the_post();
                    $post_slug = $post->post_name;
                    $user_membership_check_original = get_user_meta($user_id, $post_slug, true);
                    $user_membership_check = (strpos($user_membership_check_original, '-') !== false) ? $user_membership_check_original : (int)$user_membership_check_original;


                    $previous_post_id = get_previous_post()->ID;
                    $post_term = get_the_terms(get_the_ID(), 'membership_taxonomy')[0];
                    $previous_post_term = get_the_terms($previous_post_id, 'membership_taxonomy')[0];
                    $user_membership_check_carbon = new Carbon($user_membership_check);
                    $user_membership_formatted = carbon_formatting($user_membership_check_carbon, '', 'Y年');
                ?>
              <td class="dashboard__tableDesc
                          <?= ($user_membership_check) ? '-done' : ''; ?>">
                <?php echo ($user_membership_check) ? $user_membership_formatted : '未完了'; ?>
              </td>

              <?php if (
                      ($post_term->slug && $previous_post_term->slug) &&
                      ($post_term->slug != $previous_post_term->slug)
                    ) { ?>

              <td class="dashboard__tableDesc -break"></td>

              <?php } ?>

              <?php }
                }
                wp_reset_postdata(); ?>
            </tr>
            <?php } ?>

          </tbody>
        </table>
      </div>

    </div>


  </div>


</div>

<?php get_footer();