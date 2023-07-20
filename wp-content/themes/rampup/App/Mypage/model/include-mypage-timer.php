<?php

$user_id = get_current_user_id();



use Carbon\Carbon;

global $wpdb;

/*--------------------------------------------------
/* タイマーの値を保存する
/*------------------------------------------------*/
if (isset($_POST['timerPostTrigger'])) {
  $user_id = get_current_user_id();

  // ---------- 学習スタートをクリックした日時 ----------
  $startDate_startTime = $_POST["startDate_startTime"];
  $startDate_startTimeEx = explode(" ", $startDate_startTime)[0];

  // ---------- 学習スタートをクリックした日と部分一致する ----------
  // ---------- wp_usermetaの値(配列) ----------
  $user_startDate = $wpdb->get_row(
    '
      SELECT * FROM ' . $wpdb->usermeta . '
      WHERE user_id =' . '"' . $user_id . '"
      AND meta_key LIKE"' . $startDate_startTimeEx . '%"
    '
  );

  // ---------- 勉強時間と休憩時間 ----------
  $timer_studySS = $_POST["timer_study"];
  $timer_pauseSS = $_POST["timer_pause"];

  // ---------- 日付を跨いだ時の処理に使う変数 ----------
  $startDate = new Carbon($startDate_startTime);
  $startDate_startOfDate = $startDate->copy()->startOfDay();
  $startDate_endOfDate = $startDate->copy()->endOfDay();

  // ---------- 学習開始時間とその日の00:00:00の差分 ----------
  $startDate_beforeStart =  $startDate->diffInSeconds($startDate_startOfDate);

  // ---------- 明日の学習時間 ----------
  $timer_studySS_tomorrow = ($startDate_beforeStart + $timer_studySS) - 86400; // 86400 = 1日

  // ---------- 次の日の00:00:00 ----------
  $tomorrowDate_startOfDate = $startDate_startOfDate->copy()->addDay()->format("Y-m-d 00:00:00");
  // ---------- 日付を跨いで勉強した場合 ----------
  if ($timer_studySS_tomorrow > 0) {

    // ---------- 今日の学習時間 ----------
    $timer_studySS_today = $timer_studySS - $timer_studySS_tomorrow;

    // ---------- 分に変換 ----------
    $timer_studySS_tomorrowMin = floor($timer_studySS_tomorrow / 60);

    // ---------- 明日の日付でDBに値保存 ----------
    update_user_meta($user_id, $tomorrowDate_startOfDate, $timer_studySS_tomorrowMin);
  }

  // ---------- 日付を跨いで勉強しなかった場合 ----------
  else {
    // ---------- 今日の学習時間 ----------
    $timer_studySS_today = $timer_studySS;
  }

  // ---------- 元のデータがあったらプラスする ----------
  if ($user_startDate->meta_value) {
    $timer_studySS_todayMin =
      $user_startDate->meta_value +
      floor($timer_studySS_today / 60);
    $startDate_startTime = $user_startDate->meta_key;
  }
  // ---------- 元のデータがなければそのままDBに値保存 ----------
  else {
    $startDate_startTime = $startDate_startOfDate->copy()->format("Y-m-d 00:00:00");
    $timer_studySS_todayMin = floor($timer_studySS_today / 60);
  }

  update_user_meta($user_id, $startDate_startTime, $timer_studySS_todayMin);

  wp_safe_redirect(get_permalink());
  exit;
}

/*--------------------------------------------------
/* 保存された学習記録を保存する
/*------------------------------------------------*/
if (isset($_POST["changeRecord__formTrigger"])) {
  // ---------- 変数 ----------
  $changeRecord_timeTotalInput = $_POST["changeRecord__timeTotalInput"];
  $changeRecord_date = $_POST["changeRecord__date"];
  $changeRecord_inputHours = $_POST["changeRecord__inputHours"];
  $changeRecord_inputHours60 = $changeRecord_inputHours * 60;
  $changeRecord_inputMinutes = $_POST["changeRecord__inputMinutes"];
  $changeRecord_inputTotal = $changeRecord_inputHours60 + $changeRecord_inputMinutes;

  $class_user = new Rampup_User($user_id);

  // ---------- wp_usermetaの値(配列) ----------
  $user_recordDate = $wpdb->get_row(
    '
                SELECT * FROM ' . $wpdb->usermeta . '
                WHERE user_id =' . '"' . $user_id . '"
                AND meta_key LIKE"' . $changeRecord_date . '%"
          '
  );

  $c_userMetaMax = get_user_meta($user_id, "max-" . $changeRecord_date, true);

  $changeRecord_limit = $_POST["changeRecord__originValue"];

  // ---------- DB値 変更 ----------
  // ---------- DB値に既存の値があったら ----------
  if ($user_recordDate) {
    update_user_meta($user_id, $user_recordDate->meta_key, $changeRecord_inputTotal);
  }
  // ---------- DB値に既存の値がなかったら ----------
  else {
    update_user_meta($user_id, $changeRecord_date . " 00:00:00", $changeRecord_inputTotal);
  }

  // ---------- リダイレクト ----------
  wp_safe_redirect(get_current_link());
  exit;
}