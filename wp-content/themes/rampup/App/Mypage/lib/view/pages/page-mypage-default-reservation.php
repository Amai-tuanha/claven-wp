<?php
global $site_default_contractTerm;

use Carbon\Carbon;

include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-user-edit.php");
/**
 * Template Name: マイページ　デフォルト面談設定ページ.
 *
 * @Template Post Type: post, page,
 *
 */
get_header();
?>

<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('component-modal.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('customPost-reservation.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-user-edit.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">


<?php
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);
if (
  $class_user->user_status == "before_contract"
  || $class_user->user_status == "application"
  || $class_user->user_status == "paid"
  || ($class_user->user_status == "rest" && find_closest_reservation_date($user_id))
) {
  include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php");
?>

  <!-- <div class="mypage"> -->
    <!-- <div class="inner"> -->
      <!-- <div class="mypage__user-edit"> -->
        <?php
        // ---------- ユーザーID ----------
        $user_id = get_current_user_id();
        // ---------- コントロールパネルのユーザーエディット ----------
        ?>

        <?php if (current_user_can('administrator') && is_page('mypage-default-reservation')) { ?>
          <div class="project__wrap">
            <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php")
            ?>

            <div class="project__contents -bg__none">

              <div class="contents__width">
                <div class="controlpanel-useredit__form h-adr">

                  <section class="list__wrap6">

                    <div class="list__tableInformation">

                      <h2 class="list__titleBox">営業時間設定</h2>
                      <div class="list__textBox">

                        <?php include(get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar-multiple.php") ?>

                      </div>
                    </div>

                  </section>

                </div>

              </div>
            </div>
          </div>
        <?php } ?>
      <!-- </div> -->
    <!-- </div> -->
  <!-- </div> -->


  <script src="<?php echo get_template_directory_uri(); ?>/App/assets/js/form-sent.js"></script>
  <script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
  <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php"
  ?>
  <?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php } else {
  wp_safe_redirect(home_url());
} ?>
<?php get_footer();
