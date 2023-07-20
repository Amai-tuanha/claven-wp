<?php

use Carbon\Carbon;

// ---------- 条件分岐 ----------
$onlyAdmin = current_user_can("administrator") || current_user_can("operator");

// ---------- 変数定義 ----------
$post_id = $post->ID;
$post_type = $post->post_type;
$post_status = $post->post_status;
$booking = $_GET["booking"];


// ---------- 投稿に紐づくメタデータ ----------
$class_post = new Rampup_Post($post_id);

// ---------- 時間関連のデータ ----------
$reservation_date_carbon = new Carbon($class_post->reservation_date);
$today = new Carbon('today');
$reservationDate_today_diff = $today->diffInDays($reservation_date_carbon);

// ---------- ユーザーに紐づくメタデータ ----------
$user_id = get_post_meta($post_id, "reservation_user_id", true);


?>



<div class="singelReservation">


  <?php
  if (is_page("mypage")) {
    $permalink = "/mypage";
  } elseif (is_singular('reservation')) {
    $permalink = get_permalink();
  }
  ?>
  <form action="<?php echo $permalink; ?>" method="POST" class="singelReservation__form">
    <input type="hidden" name="post_id" value="<?= $post_id ?>">

    <table class="singelReservation__table" style="margin-top:2rem;">
      <tbody>
        <tr class="singelReservation__tr">
          <th class="singelReservation__tHead -longColspan" colspan="2">
            <?php the_title() ?>面談日程情報</th>
        </tr>

        <tr class="singelReservation__tr">
          <th class="singelReservation__tHead">Google Meet URL</th>
          <td class="singelReservation__tDesc"><a href="<?= $class_post->reservation_meet_url ?>" style="color:#5DAC7D;"><?= $class_post->reservation_meet_url ?></a></td>
        </tr>

        <tr class="singelReservation__tr">
          <th class="singelReservation__tHead">面談日時</th>
          <td class="singelReservation__tDesc">
            <?php if ($booking) { ?>
              <input type="hidden" name="reservation_date" value="<?= $booking ?>" readonly>
              <?= carbon_formatting($booking) ?>

            <?php } else { ?>
              <input type="hidden" name="reservation_date" value="<?= $class_post->reservation_date ?>" readonly>
              <?= carbon_formatting($class_post->reservation_date) ?>
            <?php } ?>

            <?php
            if ($post_status !== 'cancelled') {
              if (
                $reservation_date_carbon->isFuture() && // 面談日が未来
                $reservationDate_today_diff <= 2 && // 日にちの差異が2日以内
                !current_user_can('administrator') // 管理者アカウント
              ) { ?>
                <span class="-error">　＊こちらの面談日程は現在変更不可です</span>
              <?php } else { ?>
                <button type="button" modal-action="change-reservation" calendar-action="update_calendar" calendar-post-id="<?= $post_id ?>" class="-button js__modal__trigger">日程変更</button>

                <a class="-button" href="/mypage-cancel-reservation/<?= "?post_id=$post_id&user_id=$user_id" ?>" modal-action="cancel-reservation" class=" ">キャンセル</a>

                <?php if (is_developer()) { ?>
                  <a class="-button" href="<?= get_delete_post_link($post_id, '', true) ?>" class=" ">面談削除</a>

                <?php } ?>
              <?php } ?>
            <?php } ?>
          </td>
        </tr>
      </tbody>
    </table>

    <?php
    // ---------- 面談日が未来かつ日にちの差異が2日以内 ----------
    if (
      $reservationDate_today_diff > 2
    ) { ?>
      <!-- <div class="singelReservation__buttonWrap ">
      <input type="hidden"
             name="singelReservationFormTrigger">
      <button class="button">変更</button>
    </div> -->
    <?php } ?>
  </form>

</div>