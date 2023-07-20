<?php

/*--------------------------------------------------
/* POST送信による処理
/*------------------------------------------------*/
do_action('component_mypage_frame__send_form');

?>
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

            /*--------------------------------------------------
            /* テンプレートファイルインクルード
            /*------------------------------------------------*/
            include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-timer.php");
            ?>
          </div>
        </div>

        <div class="mypageSidebar -sticky">
          <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-sidebar.php" ?>
        </div>
      </div>



    </div>
  </div>
</div>

<?//php get_footer();
