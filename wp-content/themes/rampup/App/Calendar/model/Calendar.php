<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/

// ---------- ページ生成関数の読み込み ----------
include(get_theme_file_path() . "/App/Calendar/model/calendar-variables.php");
include_once get_theme_file_path() . "/App/Calendar/model/create-page.php";
require_once(get_theme_file_path() . "/App/vendor/autoload.php");

class Calendar
{
  private $dt;
  private $now;
  public $weekdays;
  public $dayweeks;
  public $status;
  public $year;
  public $month;
  public $this_dt;

  public function __construct($which, $status)
  {
    global $site_opening_time,
      $site_closing_time,
      $site_closing_time_sub30Min,
      $site_numberOfCalendarCell;
    $site_opening_time_carbon = new Carbon($site_opening_time);
    $this->addHour = $site_opening_time_carbon->hour;
    $this->dt = Carbon::today('Asia/Tokyo')->addHour($this->addHour); //何時から表示を始めるか
    $this->now = Carbon::now('Asia/Tokyo');
    $this->status = $this->setStatus($which, $status);
    $this->weekdays = $this->setWeekDays();
    $this->dayweeks = $this->setDayWeeks($this->weekdays);
    $this->this_dt = $this->dt;

    // ---------- 面談一回あたりのコマ数 ----------
    $this->site_numberOfCalendarCell = $site_numberOfCalendarCell;
    $this->site_numberOfCalendarCell_minus30min = $this->site_numberOfCalendarCell - 30;
    $this->site_numberOfCalendarCell_minus60min = $this->site_numberOfCalendarCell - 60;

    // ---------- 就業時間 ----------
    $this->site_closing_time_sub30Min = $site_closing_time_sub30Min;
    $this->site_closing_time = $site_closing_time;
    $this->site_opening_time = $site_opening_time;

    // ---------- multiple ----------
    $this->weekDays_fromMon = $this->setWeekDays_fromMon();
  }

  private function setStatus($which, $state)
  {
    //現在の値を吟味し、何日ずらして表示するか
    //which どちらを押したか
    //state 現在から何週間進んだか
    $value = 0;
    if ('plus' === $which) {
      $value = intval($state) + 1;
      $this->dt->addDay($value * 7);
    } elseif ('minus' === $which) {
      $value = intval($state) - 1;
      $this->dt->addDay($value * 7);
    } else {
    }
    return $value;
  }


  private function setWeekDays()
  {
    $list = [];
    for ($day = 0; $day < 7; ++$day) {
      $list[] = $this->dt->copy()->addDay($day);
    }
    return $list;
  }


  /*--------------------------------------------------
  /* 月曜日から始める一週間を表示
  /*------------------------------------------------*/
  public function setWeekDays_fromMon()
  {
    $monday = $this->now->startOfWeek();
    $list_fromMon = [];

    $day = 0;
    while ($day < 7) {
      $list_fromMon[] = $monday->copy()->addDay($day);
      ++$day;
    }

    return $list_fromMon;
  }

  private function setDayWeeks($weekdays)
  {
    $dayweeks = [];
    //曜日を決定してリストに格納
    foreach ($weekdays as $wd) {
      if (1 === $wd->dayOfWeek) {
        $dayweeks[] = '月';
      } elseif (2 === $wd->dayOfWeek) {
        $dayweeks[] = '火';
      } elseif (3 === $wd->dayOfWeek) {
        $dayweeks[] = '水';
      } elseif (4 === $wd->dayOfWeek) {
        $dayweeks[] = '木';
      } elseif (5 === $wd->dayOfWeek) {
        $dayweeks[] = '金';
      } elseif (6 === $wd->dayOfWeek) {
        $dayweeks[] = '土';
      } else {
        $dayweeks[] = '日';
      }
    }

    return $dayweeks;
  }

  private function outputCarbon($dt)
  {
    $carbon = new Carbon($dt, 'Asia/Tokyo');
    $carbon->format('Y-m-d H:i:s');

    return $carbon;
  }

