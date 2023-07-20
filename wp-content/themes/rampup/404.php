<?php
/**
* Template 404 pages (Not Found)
* @package WordPress
* @subpackage I'LL

*/

get_header(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/css/page-templateDesign.css') ?>">

<section class="notFound">
    <div class="inner">
  
      <h2 class="notFound__title">404<span class="notFound__span">NOT FOUND</span></h2>
      <p class="notFound__text1">お探しのページは<br class="notFound__text1Br">見つかりませんでした</p>
      <p class="notFound__text2">
        一時的にアクセスできない状態か、「移動」もしくは「削除」された可能性があります。
        <br>
        お手数ですが、トップページに戻るか、メニュー一覧よりお探しください。
      </p>
      <a class="notFound__link" href="/">TOPに戻る</a>
      
    </div>
  </section>

<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
