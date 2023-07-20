$(window).on("load resize", function () {
  var siblingsWidth = $(".course__contentsWrap").siblings().width();
  var windowWidth = $(window).width();

  if (windowWidth > 768) {
    $(".course__contentsWrap").css({
      "max-width": "calc( 100% - " + siblingsWidth + "px )",
    });
  } else {
    $(".course__contentsWrap").css({
      "max-width": "100%",
    });
  }

  $(".singlePost")
    .find("img")
    .each(function () {
      var imgWidth = $(this).attr("width");
      var parentWidth = $(this).parent().width();
      if (imgWidth > parentWidth) {
        $(this).css({
          width: "100%",
        });
      }
    });
});

$(function () {
  winWidth = $(window).width();
  // // ---------- ヘッダーでタブ切り替え ----------
  // $('input[name="tab_item"]').each(function () {
  //   $(this).change(function () {
  //     $(".mypage__mainChild").hide();

  //     // ---------- タブ1をクリックした時 ----------
  //     if ($("#tab1 ,#tab1sp").is(":checked")) {
  //       $("#tabContents1").show();
  //       window.history.pushState(null, null, "?");

  //       // ---------- タブ2をクリックした時 ----------
  //     } else if ($("#tab2, #tab2sp").is(":checked")) {
  //       $("#tabContents2").show();
  //       window.history.pushState(null, null, "?progress#step0");

  //       // ---------- タブ3をクリックした時 ----------
  //     } else if ($("#tab3 , #tab3sp").is(":checked")) {
  //       $("#tabContents3").show();
  //       window.history.pushState(null, null, "?seminar");
  //     }
  //   });
  // });

  $(".-sticky").parentsUntil("html").css({
    overflow: "visible",
  });

  headerHeight = $("header").height();
  $(".-sticky").css({
    top: headerHeight + 16,
  });

  // ---------- urlの『?』以降を取得してページの表示を切り替える----------
  urlParam = window.location.search;
  urlParam = urlParam.replace("?", "");
  if (urlParam === "progress") {
    if (winWidth > 860) {
      // ---------- PCの時----------
      $("#tab2").prop("checked", true);
    } else {
      // ---------- SPの時----------
      $("#tab2sp").prop("checked", true);
    }
    $("#tabContents2").show();
    // $("#progress__step0").show();
  } else if (urlParam === "seminar") {
    if (winWidth > 860) {
      // ---------- PCの時----------
      $("#tab3").prop("checked", true);
    } else {
      // ---------- SPの時----------
      $("#tab3sp").prop("checked", true);
    }
    $("#tabContents3").show();
  } else {
    if (winWidth > 860) {
      // ---------- PCの時----------
      $("#tab1").prop("checked", true);
    } else {
      // ---------- SPの時----------
      $("#tab1sp").prop("checked", true);
    }
    $("#tabContents1").show();
  }

  // // ---------- 課題進捗の丸アイコンをクリックした時 ----------
  // $(".svgCircleWrap").on("click", function () {
  //   // ---------- 課題進捗ページじゃなかったらリロード挟む ----------
  //   if (urlParam !== "progress") {
  //     location.reload();
  //   }
  // });
  // ---------- 課題進捗の丸アイコンか ----------
  // ---------- prevNextボタンをクリックした時 ----------
  $(".svgCircleWrap, .mypage__button.-progressPrevNext").click(function () {
    setTimeout(function () {
      urlHash = location.hash;
      $(".mypageProgress__postsChild").hide();
      $(urlHash).show();
    }, 100);
  });

  urlHash = location.hash;
  if (urlParam === "progress" || urlHash) {
    // urlHash = urlHash.replace("#", "");
    // var pregressStep = "#progress__" + urlHash;
    var pregressStep = urlHash;
    $(pregressStep).show();
  }
  if (!urlHash) {
    $(".mypageProgress__postsChild:first-child").show();
  }

  // // ---------- ヘッダー アコーディオン ----------
  // $(".mypageHeader__flexRight").click(function () {
  //   $(this).find(".mypageHeader__accordion").toggleClass("-active");
  // });

  // ---------- 課題進捗アコーディオン ----------
  $(".js__mypageProgress__postTitle").click(function () {
    $(this).next().slideToggle();
  });
});