  /*--------------------------------------------------
  /* 予約された箇所をバツにする処理
  /*------------------------------------------------*/
  private function outputDb($params)
  {
    global $wpdb, $user_administrators_array, $site_reservation_personInCharge_user_id;

    // ---------- 担当者のユーザーID取得 ----------
    if (isset($_GET['reservation_personInCharge_user_id'])) {
      $reservation_personInCharge_user_id = (int)esc_attr($_GET['reservation_personInCharge_user_id']);
    } else {
      $reservation_personInCharge_user_id = (int)$site_reservation_personInCharge_user_id;
    }

    $today = Carbon::today()->format("Y-m-d");
    $args = array(
      'post_type' => 'reservation',
      'posts_per_page' => -1,
      'meta_key' => 'reservation_date',
      'post_status' => ['scheduled', 'noreserve'],
      // 'post_status' => 'any',
      'meta_query' => [
        [
          'key'     => 'reservation_date',
          'value'   => $today,
          'type'   => 'DATE',
          'compare' => '>=',
        ]
      ],
    );
    // wp_cache_flush();
    $result = get_posts($args);
    foreach ($result as $item) {
      // ---------- 変数定義 ----------
      $reservation_post_id = $item->ID;
      $target_reservation_personInCharge_user_id = (int)get_post_meta($reservation_post_id, 'reservation_personInCharge_user_id', true);
      $target_reservation_date = get_post_meta($reservation_post_id, 'reservation_date', true);
      $reservation_post_status = get_post_status($reservation_post_id);
      $target = new Carbon("$target_reservation_date", 'Asia/Tokyo');

      // ---------- 「実施予定」だった場合 ----------
      if ($reservation_post_status === 'scheduled') {
        // ---------- (面談時間 - 30分)のコマ数を予約日時の前後それぞれにつける ----------
        $target_forward = $target->copy()->addMinutes($this->site_numberOfCalendarCell_minus30min);
        $target_backward = $target->copy()->subMinutes($this->site_numberOfCalendarCell_minus30min);
        $periods = CarbonPeriod::create($target_backward, $target_forward)->minutes(30);

        foreach ($periods as $period) {
          if ($target_reservation_personInCharge_user_id === $reservation_personInCharge_user_id) {
            if ($params->eq($period)) {
              return true;
            }
          }
        }
      }
      // ---------- 「実施不可」の場合 ----------
      elseif ($reservation_post_status == 'noreserve') {
        if ($params->eq($target)) {
          return true;
        }
      } else {
        continue;
      }
    }

    // // ---------- reservation_dateのメタキーを持っている投稿をロープで回す ----------
    // $result = $wpdb->get_results("SELECT `post_id`, `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE meta_key = 'reservation_date' ");
    // $result = get_posts($args);
    // foreach ($result as $item) {
    //   // ---------- 変数定義 ----------
    //   $reservation_post_id = $item->post_id;
    //   $target_reservation_personInCharge_user_id = get_post_meta($reservation_post_id, 'reservation_personInCharge_user_id', true);
    //   $reservation_post_status = get_post_status($reservation_post_id);
    //   $target = new Carbon("$item->meta_value", 'Asia/Tokyo');

    //   // ---------- 「実施予定」だった場合 ----------
    //   if ($reservation_post_status == 'scheduled') {
    //     // ---------- (面談時間 - 30分)のコマ数を予約日時の前後それぞれにつける ----------
    //     $target_forward = $target->copy()->addMinutes($this->site_numberOfCalendarCell_minus30min);
    //     $target_backward = $target->copy()->subMinutes($this->site_numberOfCalendarCell_minus30min);
    //     $periods = CarbonPeriod::create($target_backward, $target_forward)->minutes(30);

    //     foreach ($periods as $period) {
    //       if ($target_reservation_personInCharge_user_id == $reservation_personInCharge_user_id) {
    //         if ($params->eq($period)) {
    //           return true;
    //         }
    //       }
    //     }
    //   }
    //   // ---------- 「実施不可」の場合 ----------
    //   elseif ($reservation_post_status == 'noreserve') {
    //     if ($params->eq($target)) {
    //       return true;
    //     }
    //   } else {
    //     continue;
    //   }
    // }

    // return false;
  }

