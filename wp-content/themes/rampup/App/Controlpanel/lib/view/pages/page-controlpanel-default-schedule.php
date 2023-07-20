<?php
global $site_default_contractTerm;

use Carbon\Carbon;

get_header();
?>
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-user-edit.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">


<?php
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);

if (current_user_can('administrator')) {
  include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php");
?>

  <?php
  // ---------- ユーザーID ----------
  $user_id = get_current_user_id();
  // ---------- コントロールパネルのユーザーエディット ----------
  ?>

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


  <script src="<?php echo get_template_directory_uri(); ?>/App/assets/js/form-sent.js"></script>
  <script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
  <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php"
  ?>
  <?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php } else {
  wp_safe_redirect(home_url());
} ?>
<?php get_footer();
