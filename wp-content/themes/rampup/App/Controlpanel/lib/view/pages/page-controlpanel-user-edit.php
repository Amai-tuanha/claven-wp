<?php

use Carbon\Carbon;

global $site_default_contractTerm;
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-user-edit.php");
?>
<?php


/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-user-edit.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('customPost-reservation.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">



<?php if (isset($_GET['user_id'])) {
      $user_id = $_GET['user_id'];
      $class_user = new Rampup_User($user_id);
      ?>

<div class="project__wrap">
      
      
      <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>

            <?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-user-edit.php"); ?>

      </div>
<?php } else {
      wp_safe_redirect('/controlpanel-user-list');
      exit;
?>

<?php } ?>

<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<? //php include(get_theme_file_path() . "/footer-default.php")
?>
<?php get_footer();