  private function outputDb_noreserve($params)
  {
    global $wpdb, $user_administrators_array, $site_reservation_personInCharge_user_id;

    // ---------- 担当者のユーザーID取得 ----------
    if (!is_nullorempty($_GET['reservation_personInCharge_user_id'])) {
      $reservation_personInCharge_user_id = (int)esc_attr($_GET['reservation_personInCharge_user_id']);
    } else {
      $reservation_personInCharge_user_id = (int)$site_reservation_personInCharge_user_id;
    }
    // echo '<pre>';
    // var_dump($site_reservation_personInCharge_user_id);
    // var_dump($reservation_personInCharge_user_id);
    // echo '</pre>';

    $today = Carbon::today()->format("Y-m-d");
    $args = array(
      'post_type' => 'reservation',
      'posts_per_page' => -1,
      'meta_key' => 'reservation_date',
      'post_status' => 'noreserve',
      'meta_query' => [
        [
          'key'     => 'reservation_date',
          'value'   => $today,
          'type'   => 'DATE',
          'compare' => '>=',
        ]
      ],
    );
    // wp_cache_flush();
    $result = get_posts($args);
    foreach ($result as $item) {
      // ---------- 変数定義 ----------
      $reservation_post_id = $item->ID;
      $target_reservation_personInCharge_user_id = (int)get_post_meta($reservation_post_id, 'reservation_personInCharge_user_id', true);
      $target_reservation_date = get_post_meta($reservation_post_id, 'reservation_date', true);
      $reservation_post_status = get_post_status($reservation_post_id);
      $target = new Carbon($target_reservation_date, 'Asia/Tokyo');

      // ---------- 「実施予定」だった場合 ----------
      if ($reservation_post_status === 'noreserve') {
        // ---------- (面談時間 - 30分)のコマ数を予約日時の前後それぞれにつける ----------
        // $target_forward = $target->copy()->addMinutes($this->site_numberOfCalendarCell_minus30min);
        // $target_backward = $target->copy()->subMinutes($this->site_numberOfCalendarCell_minus30min);
        // $target_forward = $target->copy()->addMinutes($this->site_numberOfCalendarCell_minus30min);
        $target_backward = $target->copy();
        // $periods = CarbonPeriod::create($target_backward, $target_forward)->minutes(30);
        $periods = CarbonPeriod::create($target_backward, $target_backward)->minutes(30);
        // echo '<pre>';
        // var_dump($target);
        // echo '</pre>';
        
        // foreach ($target as $period) {
          foreach ($periods as $period) {
            // echo '<pre>';
            // var_dump($period);
            // echo '</pre>';
            if ($target_reservation_personInCharge_user_id === $reservation_personInCharge_user_id) {
            if ($params->eq($period)) {
              return true;
            }
          }
        }
      // }
      // // ---------- 「実施不可」の場合 ----------
      // if ($reservation_post_status == 'noreserve') {
      //   if ($params->eq($target)) {
      //     return true;
      //   }
      } else {
        continue;
      }
    }

    // // ---------- reservation_dateのメタキーを持っている投稿をロープで回す ----------
    // $result = $wpdb->get_results("SELECT `post_id`, `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE meta_key = 'reservation_date' ");
    // $result = get_posts($args);
    // foreach ($result as $item) {
    //   // ---------- 変数定義 ----------
    //   $reservation_post_id = $item->post_id;
    //   $target_reservation_personInCharge_user_id = get_post_meta($reservation_post_id, 'reservation_personInCharge_user_id', true);
    //   $reservation_post_status = get_post_status($reservation_post_id);
    //   $target = new Carbon("$item->meta_value", 'Asia/Tokyo');

    //   // ---------- 「実施予定」だった場合 ----------
    //   if ($reservation_post_status == 'scheduled') {
    //     // ---------- (面談時間 - 30分)のコマ数を予約日時の前後それぞれにつける ----------
    //     $target_forward = $target->copy()->addMinutes($this->site_numberOfCalendarCell_minus30min);
    //     $target_backward = $target->copy()->subMinutes($this->site_numberOfCalendarCell_minus30min);
    //     $periods = CarbonPeriod::create($target_backward, $target_forward)->minutes(30);

    //     foreach ($periods as $period) {
    //       if ($target_reservation_personInCharge_user_id == $reservation_personInCharge_user_id) {
    //         if ($params->eq($period)) {
    //           return true;
    //         }
    //       }
    //     }
    //   }
    //   // ---------- 「実施不可」の場合 ----------
    //   elseif ($reservation_post_status == 'noreserve') {
    //     if ($params->eq($target)) {
    //       return true;
    //     }
    //   } else {
    //     continue;
    //   }
    // }

    // return false;
  }

  /*--------------------------------------------------
  /* 終業時刻から面談一回あたりの時間に応じてカレンダーを「×」にする
  /*------------------------------------------------*/
  private function outputDb_closing_time($params)
  {
    $site_closing_time = new Carbon($this->site_closing_time); // 終業時刻
    $site_closing_time_backward = $site_closing_time->copy()->subMinutes($this->site_numberOfCalendarCell_minus30min); // 終業時刻から1時間引いた時刻
    $site_closing_time_periods = CarbonPeriod::create($site_closing_time_backward, $site_closing_time)->minutes(30); // 上記2つの30分ごとの時間の差を配列に格納
    foreach ($site_closing_time_periods as $site_closing_time_period) {
      if (
        $params->isSameAs('H', $site_closing_time_period) && // 時間(H)が同じ値だったら
        $params->isSameAs('i', $site_closing_time_period) // 分(i)が同じ値だったら
      ) {
        return true;
      }
    }
  }

