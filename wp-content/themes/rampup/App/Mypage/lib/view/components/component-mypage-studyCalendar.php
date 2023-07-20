<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

// do_action('component_mypage_studyCalendar__display_studyCalendar');
/*--------------------------------------------------
/* 変数等
/*------------------------------------------------*/

global $wpdb, $weekday_jap;
$user_id = get_current_user_id();

if ('GET' === $_SERVER['REQUEST_METHOD']) {

	// ---------- 日毎カレンダー ----------
	if (
		$_GET["period"] == "days"
		|| $_GET["period"] == ""
	) {
		$c_mode = "days";
		//  // ---------- 基準日 ----------
		$c_basis = "today";
		$c_lastOfPeriod = new Carbon($c_basis);
		// ---------- 1週間前 ----------

		// ---------- 前後の期間 ----------
		if (isset($_GET["pagination"])) {
			$another_pagination = new Carbon($_GET["pagination"]);
			$c_lastOfPeriod = $another_pagination;
			$prev_pagination = $another_pagination->copy()->subDays(7);
			$next_pagination = $another_pagination->copy()->addDays(7);
		} else {
			$prev_pagination = new Carbon("-7 days");
			$next_pagination = new Carbon("+7 days");
		}
		$c_startOfPeriod = $c_lastOfPeriod->copy()->subDays(6);
		$c_lastOfPeriodPagination = $c_lastOfPeriod->format("n月j日");
	} elseif (
		$_GET["period"] == "month"
		|| $_GET["period"] == "year"
	) {


		// ---------- 月間カレンダー ----------
		if ($_GET["period"] == "month") {

			// ---------- 前後の期間 ----------
			if (isset($_GET["pagination"])) {
				$another_pagination = new Carbon($_GET["pagination"]);
				$another_pagination = $another_pagination->copy()->startOfMonth();
				$c_startOfPeriod = $another_pagination;
				$prev_pagination = $another_pagination->copy()->subMonth();
				$next_pagination = $another_pagination->copy()->addMonth();
			}
			// ---------- 初期値 ----------
			else {
				$prev_pagination = new Carbon("first day of last month");
				$next_pagination = new Carbon("first day of next month");

				// ---------- 基準日 ----------
				$c_basis = "first day of this month";
				$c_startOfPeriod = new Carbon($c_basis);
			}

			// ---------- 期間の最後の日にち ----------
			$c_lastOfPeriod = $c_startOfPeriod->copy()->endOfMonth();
			$c_lastOfPeriodPagination = $c_lastOfPeriod->format("n月j日");
		}

		// ---------- 年間カレンダー ----------
		else if ($_GET["period"] == "year") {
			$c_mode = "year";
			// ---------- 基準日 ----------



			// ---------- 前後の期間 ----------
			if (isset($_GET["pagination"])) {
				$another_pagination = new Carbon($_GET["pagination"]);
				$another_pagination = $another_pagination->copy()->startOfYear();
				$c_startOfPeriod = $another_pagination;
				$prev_pagination = $another_pagination->copy()->subYear();
				$next_pagination = $another_pagination->copy()->addYear();
			}
			// ---------- 初期値 ----------
			else {
				$prev_pagination = new Carbon("-1 year");
				$next_pagination = new Carbon("+1 year");

				// ---------- 基準日 ----------
				$c_basis = "today";
				$c_startOfPeriod = new Carbon($c_basis);
				$c_startOfPeriod = $c_startOfPeriod->copy()->startOfYear();
			}

			// ---------- 期間の最後の日にち ----------
			$c_lastOfPeriod = $c_startOfPeriod->copy()->endOfYear();
			$c_lastOfPeriodPagination = $c_lastOfPeriod->format("n月j日");
		}

		// ---------- 基準日 ----------


	} else {
		$c_mode = "days";
		$c_basis = "today";
		// ---------- 基準日 ----------
		$c_lastOfPeriod = new Carbon("today");
		// ---------- 1週間前 ----------
		$c_startOfPeriod = $c_lastOfPeriod->copy()->subDays(6);
	}
}
// ---------- 初期値 ----------
else {
	$c_mode = "days";
	$c_basis = "today";
	// ---------- 基準日 ----------
	$c_lastOfPeriod = new Carbon("today");
	// ---------- 1週間前 ----------
	$c_startOfPeriod = $c_lastOfPeriod->copy()->subDays(6);
}

