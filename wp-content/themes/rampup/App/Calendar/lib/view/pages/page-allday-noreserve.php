<?php

/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
use Carbon\Carbon;
include(get_theme_file_path() . "/App/Calendar/model/include-allday-noreserve.php");

/*--------------------------------------------------
/* ヘッダー
/*------------------------------------------------*/
get_header(); ?>

<!--content-->
<?php if (current_user_can('administrator')) { ?>
<section>
  <div class="inner">
    <h3>一日丸ごと予約不可機能</h3>
    <p>一発登録されるので慎重に・・・</p>
    <form action=""
          method="post">
      <label for="noReserve"
             id="">予約不可日を選択してください</label>
      <input type="date"
             name="reserve_date"
             id="noReserve">
      <button type="submit">登録</button>
    </form>
  </div>
</section>
<?php } else {
    wp_safe_redirect(home_url());
} ?>




<!--end content-->
<?php get_footer();