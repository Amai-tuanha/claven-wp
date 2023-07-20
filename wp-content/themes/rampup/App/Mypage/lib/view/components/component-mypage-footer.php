<?php

use Carbon\Carbon;

if (esc_attr($_GET['timer-redirect']) == 'true') {
  $display_block = 'display : block;';
?>
  <script>
    $(function() {
      setTimeout(function() {

        $('#js__modaiTimer__start').attr("link", "<?php echo wp_get_referer() ?>")
      }, 100);
    });
  </script>
<?php
}
?>


<style>
  .modalWrap{
    cursor: pointer;
  }
  .-js-mypage-animation{
    cursor: auto;
  }
</style>
<link rel="stylesheet" href="<?= rampup_css_path('component-mypage-modal.css') ?>">
<div class="modalWrap" style="<?= $display_block ?>">
  <div class="modal__contentScroll">
    <!-- <div class="modal__contentWrap"> -->
    <div class="modal__contentInner -noScrollBar">
      <div class="modal__content">

        <div class="modal__contentChild -js-changeRecord">
          <?php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-modal-changeRecord.php") ?>
        </div>

        <div class="modal__contentChild -js-startCourse" style="<?= $display_block ?>">
          <?php include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-modal-startCourse.php") ?>
        </div>

        <div class="modal__contentChild -js-mypage-animation">
          <?php


          include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-progressCircle-footer-calc.php");

          ?>



        </div>
      </div>
    </div>
  </div>
</div>

<?php 
if(!is_page('terms-of-service')){
  echo '<script src="https://cdn.jsdelivr.net/npm/@mojs/core"></script>';
}
 ?>
<script src="<?php echo get_template_directory_uri() ?>/App/Mypage/lib/js/button-animation.js"></script>
<?php // ---------- jsの読み込み制御 ----------
if(
    strpos($parsed_query, "mypage-timer") !== false
 || strpos($parsed_query, "membership") !== false
  ){
; ?>
<script src="<?php echo get_template_directory_uri() ?>/App/Mypage/lib/js/mypage.js"></script>
<?php 
}
; ?>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/timer/push.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/App/Mypage/lib/js/timer.js"></script>
