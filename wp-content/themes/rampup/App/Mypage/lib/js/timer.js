// ---------- ドメインより後のURL ----------
var pathName = location.pathname;
// ---------- ０詰めの関数 ----------
function zeroPadding(NUM, LEN) {
  return (Array(LEN).join("0") + NUM).slice(-LEN);
}

function presentTime() {
  // ---------- 現在の時刻を取得 ----------
  var now = new Date();
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

$(window).on("load focus", function () {
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

  // ---------- 学習終了をクリックした時 ----------
  $("#js__timer__stop").on("click", function () {
    sessionStorage.setItem("timerStatus", "stop");
    localStorage.setItem("timerStatusLocal", "stop");
    $("#js__timer__start").removeClass("-disabled").text("学習スタート");
    $("#js__timer__pause").addClass("-disabled");
    $("#js__timer__stop").addClass("-disabled");
  });

  // var timerStatus = sessionStorage.getItem("timerStatus");
  var timerStatus = localStorage.getItem("timerStatusLocal");
  if (timerStatus === "start") {
    $("#js__timer__start").addClass("-disabled");
    $("#js__timer__pause").removeClass("-disabled");
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
var ls_timerStart = window.sessionStorage.getItem("ls_timerStart");
var ss_timerEnd = window.sessionStorage.getItem("ss_timerEnd");

// タイマーが表示される所
var stopwatchEl = document.querySelector("#js__stopwatch");
// 不明
var lapsContainer = document.querySelector(".laps");

// ---------- 学習時間を計測 ----------
function start() {
  if (!timer) {
    isCountingDown = true;

    if (window.localStorage.getItem("timerStatusLocal") === "start") {
      // startTime = sessionStorage.getItem("startTime");
      startTime = localStorage.getItem("startTime");
    } else {
      startTime = new Date().getTime();
      // startTime = startTime - 10799000; // 3時間前
      // startTime = startTime - 28799000; // 8時間前
      // startTime = startTime - 43199000; // 12時間前
      // console.log(startTime);
      // startTime = startTime - 940000000; // 12時間前
      // startTime = startTime - 8553600000; // 12時間前
      // startTime = startTime - 53997000; // 12時間前
      // console.log(startTime);
      // startTime = startTime - (43199000 + 28799000) ; // 9時間前
      // sessionStorage.setItem("startTime", startTime);
      localStorage.setItem("startTime", startTime); //学習スタート時間（ミリ秒）
      localStorage.setItem("format-startTime", presentTime());// 学習スタート時間（フォーマット済み）
    }

    if (stopwatchEl) {
      const showTimer = () => {
        stopwatchEl.textContent =
          zeroPadding(h, 2) + ":" + zeroPadding(m, 2) + ":" + zeroPadding(s, 2);

        const currentTime = new Date().getTime();
        // console.log(currentTime);
        // console.log(startTime);
        // currentTime = currentTime + 10799000; // 3時間後
        // currentTime = currentTime + 28799000; // 8時間後
        // currentTime = currentTime + 43199000; // 12時間後
        // const elapsedTime = new Date(currentTime - startTime);
        const elapsedTime = currentTime - startTime;
        // console.log(currentTime - startTime);
        
        elapsedTimeSec = Math.floor((currentTime - startTime) / 1000);
        // h = elapsedTime.getHours() - 9;
        h = Math.floor(elapsedTime / 1000 / 60 / 60) % 24;
        // console.log(elapsedTime);
        // console.log(h);
        // m = elapsedTime.getMinutes();
        m = Math.floor(elapsedTime / 1000 / 60) % 60;
        // s = elapsedTime.getSeconds();
        s = Math.floor(elapsedTime / 1000) % 60;
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
        // else if (h >= 12) {
        //   sendPushNotification(
        //     "勉強開始から12時間経過",
        //     "勉強開始から12時間が経過しました。タイマーを終了します。"
        //   );
        //   setTimeout(function () {
        //     $("#js__timer__stop").trigger("click");
        //   }, 2000);
        // }
        // // ---------- 検証用 ----------
        // else if (h === 0 && m === 0 && s === 3) {
        //   sendPushNotification(
        //     "テスト通知タイトル",
        //     "テスト通知本文テスト通知本文テスト通知本文テスト通知本文テスト通知本文テスト通知本文"
        //   );
        // }
        let hour_length = 2
        if(h >= 99){
          hour_length = 3
        }
        stopwatchEl.textContent =
          zeroPadding(h, hour_length) + ":" + zeroPadding(m, 2) + ":" + zeroPadding(s, 2);
        $("#timer_study").val(timer_studySS);
        // $("#timer_study").val(timer_studySS + 86400); // 検証用
      };
      timer = setInterval(showTimer, interval);
    }
  }

  // ---------- 学習開始の日時を#startDate_startTimeに入れる ----------
  if (
    window.localStorage.getItem("timerStatusLocal") === "stop" ||
    window.localStorage.getItem("timerStatusLocal") === ""
  ) {
    // if (!ls_timerStart) {
    localStorage.setItem("ls_timerStart", presentTime());
    var ls_timerStart = window.localStorage.getItem("ls_timerStart");
    var startDate_timeVal = ls_timerStart;
    $("#startDate_startTime").val(startDate_timeVal);
    // }
  }
}

// ---------- 消さない ----------
// // ---------- 休憩時間を計測 ----------
// function pause_start() {
//   isCountingDown = true;
//   timer_pauseSS = $("#timer_pause").val();
//   pauseTimer = setInterval(function () {
//     timer_pauseSS++;
//     $("#timer_pause").val(timer_pauseSS);
//   }, interval);
// }

// ---------- 消さない ----------
// // ---------- 休憩時間を計測一時停止 ----------
// function pause_pause() {
//   if (window.sessionStorage.getItem("timerStatus") === "pause") {
//     clearInterval(pauseTimer);
//     pauseTimer = false;
//     startDate_timeVal = true;
//   }
// }

// ---------- タイマー停止　＋　データ送信 ----------
function stop(submit = "") {
  $("#startDate_endTime").val(presentTime());

  clearInterval(timer);
  // clearInterval(pauseTimer);

  if (submit === "") {
    $("#timerForm").submit();
  }
  window.localStorage.setItem("timerStatusLocal", "stop");
  window.localStorage.removeItem("time");
  window.localStorage.removeItem("ls_timerStart");
  timer = false;
  pauseTimer = false;
  ls_timerStart = 0;
  startDate_timeVal = false;
  timer_studySS = 0;
  timer_pauseSS = 0;
  s = 0;
  m = 0;
  h = 0;
  // if (pathName == "/mypage/") {
  //   stopwatchEl.textContent = `00:00:00`;
  // }
  $("#timer_study").val(timer_studySS);
  $("#timer_pause").val(timer_pauseSS);
}

// ---------- ページ移動前の処理 ----------
window.addEventListener("beforeunload", function () {
  if (isCountingDown == true) {
    pause();
    let obj = {
      // startTime: sessionStorage.getItem("startTime"),
      startTime: localStorage.getItem("startTime"),
      ls_timerStart: window.localStorage.getItem("ls_timerStart"),
      timer_studySS: timer_studySS,
      m: zeroPadding(m, 2),
      s: zeroPadding(s, 2),
      h: zeroPadding(h, 2),
      timer_pauseSS: timer_pauseSS,
      // ms: ms < 10 ? "0" + ms : ms,
    };
    window.localStorage.setItem("time", JSON.stringify(obj));
    // localStorage.setItem("timerStatusLocal", "stop");
  }
});
// console.log(startTime);
// ---------- 読み込み時の処理 ----------
// window.addEventListener("load", function () {
$(window).on("load focus", function () {
  if (window.localStorage.getItem("time") !== null && pathName === "/mypage-timer/") {
    let obj = JSON.parse(window.localStorage.getItem("time"));
    s = obj["s"];
    m = obj["m"];
    h = obj["h"];
    timer_studySS = obj["timer_studySS"];
    timer_pauseSS = obj["timer_pauseSS"];
    ls_timerStart = obj["ls_timerStart"];

    stopwatchEl.textContent = `${h}:${m}:${s}`;
    $("#timer_pause").val(timer_pauseSS);
    // $("#startDate_startTime").val(ls_timerStart);
  }

  // ---------- ローカルストレージに保存したフォーマット後の学習スタート時間をvalueに渡す。 ----------
  if(localStorage.getItem("startTime") !== null){
    $("#startDate_startTime").val(localStorage.getItem("format-startTime"));
  }

  // ---------- 学習スタートクリックしているとき ----------
  if (window.localStorage.getItem("timerStatusLocal") === "start") {
    start();
    // localStorage.setItem("timerStatusLocal", "start");
  }
  // ---------- 学習終了をクリックしているとき ----------
  else if (window.localStorage.getItem("timerStatusLocal") === "stop") {
    stop("notSubmit");
  }
  // // ---------- 休憩をクリックしているとき ----------
  // else if (window.sessionStorage.getItem("timerStatus") === "pause") {
  //   pause_start();
  // }

  // // ---------- 学習スタートクリックしているとき ----------
  // if (window.sessionStorage.getItem("timerStatus") === "start") {
  //   start();
  //   localStorage.setItem("timerStatusLocal", "start");
  // }
  // // ---------- 休憩をクリックしているとき ----------
  // else if (window.sessionStorage.getItem("timerStatus") === "pause") {
  //   pause_start();
  // }
});

$(function () {
  var url = new URL(window.location.href);
  var params = url.searchParams;
  // ---------- モーダルの学習スタートをクリックした時 ----------
  $("#js__modaiTimer__start").on("click", function () {
    link = $(this).attr("link");
    start();
    sessionStorage.setItem("timerStatus", "start");
    localStorage.setItem("timerStatusLocal", "start");
    $(this).next().trigger("click");
    window.open(link, "_blank");

    setTimeout(function () {
      // params.delete("timer-redirect");
      // history.replaceState("", "", url.pathname);
      location.href = "/mypage-timer/";
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
});

$(window).on("load focus", function () {
  // // ---------- 学習スタートを押していなかったらリンク変更 ----------
  // if (window.localStorage.getItem("timerStatusLocal") !== "start") {
  //   $(".mypageHeader__logo").attr("href", "/mypage");
  // } else {
  //   $(".mypageHeader__logo").attr("href", "/course");
  // }
});
// $(window).on("load focus", function () {
//   // ---------- 学習スタートを押していなかったらリダイレクト ----------
//   if (
//     pathName !== "/mypage/" &&
//     window.localStorage.getItem("timerStatusLocal") !== "start"
//   ) {
//     sessionStorage.setItem("timerStatus", "stop");
//     location.href = "/mypage/";
//   }
// });
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