<?php

/**
 * Template Name: トップページ
 *
 * @Template Post Type: post, page,
 *
 * @since I'LL 1.0
 */
get_header();
 ?>

<link rel="stylesheet"
      href="<?php echo get_template_directory_uri() ?>/assets/css/top.css">


<div class="contact contact-bg"
     id="contact">
  <div class="inner-950">
    <h3 class="contact_title calendar-heading">
      下記よりご希望の面談日時で予約が可能です
    </h3>
    <p class="contact_text calendar-heading">
      受講したい方、講座に興味のある方、<br class="tab"><br class="sp">相談・質問がしたい方など、<br><br class="tab">お気軽にご相談ください！
    </p>
  </div>


  <div class="inner-950">
    <?php
    // ---------- カレンダーのコンポーネント出力 ----------
    include(get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar.php");
    ?>
  </div>
</div>

<div class="sp-fix pc-none">
  <a href="#contact">
    <p>まずは面談申し込みへ！</p>
  </a>
</div>

<?php if (is_front_page()) { ?>
<style>
::-webkit-scrollbar {
  width: 5px;
  height: 0px;
}

::-webkit-scrollbar-track {
  background: rgba(gray, .3);
  border: none;
}

::-webkit-scrollbar-thumb {
  background-color: #12b9bd;
}
</style>

<?php } ?>

<?php include get_theme_file_path() . "/footer-default.php" ?>
<?php get_footer();