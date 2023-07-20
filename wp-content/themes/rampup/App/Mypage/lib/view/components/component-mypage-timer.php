<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage-timer.css') ?>">



<div class="mypageTimer">
  <h1 class="mypage__mainChildTitle">学習時間の記録</h1>

  <?php

  use Carbon\Carbon;
  use Carbon\CarbonPeriod;

  // do_action('component_mypage_timer__send_timer_form')

  ?>
  <div class="mypageTimer__stopwatch">
    <h2 class="mypageTimer__stopwatchTitle">今日の学習時間</h2>
    <div class="mypageTimer__stopwatchFlex">
      <div class="mypageTimer__stopwatchNumberWrap">
        <span class="mypageTimer__stopwatchNumber " id="js__stopwatch">00:00:00</span>

        <form action="<?php echo get_permalink(); ?>" method="POST" id="timerForm">
          <input type="hidden" name="timerPostTrigger">
          <input id="startDate_startTime" type="hidden" name="startDate_startTime" value="">
          <input id="startDate_endTime" type="hidden" name="startDate_endTime" value="">
          <input id="timer_study" type="hidden" name="timer_study" value="">
          <input id="timer_pause" type="hidden" name="timer_pause" value="">
        </form>
      </div>

      <div class="mypage__timerButtons">
        <button onclick="start()" class="mypage__button" id="js__timer__start">学習スタート</button>

        <button onclick="stop()" class="mypage__button -disabled" id="js__timer__stop">学習終了</button>
      </div>
    </div>
  </div>

  <?php
  include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-studyCalendar.php") ?>

</div>
