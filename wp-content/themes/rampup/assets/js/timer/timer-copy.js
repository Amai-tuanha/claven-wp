// ---------- ０詰めの関数 ----------
function zeroPadding(NUM, LEN) {
  return (Array(LEN).join("0") + NUM).slice(-LEN);
}

function presentTime() {
  // ---------- 現在の時刻を取得 ----------
  var now = new window.Date();
  var Ye = now.getFullYear();
  var Mo = zeroPadding(now.getMonth() + 1, 2);
  var Da = zeroPadding(now.getDate(), 2);
  var Ho = zeroPadding(now.getHours(), 2);
  var Mi = zeroPadding(now.getMinutes(), 2);
  var Se = zeroPadding(now.getSeconds(), 2);
  var statDate_time = Ye + "-" + Mo + "-" + Da + " " + Ho + ":" + Mi + ":" + Se;

  return statDate_time;

  // return window.sessionStorage.getItem(when);
}

// ---------- プッシュ通知 ----------
function sendPushNotification($title, $body, $click = "") {
  Push.create($title, {
    body: $body,
    timeout: 5000,
    onClick: function () {
      $click;
      this.close();
    },
  });
}

// ---------- モーダルの学習スタートをクリックした後に ----------
// ---------- progress内の「学習を進める」ボタンを変更 ----------
function afterStartCourse() {
  $(".mypage__button.js__modal__trigger").each(function () {
    var link = $(this).attr("link");
    $(this).attr({
      "modal-trigger": "",
      href: link,
      target: "_blank",
      rel: "noopener",
    });
    $(this).removeClass("js__modal__trigger");
  });
}

$(function () {
  // ---------- 学習スタートをクリックした時 ----------
  $("#js__timer__start").on("click", function () {
    sessionStorage.setItem("timerStatus", "start");
    localStorage.setItem("timerStatusLocal", "start");
    $("#js__timer__start").addClass("-disabled");
    $("#js__timer__pause").removeClass("-disabled");
    $("#js__timer__stop").removeClass("-disabled");

    // ---------- progress内の「学習を進める」ボタンを変更 ----------
    afterStartCourse();
  });

  // ---------- 休憩をクリックした時 ----------
  $("#js__timer__pause").on("click", function () {
    sessionStorage.setItem("timerStatus", "pause");
    localStorage.setItem("timerStatusLocal", "pause");
    $("#js__timer__start").removeClass("-disabled").text("学習再開");
    $("#js__timer__pause").addClass("-disabled");
    $("#js__timer__stop").removeClass("-disabled");
  });

  // ---------- 学習終了をクリックした時 ----------
  $("#js__timer__stop").on("click", function () {
    sessionStorage.setItem("timerStatus", "stop");
    localStorage.setItem("timerStatusLocal", "stop");
    $("#js__timer__start").removeClass("-disabled").text("学習スタート");
    $("#js__timer__pause").addClass("-disabled");
    $("#js__timer__stop").addClass("-disabled");
  });

  var timerStatus = sessionStorage.getItem("timerStatus");
  if (timerStatus === "start") {
    $("#js__timer__start").addClass("-disabled");
    $("#js__timer__pause").removeClass("-disabled");
    $("#js__timer__stop").removeClass("-disabled");
  } else if (timerStatus === "pause") {
    $("#js__timer__start").removeClass("-disabled").text("学習再開");
    $("#js__timer__pause").addClass("-disabled");
    $("#js__timer__stop").removeClass("-disabled");
  } else if (timerStatus === "stop") {
    $("#js__timer__start").removeClass("-disabled").text("学習スタート");
    $("#js__timer__pause").addClass("-disabled");
    $("#js__timer__stop").addClass("-disabled");
  }
});

// ---------- ここからタイマーの処理 ----------
var timer_studySS = 0,
  s = 0,
  m = 0,
  h = 0,
  timer_pauseSS = 0;

var timerStatus = window.sessionStorage.getItem("timerStatus");
var isCountingDown = false;
var timer;
var pauseTimer;
var interval = 1000; // 1000 = 1秒
var startDate_timeVal;
var ss_timerStart = window.sessionStorage.getItem("ss_timerStart");
var ss_timerEnd = window.sessionStorage.getItem("ss_timerEnd");

// タイマーが表示される所
var stopwatchEl = document.querySelector("#js__stopwatch");
// 不明
var lapsContainer = document.querySelector(".laps");

