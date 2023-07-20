<?php

/**
 * Template Name: カレンダー
 *
 * @Template Post Type: post, page,
 *
 */
?>

<?php

use Carbon\Carbon;

include get_theme_file_path() . "/App/Core/app-variables.php";
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");

$which = '';
$state = 0;
$weekJaList = ['日', '月', '火', '水', '木', '金', '土'];
$calendarClass = new Calendar($which, $state);
$this_month = carbon::today()->format("m");

$reservation_personInCharge_user_id_GET = $_GET['reservation_personInCharge_user_id'];
$reservation_personInCharge_user_id_esc = esc_attr($reservation_personInCharge_user_id_GET);

// $user = wp_get_current_user();
// $email = $user->user_email;

if (
  is_page("mypage")
  && $meetingBeforeContract_today_diff < "2"
  && $today < $user_meetingBeforeContract_date
  || is_page("mypage")
  && $meetingAfterContract_today_diff < "2"
  && $today < $user_meetingAfterContract_date
) {
  $changeDisabled = "-changeDisabled";
}

if ('GET' === $_SERVER['REQUEST_METHOD']) {

  if (isset($_GET['booking'])) {
    $booking = $_GET['booking'];
    $user_id = $_GET['user_id'];
    $calendar_action = $_GET['calendar_action'];
    $calendar_post_id = $_GET['calendar_post_id'];
    // ---------- 初回面談ページ ----------
    if (
      is_front_page() ||
      is_page("mypage-add-reservation")
    ) {
      wp_safe_redirect("/add-meeting-form?booking=$booking&calendar_action=$calendar_action");
    } else {
      wp_safe_redirect("/add-meeting-form?booking=$booking&user_id=$user_id&calendar_action=$calendar_action&calendar_post_id=$calendar_post_id");
    }
  }
} elseif ('POST' === $_SERVER['REQUEST_METHOD']) {
  if (isset($_POST['which'])) {
    $which = $_POST['which'];
    $state = $_POST['state'];
    $calendarClass = new Calendar($which, $state);
    $_POST['state'] = $calendarClass->status;
  }
}


?>
<link rel="stylesheet"
      href="<?= rampup_css_path('component-calendar.css') ?>">


