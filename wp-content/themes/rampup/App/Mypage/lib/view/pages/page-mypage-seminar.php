<?php
/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
include(get_theme_file_path() . "/App/Mypage/model/include-mypage-seminar.php");

/*--------------------------------------------------
/* ヘッダー
/*------------------------------------------------*/
get_header();

/*--------------------------------------------------
/* ページコンテンツ
/*------------------------------------------------*/
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);
if ($class_user->user_status == "paid") {

  include get_theme_file_path() . "/App/Core/app-variables.php";
  include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php"); ?>

  <link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">

  <div class="mypage">
    <div class="inner -mypageInner">
      <div class="mypage__contents">

        <div class="mypage__contentsFlex">
          <div class="mypage__main">
            <div class="mypage__mainChild">
              <?php
              // ---------- 契約期間終了したらポップを出す ----------
              global
                $site_default_contractEnd,
                $site_default_contractEndCheck;
              if (
                $class_user->user_contractEnd <= $site_default_contractEnd &&
                $site_default_contractEndCheck == 'true'
              ) {
                do_action('mypage_user_contractEnd', $user_id);
              }

              include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-seminar.php"); ?>
            </div>
          </div>

          <div class="mypageSidebar -sticky">
            <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-sidebar.php" ?>
          </div>
        </div>



      </div>
    </div>
  </div>
  <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php" ?>
  <?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php } else {
  // wp_safe_redirect(home_url());
  // exit;
} ?>
<?php get_footer();