// ---------- 学習時間を計測 ----------
function start() {
  if (!timer) {
    isCountingDown = true;

    if (window.sessionStorage.getItem("timerStatus") === "start") {
      startTime = sessionStorage.getItem("startTime");
    } else {
      startTime = new Date().getTime();
      // startTime = startTime - 10799000; // 3時間前
      // startTime = startTime - 28799000; // 8時間前
      // startTime = startTime - 43199000; // 12時間前
      sessionStorage.setItem("startTime", startTime);
    }

    const showTimer = () => {
      stopwatchEl.textContent =
        zeroPadding(h, 2) + ":" + zeroPadding(m, 2) + ":" + zeroPadding(s, 2);

      const currentTime = new Date().getTime();
      const elapsedTime = new Date(currentTime - startTime);

      elapsedTimeSec = Math.floor((currentTime - startTime) / 1000);
      h = elapsedTime.getHours() - 9;
      m = elapsedTime.getMinutes();
      s = elapsedTime.getSeconds();
      timer_studySS = elapsedTimeSec;

      if (h === 3 && m === 0 && s === 1) {
        sendPushNotification(
          "勉強開始から3時間経過",
          "勉強開始から3時間が経過しました。そろそろ休憩にしませんか？"
        );
      }
      // ---------- 8時間時点での通知 ----------
      else if (h === 8 && m === 0 && s === 1) {
        sendPushNotification(
          "勉強開始から8時間経過",
          "勉強開始から8時間が経過しました。勉強を終了しますか？"
        );
      }
      // ---------- 12時間時点での終了 ----------
      else if (h === 12 && m === 0 && s === 0) {
        sendPushNotification(
          "勉強開始から12時間経過",
          "勉強開始から12時間が経過しました。タイマーを終了します。学習を続ける場合は再度タイマーを起動してください。"
        );
        setTimeout(function () {
          $("#js__timer__stop").trigger("click");
        }, 2000);
      }
      // // ---------- 検証用 ----------
      // else if (h === 0 && m === 0 && s === 3) {
      //   sendPushNotification(
      //     "テスト通知タイトル",
      //     "テスト通知本文テスト通知本文テスト通知本文テスト通知本文テスト通知本文テスト通知本文"
      //   );
      // }

      stopwatchEl.textContent =
        zeroPadding(h, 2) + ":" + zeroPadding(m, 2) + ":" + zeroPadding(s, 2);
      $("#timer_study").val(timer_studySS);
      // $("#timer_study").val(timer_studySS + 86400); // 検証よう
    };
    timer = setInterval(showTimer, interval);
  }

  // if (!timer) {
  //   // timer = setInterval(function () {
  //   //   stopwatchEl.textContent =
  //   //     zeroPadding(h, 2) + ":" + zeroPadding(m, 2) + ":" + zeroPadding(s, 2);
  //   //   // timer_studySS++;
  //   //   // s++;
  //   //   if (s === 59) {
  //   //     s = 0;
  //   //     m++;
  //   //   }
  //   //   if (m === 59) {
  //   //     m = 0;
  //   //     h++;
  //   //   }
  //   //   // ---------- 3時間時点での通知 ----------
  //   //   if (h === 3 && m === 1 && s === 1) {
  //   //     sendPushNotification(
  //   //       "勉強開始から3時間経過",
  //   //       "勉強開始から3時間が経過しました。そろそろ休憩にしませんか？"
  //   //     );
  //   //   }
  //   //   // ---------- 8時間時点での通知 ----------
  //   //   else if (h === 8 && m === 1 && s === 1) {
  //   //     sendPushNotification(
  //   //       "勉強開始から8時間経過",
  //   //       "勉強開始から8時間が経過しました。勉強を終了しますか？"
  //   //     );
  //   //   }
  //   //   // // ---------- 8時間時点での通知 ----------
  //   //   // else if (m % 10 == 0 && s === 1) {
  //   //   //   sendPushNotification(
  //   //   //     "テスト通知タイトル",
  //   //   //     "テスト通知本文テスト通知本文テスト通知本文テスト通知本文テスト通知本文テスト通知本文"
  //   //   //   );
  //   //   // }
  //   //   stopwatchEl.textContent =
  //   //     zeroPadding(h, 2) + ":" + zeroPadding(m, 2) + ":" + zeroPadding(s, 2);
  //   //   // $("#timer_study").val(timer_studySS + 43200);
  //   //   $("#timer_study").val(timer_studySS);
  //   // }, interval);
  // }

  // ---------- 学習開始の日時を#startDate_startTimeに入れる ----------
  if (
    window.sessionStorage.getItem("timerStatus") === "stop" ||
    window.sessionStorage.getItem("timerStatus") === ""
  ) {
    // if (!ss_timerStart) {
    sessionStorage.setItem("ss_timerStart", presentTime());
    var ss_timerStart = window.sessionStorage.getItem("ss_timerStart");
    var startDate_timeVal = ss_timerStart;
    $("#startDate_startTime").val(startDate_timeVal);
    // }
  }
}

// ---------- 学習時間を計測一時停止 ----------
function pause() {
  clearInterval(timer);
  timer = false;
  startDate_timeVal = true;
}

