<?php get_header(); ?>
<link rel="stylesheet" href="<?= rampup_css_path('page-cardPayment.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('page-mypage.css') ?>">
<?php
// ---------- マイページヘッダー読み込み ----------
include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php"); ?>


<section class="-mb8" style="margin-top:8rem;">
  <div class="inner">
    <ol class="step -mb8">
      <li class="userPolicy" >利用規約</li>
      <li class="settlement" >決済</li>
      <li class="is-current">完了</li>
    </ol>
    <div class="thanks -pd0">
      <h1 class="thanks__title -tac">お支払いが完了しました</h1>
      <p class="thanks__text -tac">この度は誠にありがとうございます。 <br>後ほど担当よりご連絡させていただきますので、しばらくお待ち下さい。
      </p>
      <br>
      <p class="thanks__text -tac"><a class="-url" href="/mypage">マイページへ</a></p>
    </div>
  </div>
</section>




<?php include(get_theme_file_path() . "/footer-default.php") ?>
<?php get_footer();
