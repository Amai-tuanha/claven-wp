<?php

use Carbon\Carbon;

include(get_theme_file_path() . "/App/Calendar/model/include-spot-noreserve.php") ?>
<?php
/**
 * Template Name: 一コマ予約不可
 * @package WordPress
 * @Template Post Type: post, page,
 * @subpackage I'LL
 */
get_header(); ?>

<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-user-edit.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('component-calendar.css') ?>">
<?php if (current_user_can('administrator')) { ?>

  <div class="project__wrap calendar">
    <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php")
    ?>

    <div class="project__contents -bg__none">

      <div class="contents__width">
        <!-- <div class="controlpanel-useredit__form h-adr"> -->

          <section class="list__wrap6">

            <div class="list__tableInformation">

              <h2 class="list__titleBox">予約不可設定</h2>
              <div class="list__textBox">

                <?php
                $today = Carbon::today()->format("Y-m-d");
                $get_reservation_personInCharge_user_id = esc_attr($_GET['reservation_personInCharge_user_id']);

                if ($get_reservation_personInCharge_user_id) {
                  $reservation_personInCharge_user_id = (int)$get_reservation_personInCharge_user_id;
                } else {
                  $reservation_personInCharge_user_id = (int)$site_reservation_personInCharge_user_id;
                }
                ?>
                <!-- <div class="inner"> -->
                  <div class="calendar__top">
                    <div class="calendar__boxWrap -tac">

                      <span>予約不可にする日程をお選びください</span>
                    </div>
                  </div>
                  <div class="calendar__top">
                    <form action="<?= get_current_link() . '#calendarForm' ?>" method="GET" class="calendar__boxWrap -tac">
                      <label class="calendar__inputLabel" for="select_personInCharge">担当者を選択</label>
                      <select class="calendar__input" id="select_personInCharge" onchange="onChengeSendForm(event)" name="reservation_personInCharge_user_id">

                        <?php

                        foreach ($site_reservation_personInCharge_user_id_array as $personInCharge_user_id) {
                          $class_user = new Rampup_User($personInCharge_user_id);
                          if ($reservation_personInCharge_user_id === (int)$personInCharge_user_id) {
                            $selected = 'selected';
                          } else {
                            $selected = null;
                          }
                        ?>
                          <option <?= $selected ?> value="<?= $personInCharge_user_id ?>">
                            <?= $class_user->user_displayName ?>
                          </option>
                        <?php } ?>
                      </select>
                    </form>
                  </div>
                  <div class="calendar__nextPrev" style="margin:0; width:100%;">
                    <form method="POST" action="#calendarForm">
                      <div class="calendar__nextPrevForm">
                        <button onclick="location.href='/#calendarForm'" class="calendar__prevButton  content-shadow" name="which" value="minus" type="submit">
                          <i class="fas fa-arrow-circle-left"></i> 前の週</button>

                        <button class="calendar__nextButton content-shadow" name="which" value="plus" type="submit">次の週
                          <i class="fas fa-arrow-circle-right"></i>
                        </button>
                      </div>
                      <input type="hidden" name="state" value="<?php echo htmlspecialchars($_POST['state']); ?>">
                    </form>
                  </div>

                  <?php

                  $today = Carbon::today()->format("Y-m-d");
                  $args = array(
                    'post_type' => 'reservation',
                    'posts_per_page' => -1,
                    // 'post_status' => array( 'scheduled', 'noreserve' ),
                    'meta_key' => 'reservation_date',
                    // 'post_status' => ['scheduled', 'noreserve'],
                    // 'post_status' => 'noreserve',
                    // 'post_status' => 'scheduled',
                    'post_status' => 'any',
                    'meta_query' => [
                      [
                        'key'     => 'reservation_date',
                        'value'   => $today,
                        'type'   => 'DATE',
                        'compare' => '>=',
                      ]
                    ],
                  );
                  $result = get_posts($args);

                  ?>

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
                        <input type="hidden" name="reservation_personInCharge_user_id" value="<?= $user_id ?>">
                        <form method="POST" id="noreserve_calendar" action="">
                          <input type="hidden" id="calendar_action" name="calendar_action" value="add_calendar">


                          <?php
                          if (is_page('mypage-change-reservation')) {
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
                                      <input class="check" id="<?php echo $sch_dt_num; ?>" name="booking[]" value="<?php echo $sch_dt_num; ?>" type="checkbox">
                                      <label class="label table-text_o" for="<?php echo $sch_dt_num; ?>">〇</label>

                                    <?php } elseif (false === $sch_state_num) { ?>
                                      <!-- <input class="check" id="<?php echo $sch_dt_num; ?>" name="cancel[]" value="<?php echo $sch_dt_num; ?>" checked type="checkbox"> -->
                                      <input class="check cancel_input" id="<?php echo $sch_dt_num; ?>" name="cancel[]" value="<?php echo $sch_dt_num; ?>" type="checkbox">
                                      <label class="label table-text_o cancel_label" style="color: #707070; background-color:#d0d0d0;" for="<?php echo $sch_dt_num; ?>">×</label>
                                    <?php } ?>
                                  </td>
                                <?php } ?>
                              </tr>
                            <?php } ?>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="-tac">
                      <button type="submit" class="send" form="noreserve_calendar">更新する</button>

                    </div>
                  </section>

                <!-- </div> -->
              </div>
            </div>

          </section>

        <!-- </div> -->

      </div>
    </div>
  </div>
<?php } else {
  wp_safe_redirect(home_url());
} ?>

<script>
  // ---------- ×がついている日程をチェックされたときに背景色を変更する。 ----------
  jQuery('.cancel_input').change(function() {
    if (jQuery(this).prop('checked')) {
      console.log(this.parentNode.childNodes[5]);
      this.parentNode.childNodes[5].style.backgroundColor = "#ffffff"
    } else {
      this.parentNode.childNodes[5].style.backgroundColor = "#d0d0d0"
      console.log('チェクはずれました。');
    }
  });
</script>
<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<!-- end content-->
<?php get_footer();