$period = CarbonPeriod::create($c_startOfPeriod, $c_lastOfPeriod);
?>
<div class="mypageTimer__calendarWrap">
  <ul class="mypageTimer__calendarSwitch">
    <li class="mypageTimer__calendarSwitchChild">
      <form class="mypageTimer__calendarSwitchForm"
            action="<?php echo get_permalink(); ?>"
            method="GET">
        <?php rampup_GETS(['period', 'pagination']) ?>

        <?php if (current_user_can('administrator')) { ?>
        <input type="hidden"
               name="user_target"
               value="administrator">
        <?php } ?>

        <button class="mypageTimer__calendarSwitchButton <?php if ($_GET["period"] == "days" || $_GET["period"] == "") {
																														echo "-active";
																													} ?>"
                type="submit"
                name="period"
                value="days">週</button>
      </form>
    </li>

    <li class="mypageTimer__calendarSwitchChild">
      <form class="mypageTimer__calendarSwitchForm"
            action="<?php echo get_permalink(); ?>"
            method="GET">
        <?php rampup_GETS(['period', 'pagination']) ?>
        <?php if (current_user_can('administrator')) { ?>
        <input type="hidden"
               name="user_target"
               value="administrator">
        <?php } ?>

        <button class="mypageTimer__calendarSwitchButton <?php if ($_GET["period"] == "month") {
																														echo "-active";
																													} ?>"
                type="submit"
                name="period"
                value="month">月</button>
      </form>
    </li>

    <li class="mypageTimer__calendarSwitchChild">
      <form class="mypageTimer__calendarSwitchForm"
            action="<?php echo get_permalink(); ?>"
            method="GET">
        <?php rampup_GETS(['period', 'pagination']) ?>
        <?php if (current_user_can('administrator')) { ?>
        <input type="hidden"
               name="user_target"
               value="administrator">
        <?php } ?>

        <button class="mypageTimer__calendarSwitchButton <?php if ($_GET["period"] == "year") {
																														echo "-active";
																													} ?>"
                type="submit"
                name="period"
                value="year">年</button>
      </form>
    </li>
  </ul>

  <div class="mypageTimer__calendarPagination">
    <div class="mypageTimer__calendarPaginationLeft">
      <form class="mypageTimer__calendarPaginationForm -prev"
            action="<?php echo get_permalink(); ?>"
            method="GET">
        <?php rampup_GETS(['pagination']) ?>
        <?php if (current_user_can('administrator')) { ?>
        <input type="hidden"
               name="user_target"
               value="administrator">
        <?php } ?>
        <!-- <input type="hidden"
               name="period"
               value="<?= $_GET["period"] ?>"> -->

        <button class="mypageTimer__calendarPaginationButton"
                type="submit"
                name="pagination"
                value="<?= $prev_pagination ?>">
          <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_left_triangle_white_1.svg"
               alt="左三角">
        </button>
      </form>

      <h2 class="mypageTimer__calendarPaginationDate">
				<?= $c_startOfPeriod->format("Y年n月j日") . "〜" . $c_lastOfPeriodPagination ?>
			</h2>
    </div>
    <form class="mypageTimer__calendarPaginationForm"
          action="<?php echo get_permalink(); ?>"
          method="GET">
      <?php rampup_GETS(['pagination']) ?>
      <?php if (current_user_can('administrator')) { ?>
      <input type="hidden"
             name="user_target"
             value="administrator">
      <?php } ?>
      <!-- <input type="hidden"
             name="period"
             value="<?= $_GET["period"] ?>"> -->

      <button class="mypageTimer__calendarPaginationButton"
              type="submit"
              name="pagination"
              value="<?= $next_pagination ?>">
        <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_right_triangle_white_1.svg"
             alt="右三角">
      </button>

    </form>
  </div>

  <?php
	// ---------- ユーザーメタの配列を作る ----------
	$c_userDataArray = [];

	// ---------- 年間カレンダーの時の配列の処理 ----------
	$c_userDataArray_month = [];
	$c_userDataArray_year = [];
	$a = 0;
	while ($a <= 11) {
		$a++;
		$c_userDataArray_year[$a] = $c_userDataArray_month;
	}

	// ---------- periodのループ回す ----------
	$c_i2 = 0;
	foreach ($period as $carbon) {
		$c_i2++;

		// ---------- メターキーのフォーマットを定義 ----------
		$c_formatted = $carbon->copy()->format("Y-m-d");

		// ---------- ユーザーメタ取得（配列） ----------
		$c_userMetaKeys = $wpdb->get_col(
			'SELECT meta_key
				FROM ' . $wpdb->usermeta . ' WHERE
				user_id =  ' . '"' . $user_id . '"
				AND meta_key LIKE "' . $c_formatted . '%"
			'
		);

		// ---------- ユーザーメタ定義（変数） ----------
		$c_userDataMinutes = get_user_meta($user_id, $c_userMetaKeys[0], true);

		// ---------- データがなかったら0を出力 ----------
		if (empty($c_userMetaKeys)) {
			$c_userDataMinutes = 0;
		}

		// ---------- 年間カレンダーの時 ----------
		if ($_GET["period"] == "year") {
			$c_month = explode("-", $c_userMetaKeys[0]);
			$c_monthInt = (int)$c_month[1];
			$c_monthInt = ltrim($c_monthInt, '0');

			if (($c_monthInt) == $carbon->format("n")) {
				array_push($c_userDataArray_year[$c_monthInt], $c_userDataMinutes);
			}
		}

		// ---------- 日毎、月間カレンダーの時 ----------
		else {
			// ---------- $c_formattedの型と同じものだけを$c_userDataArrayに入れる ----------
			array_push($c_userDataArray, $c_userDataMinutes);
		}
	}


	// ---------- 年間カレンダーの時（12回配列回す） ----------
	if ($_GET["period"] == "year") {
		foreach ($c_userDataArray_year as $c_userDataArray_yearChild) {
			array_push($c_userDataArray, array_sum($c_userDataArray_yearChild));
		}
	}

	// ---------- 期間の最大値 ----------
	if (
		// !empty($c_userDataArray) ||
		array_sum($c_userDataArray) !== 0
	) {

		$c_userDataArratyMax = max($c_userDataArray);
		$c_userDataMaxHour = $c_userDataArratyMax / 60;
		$c_userDataMaxLimit = roundUpToAny($c_userDataMaxHour);
	} else {
		$c_userDataMaxLimit = 24;
	}
	?>

  <div class="mypageTimer__calendar">
    <figure class="mypageTimer__calendarFigureWrap">
      <ul class="mypageTimer__calendarUnits">
        <li class="mypageTimer__calendarUnitItem -top">(時間)<br><?= $c_userDataMaxLimit ?></li>
        <li class="mypageTimer__calendarUnitItem -middle"><?= $c_userDataMaxLimit / 2 ?></li>
        <li class="mypageTimer__calendarUnitItem -bottom">0</li>
      </ul>

      <ul class="mypageTimer__calendarFigure -<?= $_GET["period"] ?>">
        <?php
				$c_index = 0;

				// ---------- 表示期間によって$periodを変更 ----------
				if ($_GET["period"] == "year") {
					$period = CarbonPeriod::create($c_startOfPeriod, $c_lastOfPeriod)->months();
				} else {
					$period = CarbonPeriod::create($c_startOfPeriod, $c_lastOfPeriod);
				}
				// ---------- 学習カレンダー 期間の順番 ----------
				global $periods;
				$prev_periods = $periods[$_GET["period"]];
				foreach ($period as $carbon) {
					$c_index++;

					// ---------- データの幅取得 ----------
					$c_count = count($period);
					$c_dataWidth = 100 / $c_count;

					// ---------- 日毎の場合 ----------
					if (
						$_GET["period"] == "days"
						|| $_GET["period"] == ""
					) {

						$c_date = $carbon->format("n/j");
						$c_dayOfWeek = "<br>(" . $weekday_jap[$carbon->dayOfWeek] . ")";
						$c_dayOfWeekToJS = "(" . $weekday_jap[$carbon->dayOfWeek] . ")";
					}

					// ---------- 月間の場合 ----------
					elseif ($_GET["period"] == "month") {
						if (
							(int)$carbon->format("j") == $c_startOfPeriod->format("j")
							|| (int)$carbon->format("j") == $c_lastOfPeriod->format("j")
							|| (int)$carbon->format("j") == 15
							// || (int)$carbon->format("j") == 10
							// || (int)$carbon->format("j") == 20
							// || (int)$carbon->format("j") == 20
							// || (int)$carbon->format("j") == 25
							// || (int)$carbon->format("j") % 5 === 0 // 5の倍数
						) {
							$c_date = $carbon->format("j日");
							$c_dayOfWeek = "<br>(" . $weekday_jap[$carbon->dayOfWeek] . ")";
						} else {
							$c_date = "";
							$c_dayOfWeek = "";
						}
					}

					// ---------- 年間の場合 ----------
					elseif ($_GET["period"] == "year") {
						$c_date = $carbon->format("n月");
						$c_date = str_replace("月", "<br class='-sp-only'>月", $c_date);
					}

					// ---------- ユーザーメタ（勉強時間）再定義 ----------
					$c_userDataMinutes = $c_userDataArray[$c_index - 1];
					if (!$c_userDataMinutes) {
						$c_userDataMinutes = 0;
					}

					// ---------- 定義した個々の値を百分率で表示 ----------
					$c_userDataPercentage = ($c_userDataMinutes / ($c_userDataMaxLimit * 60)) * 100;
				?>
        <li class="mypageTimer__calendarDataWrap <?= "-" . $prev_periods ?>"
            style="width : <?= $c_dataWidth ?>% ;">
          <form class="mypageTimer__calendarData"
                action="<?php echo get_permalink(); ?>"
                method="GET">
            <!-- <input type="hidden"
                   name="period"
                   value="<?= $prev_periods ?>"> -->
            <?php rampup_GETS(['pagination']) ?>
            <button class="mypageTimer__calendarValue <?php echo ($prev_periods === "changeRecord") ? "js__modal__trigger" : ''; ?>"
                    <?php echo ($prev_periods !== "changeRecord") ? 'type="submit"' : 'type="button"'; ?>
                    name="pagination"
                    <?php echo ($prev_periods === "changeRecord") ? "modal-trigger='-js-$prev_periods'" : ''; ?>
                    data-min="<?= $c_userDataMinutes ?>"
                    value="<?= $carbon ?>"
                    dayOfWeek="<?= $c_dayOfWeekToJS ?>"
                    time="<?= convertToHoursMins($c_userDataMinutes, "%02d時間%02d分") ?>"
                    style="height: <?= $c_userDataPercentage ?>% ;">
            </button>

            <div class="mypageTimer__calendarDate">
              <?= $c_date . $c_dayOfWeek  ?>
            </div>
          </form>
        </li>
        <?php } ?>
      </ul>
    </figure>
  </div>
</div>