// ---------- モーダル ----------
$(".js__modal__trigger").on("click", function () {
  var modalTrigger = $(this).attr("modal-trigger");
  

  if (modalTrigger !== "") {
    if (modalTrigger === "-js-changeRecord") {
      // ---------- モーダルコンテンツの高さ調節 ----------
      setTimeout(function () {
        modalContentsChildHeight = $(
          ".modal__contentChild." + modalTrigger
        ).height();

        $(".modal__content").css({
          "max-height": modalContentsChildHeight,
        });
      }, 200);

      // ---------- モーダル ----------
      $(".modalWrap").fadeIn();
      $(".modal__contentChild." + modalTrigger).fadeIn("300");

      // ---------- 学習記録を変更するモーダルの時 ----------
      date = $(this).val();
      date = date.split(" ");
      dayOfWeek = $(this).attr("dayOfWeek");
      dateJapSplit = date[0].split("-");
      dateJap = dateJapSplit[1] + "/" + dateJapSplit[2] + dayOfWeek;
      dataMin = $(this).attr("data-min");
      time = $(this).attr("time");
      time = time.replace("時間", ":");
      time = time.replace("分", "");

      timeInput = time.split(":");

      $("#changeRecord__timeTotalInput").val(time);
      $("#changeRecord__timeTotalText").text(time);
      $("#changeRecord__date").val(date[0]);
      $("#changeRecord__originValue").val(dataMin);
      $("#js__modal__titleDate").text(dateJap);

      if (timeInput) {
        $("#changeRecord__inputHours").val(timeInput[0]);
        $("#changeRecord__inputMinutes").val(timeInput[1]);
      } else {
        $("#changeRecord__inputHours").val("0");
        $("#changeRecord__inputMinutes").val("0");
      }
    } else if (modalTrigger === "-js-mypageSetting") {
      // ---------- モーダルコンテンツの高さ調節 ----------
      setTimeout(function () {
        modalContentsChildHeight = $(
          ".modal__contentChild." + modalTrigger
        ).height();

        $(".modal__content").css({
          "max-height": modalContentsChildHeight,
        });
      }, 200);
      // ---------- モーダル ----------
      $("." + modalTrigger)
        .parents(".modalWrap")
        .fadeIn();
      $(".modal__contentChild." + modalTrigger).fadeIn("300");
    }
    // ---------- 学習を開始するモーダルの時 ----------
    else if (modalTrigger === "-js-startCourse") {
      // ---------- モーダルコンテンツの高さ調節 ----------
      setTimeout(function () {
        modalContentsChildHeight = $(
          ".modal__contentChild." + modalTrigger
        ).height();

        $(".modal__content").css({
          "max-height": modalContentsChildHeight,
        });
      }, 200);

      // ---------- モーダル ----------
      $("." + modalTrigger)
        .parents(".modalWrap")
        .fadeIn();
      $(".modal__contentChild." + modalTrigger).fadeIn("300");

      // ---------- URLを渡す ----------
      link = $(this).attr("link");
      $("#js__modaiTimer__start").attr("link", link);
    }
    // ---------- アニメーションの時は少し発火を遅らせる ----------
    else if (modalTrigger === "-js-mypage-animation") {
      $(".modal__contentChild." + modalTrigger).addClass("-active");

      setTimeout(function () {
        $("." + modalTrigger)
          .parents(".modalWrap")
          .fadeIn();
        $(".modal__contentChild." + modalTrigger).fadeIn();
      }, 1500);

      setTimeout(function () {
        modalContentsChildHeight = $(
          ".modal__contentChild." + modalTrigger
        ).height();

        $(".modal__content").css({
          "max-height": modalContentsChildHeight,
        });
      }, 1550);
      setTimeout(() => {
        $("#membership__finishedForm").submit();
      }, 4000);
    }
  }
});

$(".modalWrap").on("click", function (e) {
  
  if (!$(e.target).closest(".modal__content").length) {
    // ---------- 課題終了のアニメーションの時 ----------
    if ($(".modal__contentChild.-js-mypage-animation").hasClass("-active")) {
      // $("#membership__finishedForm").submit();
    }
    // ターゲット要素の外側をクリックした時の操作
    $(".modalWrap, .modal__contentChild").fadeOut();
    // $(".modal__contentChild").hide();
  } else {
    // ターゲット要素をクリックした時の操作
  }
});
$(".js__modal__close").on("click", function () {
  $(".modalWrap, .modal__contentChild").fadeOut();
});

$(function () {
  // ---------- gnavグローバルメニュー ----------
  $(".mypageHeader__hamburger").on("click", function () {
    $(this)
      .find(".mypageHeader__hamburgerLine")
      .toggleClass("-hamburger-active");
    $(".mypageHeader__gnav").toggleClass("-gnav-active");
  });

  $(".mypageHeader__gnav").on("click", function (e) {
    if (!$(e.target).closest(".mypageHeader__gnavContents").length) {
      // ターゲット要素の外側をクリックした時の操作
      $(".mypageHeader__hamburgerLine").removeClass("-hamburger-active");
      $(".mypageHeader__gnav").removeClass("-gnav-active");
    } else {
      // ターゲット要素をクリックした時の操作
    }
  });
  $(".mypageHeader__menuSP input").each(function () {
    $(this).change(function () {
      location.reload();
    });
  });
});