<section class="calendar <?= $changeDisabled ?>"
         id="calendarForm">

  <div class="calendar__top">
    <form action="<?= get_current_link() . '#calendarForm' ?>"
          method="GET"
          class="calendar__boxWrap -tac" style="display:none">
      <label class="calendar__inputLabel"
             for="select_personInCharge">担当者を選択</label>
      <select class="calendar__input"
              id="select_personInCharge"
              onchange="onChengeSendForm(event)"
              name="reservation_personInCharge_user_id">

        <?php foreach ($site_reservation_personInCharge_user_id_array as $reservation_personInCharge_user_id) {
          $class_user = new Rampup_User($reservation_personInCharge_user_id);

          $selected = (is_search()) ? '-search' : '';

          if ($reservation_personInCharge_user_id_esc) {

            if (
              $reservation_personInCharge_user_id_esc == $reservation_personInCharge_user_id
            ) {
              $selected = 'selected';
            }
          } else {
            if (
              $site_reservation_personInCharge_user_id == $reservation_personInCharge_user_id
            ) {
              $selected = 'selected';
            }
          }
        ?>
        <option <?= $selected ?>
                value="<?= $reservation_personInCharge_user_id ?>">
          <?= $class_user->user_displayName ?>
        </option>
        <?php } ?>
      </select>
      <!-- <span class="calendar__note">※ご希望の面談日程が空いてない場合は、担当者を変更してください</span> -->
    </form>
  </div>



  <div class="calendar__nextPrev"
       style="margin:0; width:100%;">
    <form method="POST"
          action="#calendarForm">
      <div class="calendar__nextPrevForm">
        <button onclick="location.href='/#calendarForm'"
                class="calendar__prevButton  content-shadow"
                name="which"
                value="minus"
                type="submit">
          <i class="fas fa-arrow-circle-left"></i> 前の週</button>

        <button class="calendar__nextButton content-shadow"
                name="which"
                value="plus"
                type="submit">次の週
          <i class="fas fa-arrow-circle-right"></i>
        </button>
      </div>
      <input type="hidden"
             name="state"
             value="<?php echo htmlspecialchars($_POST['state']); ?>">
    </form>
  </div>

  <section class="calendar__table">
    <div class="calendar__tableWrap">
      <table>
        <tr>
          <th></th>
          <?php foreach ($calendarClass->weekdays as $wD) {
            $day_to_display = $wD->month . '/' . $wD->day;
          ?>
          <?php if (0 === $wD->dayOfWeek) { ?>
          <th class="Sunday"><?= $day_to_display ?><br>(日)</th>
          <?php } elseif (6 === $wD->dayOfWeek) { ?>
          <th class="Saturday"><?= $day_to_display ?><br>(土)</th>
          <?php } else { ?>
          <th><?= $day_to_display ?><br>(<?php echo $weekJaList[$wD->dayOfWeek]; ?>)</th>
          <?php } ?>
          <?php } ?>
        </tr>
      </table>

      <div class="tbody content-shadow">
        <form method="GET"
              action="<?php echo home_url('/add-meeting-form') ?>">
          <input type="hidden"
                 id="calendar_action"
                 name="calendar_action"
                 value="add_calendar">
          <input type="hidden"
                 name="reservation_personInCharge_user_id"
                 value="<?php echo ($reservation_personInCharge_user_id_esc) ? $reservation_personInCharge_user_id_esc : $site_reservation_personInCharge_user_id; ?>">


          <?php
          if (is_page('mypage-change-reservation')) {
            $user_id = get_current_user_id();
          } else {
            $user_id = $_GET['user_id'];
          }

          if ($user_id) {
          ?>
          <input type="hidden"
                 name="user_id"
                 value="<?= $user_id ?>">
          <input type="hidden"
                 id="calendar_post_id"
                 name="calendar_post_id">
          <?php } ?>
          <table>
            <?php foreach ($calendarClass->outSchedule() as $sch) {
              $sch_hour = $sch['dt'][0]->hour;
              $sch_minute = $sch['dt'][0]->minute;
            ?>
            <tr>
              <td class="time">
                <?php echo $sch_hour; ?> :
                <?php if (30 === $sch_minute) { ?>
                30
                <?php } else { ?>
                00
                <?php } ?>
              </td>
              <?php
                $num = 0;
                while ($num < 7) {
                  $sch_state_num = $sch['state'][$num];
                  $sch_dt_num = $sch['dt'][$num];
                  ++$num;
                ?>
              <td>
                <?php if (true === $sch_state_num) { ?>
                <input class="check"
                       id="<?php echo $sch_dt_num; ?>"
                       name="booking"
                       value="<?php echo $sch_dt_num; ?>"
                       type="submit">
                <label class="label table-text_o"
                       for="<?php echo $sch_dt_num; ?>">〇</label>

                <?php } elseif (false === $sch_state_num) { ?>
                <input class="check"
                       id="<?php echo $sch_dt_num; ?>"
                       name="booking"
                       value="<?php echo $sch_dt_num; ?>"
                       type="submit"
                       disabled>
                <label class="label table-text_o"
                       style="color: #707070;background-color:#d0d0d0;"
                       for="<?php echo $sch_dt_num; ?>">×</label>

                <?php } ?>
              </td>
              <?php } ?>
            </tr>
            <?php } ?>
          </table>
        </form>
        
      </div>
    </div>
  </section>




  <br>
  <p class="calendar__tableFooter">面談時間は<?= $site_numberOfCalendarCell ?>分を想定しています。</p>
  <p class="calendar__tableFooter">※原則2日前からのキャンセルはできません</p>
  <p class="calendar__tableFooter">日時をクリックするとお客様情報入力画面に移動します。</p>
</section>