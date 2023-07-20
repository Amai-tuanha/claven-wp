<?php

/**
 * Template Name: カレンダー
 *
 * @Template Post Type: post, page,
 *
 */
?>
<link rel="stylesheet"
      href="<?= rampup_css_path('component-modal.css') ?>">


<?php





// ---------- 前後の週を表示するときはモーダルを表示させておく ----------
if (
  isset($_POST["which"]) &&
  isset($_POST["state"])
) {
  $display_block = "display :block; ";
}
?>
<div class="modalWrap -calendar"
     style="background-image:url(<?php echo get_template_directory_uri() ?>/img/page/front/modal_bg_1.jpg);
     <?= $display_block ?>">

  <div class="modal__scroll js__modal__scroll">
    <div class="modal__contentWrap -calendar">
      <div class="js__modal__contentHeight">
        <div class="modal__content js__modal__content">
          <div class="modal__contentChild -change-reservation"
               style="<?= $display_block ?>">
            <?php
            // ---------- カレンダーのコンポーネント出力 ----------
            include get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar.php";
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/assets/js/modal.js"></script>