  /*--------------------------------------------------
  /* 担当者がデフォルトで出れない箇所を「×」にする
  /*------------------------------------------------*/
  private function outputDb_multiple($params)
  {

    global $wpdb, $user_administrators_array, $site_reservation_personInCharge_user_id;

    if ($_GET['reservation_personInCharge_user_id']) {
      $reservation_personInCharge_user_id = esc_attr($_GET['reservation_personInCharge_user_id']);
    } else {
      $reservation_personInCharge_user_id = $site_reservation_personInCharge_user_id;
    }

    $administrator_defaultNoReserve_json = get_user_meta($reservation_personInCharge_user_id, 'administrator_defaultNoReserve_json', true);

    $administrator_defaultNoReserve_array = json_decode($administrator_defaultNoReserve_json);
    if (!empty($administrator_defaultNoReserve_array)) {

      foreach ($administrator_defaultNoReserve_array as $administrator_defaultNoReserve) {
        $dayOfWeekIso = $administrator_defaultNoReserve->dayOfWeekIso;
        $hour = $administrator_defaultNoReserve->hour;
        $mitute = $administrator_defaultNoReserve->mitute;

        if (
          $params->isSameAs('H', $administrator_defaultNoReserve) && // 時間(H)が同じ値だったら
          $params->isSameAs('i', $administrator_defaultNoReserve) && // 分(i)が同じ値だったら
          $params->isSameAs('w', $administrator_defaultNoReserve) // 同じ曜日だったら
        ) {
          return true;
        }
      }
    }
  }

  public function outSchedule()
  {
    $tomorrow = Carbon::tomorrow()->addDay(); //明後日
    $schedule = [];
    global $wpdb;

    $site_closing_time = new Carbon($this->site_closing_time_sub30Min);
    $site_opening_time = new Carbon($this->site_opening_time);
    $diffInMinutes =  $site_opening_time->diffInMinutes($site_closing_time) / 30;

    // for ($min = 0; $min <= $diffInMinutes; ++$min) {
    $min = 0;
    while ($min <= $diffInMinutes) {
      $minutes = $min * 30;
      ++$min;
      $datetimes = [];
      $states = [];
      $day = 0;
      
      while ($day < 7) {
        

        $past = $this->dt->copy()->addMinute($minutes)->addDays($day);
        // $past = $this->dt->copy()->addMinute($minutes);
        0 === $day ? $this->this_dt = $past : null;
        if ($this->outputDb($past)) {
          $reserve = $this->outputDb($past);
        } elseif ($this->outputDb_multiple($past)) {
          $reserve = $this->outputDb_multiple($past);
        } elseif ($this->outputDb_noreserve($past)) {
          $reserve = $this->outputDb_noreserve($past);
        } else {
          $reserve = $this->outputDb_closing_time($past);
        }

        $datetimes[] = $past;
        if ($reserve) {
          $states[] = false;
        } else {
          $states[] = $past->gt($tomorrow);
        }
        ++$day;
      }

      $data = [
        'state' => $states,
        'dt' => $datetimes,
      ];
      $data += ['dt' => $datetimes, 'state' => $past];
      $schedule[] = $data;
    }

    return $schedule;
  }

  /*--------------------------------------------------
  /* 担当者ごとのカレンダー出力
  /*------------------------------------------------*/
  public function outSchedule_multiple()
  {
    $tomorrow = Carbon::tomorrow()->addDay(); //明後日
    $schedule = [];
    global $wpdb;

    $site_closing_time = new Carbon($this->site_closing_time_sub30Min);
    $site_opening_time = new Carbon($this->site_opening_time);
    $diffInMinutes =  $site_opening_time->diffInMinutes($site_closing_time) / 30;

    $monday = $this->now->startOfWeek()->addHours($this->addHour);

    $min = 0;
    while ($min <= $diffInMinutes) {
      $minutes = $min * 30;
      ++$min;
      $datetimes = [];
      $states = [];

      $day = 0;
      while ($day < 7) {
       
        $past = $monday->copy()->addMinute($minutes)->addDay($day);
        0 === $day ? $this->this_dt = $past : null;
        $reserve =  ($this->outputDb($past)) ? $this->outputDb($past) : $this->outputDb_closing_time($past);


        if ($this->outputDb_multiple($past)) {
          $reserve = $this->outputDb_multiple($past);
        } else {
          $reserve = $this->outputDb_closing_time($past);
        }

        $datetimes[] = $past;


        if (
          $reserve
        ) {
          $states[] = false;
        } else {
          $states[] = $past->gt($this->today);
        }
        ++$day;
      }
      $data = [
        'state' => $states,
        'dt' => $datetimes,

      ];
      $data += ['dt' => $datetimes, 'state' => $past];
      $schedule[] = $data;
    }

    return $schedule;
  }
}