// ---------- 休憩時間を計測 ----------
function pause_start() {
  isCountingDown = true;
  timer_pauseSS = $("#timer_pause").val();
  pauseTimer = setInterval(function () {
    timer_pauseSS++;
    $("#timer_pause").val(timer_pauseSS);
  }, interval);
}

// ---------- 休憩時間を計測一時停止 ----------
function pause_pause() {
  if (window.sessionStorage.getItem("timerStatus") === "pause") {
    clearInterval(pauseTimer);
    pauseTimer = false;
    startDate_timeVal = true;
  }
}

// ---------- タイマー停止　＋　データ送信 ----------
function stop() {
  $("#startDate_endTime").val(presentTime());

  clearInterval(timer);
  clearInterval(pauseTimer);

  $("#timerForm").submit();

  window.sessionStorage.removeItem("time");
  window.sessionStorage.removeItem("ss_timerStart");
  timer = false;
  pauseTimer = false;
  ss_timerStart = 0;
  startDate_timeVal = false;
  timer_studySS = 0;
  timer_pauseSS = 0;
  s = 0;
  m = 0;
  h = 0;
  stopwatchEl.textContent = `00:00:00`;
  $("#timer_study").val(timer_studySS);
  $("#timer_pause").val(timer_pauseSS);
}

// ---------- ページ移動前の処理 ----------
window.addEventListener("beforeunload", function () {
  if (isCountingDown == true) {
    pause();
    let obj = {
      startTime: sessionStorage.getItem("startTime"),
      ss_timerStart: window.sessionStorage.getItem("ss_timerStart"),
      timer_studySS: timer_studySS,
      m: zeroPadding(m, 2),
      s: zeroPadding(s, 2),
      h: zeroPadding(h, 2),
      timer_pauseSS: timer_pauseSS,
      // ms: ms < 10 ? "0" + ms : ms,
    };
    window.sessionStorage.setItem("time", JSON.stringify(obj));
    localStorage.setItem("timerStatusLocal", "stop");
  }
});

// ---------- 読み込み時の処理 ----------
window.addEventListener("load", function () {
  if (window.sessionStorage.getItem("time") !== null) {
    let obj = JSON.parse(window.sessionStorage.getItem("time"));
    s = obj["s"];
    m = obj["m"];
    h = obj["h"];
    timer_studySS = obj["timer_studySS"];
    timer_pauseSS = obj["timer_pauseSS"];
    ss_timerStart = obj["ss_timerStart"];

    stopwatchEl.textContent = `${h}:${m}:${s}`;
    $("#timer_pause").val(timer_pauseSS);
    $("#startDate_startTime").val(ss_timerStart);
  }

  // ---------- 学習スタートクリックしているとき ----------
  if (window.sessionStorage.getItem("timerStatus") === "start") {
    start();
    localStorage.setItem("timerStatusLocal", "start");
  }
  // ---------- 休憩をクリックしているとき ----------
  else if (window.sessionStorage.getItem("timerStatus") === "pause") {
    pause_start();
  }
});

$(function () {
  // setInterval(function () {
  //   console.log(window.localStorage.getItem("timerStatusLocal"));
  // }, 500);

  // ---------- モーダルの学習スタートをクリックした時 ----------
  $("#js__modaiTimer__start").on("click", function () {
    link = $(this).attr("link");
    start();
    sessionStorage.setItem("timerStatus", "start");
    localStorage.setItem("timerStatusLocal", "start");
    $(this).next().trigger("click");
    window.open(link, "_blank");
    setTimeout(function () {
      $(".mypageHeader__menuItem.-tab1").trigger("click");
      location.reload();
    }, 500);
  });

  // ---------- モーダルの学習スタートをクリックした後に ----------
  // ---------- progress内の「学習を進める」ボタンを変更 ----------
  if (
    window.sessionStorage.getItem("timerStatus") === "start" ||
    window.localStorage.getItem("timerStatusLocal") === "start"
  ) {
    afterStartCourse();
  }

  // ---------- 学習スタートを押していなかったらリダイレクト ----------
  var pathName = location.pathname;
  if (
    pathName !== "/mypage/" &&
    window.localStorage.getItem("timerStatusLocal") !== "start"
  ) {
    sessionStorage.setItem("timerStatus", "stop");
    location.href = "/mypage/";
  }
});

// ---------- ブラウザをリロードもしくは閉じる前に警告を出す ----------
// window.onbeforeunload = function (e) {
//   e = e || window.event;

//   if (isCountingDown == true) {
//     // For IE and Firefox prior to version 4
//     if (e) {
//       e.returnValue = "Sure?";
//     }

//     // For Safari
//     return "Sure?";
//   }
// };
