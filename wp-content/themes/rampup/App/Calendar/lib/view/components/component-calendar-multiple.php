<?php
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");

if (isset($_POST['adminCalendarMultiple'])) {
  $user_id_POST = $_POST['user_id'];
  $administrator_defaultNoReserve_array = $_POST['administrator_defaultNoReserve_array'];
  $administrator_defaultNoReserve_json = json_encode($administrator_defaultNoReserve_array);
  update_user_meta($user_id_POST, 'administrator_defaultNoReserve_json', $administrator_defaultNoReserve_json);

  wp_safe_redirect(get_permalink() . '/#calendarForm');
  exit;
}

use Carbon\Carbon;

include get_theme_file_path() . "/App/Core/app-variables.php";

$which = '';
$state = 0;
$weekJaList = ['日', '月', '火', '水', '木', '金', '土'];
$calendarClass = new Calendar($which, $state);
$this_month = carbon::today()->format("m");

$reservation_personInCharge_user_id_GET = $_GET['reservation_personInCharge_user_id'];
$reservation_personInCharge_user_id_esc = esc_attr($reservation_personInCharge_user_id_GET);
?>
<link rel="stylesheet" href="<?= rampup_css_path('component-calendar.css') ?>">


<section class="calendar <?= $changeDisabled ?>" id="calendarForm">

  <div class="calendar__top">
    <div class="calendar__boxWrap -tac">

      <span>毎週予約不可にする日程をお選びください</span>
    </div>
  </div>

  <section class="calendar__table">
    <div class="calendar__tableWrap">
      <table>
        <tr>
          <th></th>
          <?php foreach ($calendarClass->weekDays_fromMon as $wD) {
          ?>
            <?php if (0 === $wD->dayOfWeek) { ?>
              <th class="Sunday -tac">(日)</th>
            <?php } elseif (6 === $wD->dayOfWeek) { ?>
              <th class="Saturday -tac">(土)</th>
            <?php } else { ?>
              <th class="-tac">(<?php echo $weekJaList[$wD->dayOfWeek]; ?>)</th>
            <?php } ?>
          <?php } ?>
        </tr>
      </table>

      <div class="tbody content-shadow">
        <form method="POST" class="calendarForm__multiple" action="<?php echo get_permalink(); ?>">

          <input type="hidden" name="adminCalendarMultiple">

          <input type="hidden" name="reservation_personInCharge_user_id" value="<?php echo ($reservation_personInCharge_user_id_esc) ? $reservation_personInCharge_user_id_esc : $site_reservation_personInCharge_user_id; ?>">


          <?php
          if (
            is_page('mypage-default-reservation') ||
            is_page('controlpanel-default-schedule') ||
            is_page('mypage-change-reservation')
          ) {
            $user_id = get_current_user_id();
          } else {
            $user_id = $_GET['user_id'];
          }

          if ($user_id) {
          ?>
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <input type="hidden" id="calendar_post_id" name="calendar_post_id">
          <?php } ?>
          <table>
            <?php foreach ($calendarClass->outSchedule_multiple() as $sch) { ?>
              <tr>
                <td class="time">
                  <?php echo $sch['dt'][0]->hour; ?> :
                  <?php if (30 === $sch['dt'][0]->minute) { ?>
                    30
                  <?php } else { ?>
                    00
                  <?php } ?>
                </td>
                <?php for ($num = 0; $num < 7; ++$num) { ?>
                  <td>
                    <?php if (true === $sch['state'][$num]) { ?>
                      <input class="check inputCheckbox" id="<?php echo $sch['dt'][$num]; ?>" name="administrator_defaultNoReserve_array[]" value="<?php echo $sch['dt'][$num]; ?>" type="checkbox">
                      <label class="label -checkbox table-text_o" for="<?php echo $sch['dt'][$num]; ?>">〇</label>
                    <?php
                      // ---------- 予定が埋まっている場合 ----------
                    } elseif (false === $sch['state'][$num]) {

                    ?>
                      <input class="check" id="<?php echo $sch['dt'][$num]; ?>" name="administrator_defaultNoReserve_array[]" value="<?php echo $sch['dt'][$num]; ?>" checked type="checkbox">
                      <label class="label table-text_o" style="color: #707070;" for="<?php echo $sch['dt'][$num]; ?>">
                        ×
                      </label>

                    <?php } ?>
                  </td>
                <?php } ?>
              </tr>
            <?php } ?>
          </table>

        </form>
      </div>
      <div class="-tac">
        <button type="button" class="send">更新する</button>

      </div>
    </div>
  </section>
</section>

<script>
  $(function() {
    $('.send').on('click', function() {
      $('.calendarForm__multiple').submit()
    })
  });
</script>