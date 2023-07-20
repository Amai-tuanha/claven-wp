$(function () {
	// ---------- bodyの直後にタグ追加 ----------
  $("body").prepend('<ul id="js__ahm" class="ahm"></ul>');

	// ---------- 変数 ----------
  headingNum = 0;
	headeingOffsetArray = [];

	// ---------- headingタグを取得して色々やる ----------
  $('.-ahm-target').find("h1, h2, h3, h4, h5, h6").each(function () {
    headingNum++;
    $(this).attr("id", `headeingNum${headingNum}`);

    var headerText = $(this).text();
    var tagName = $(this).prop("tagName");
    var tagNameLetters = tagName.split("");
    var pl = 1 * (tagNameLetters[1] - 1);

    // ---------- js__ahmの中身 ----------
    $("#js__ahm").append(
      `<li class="ahm__child" id="headeingList${headingNum}">
			<span class="ahm__childDot -dotOutside"></span>
			<div class="ahm__childInner" style="padding-left: ${pl}em">
			<a class="ahm__childLink" href="#headeingNum${headingNum}">
			<span class="ahm__childDot"></span>
			<span class="ahm__childLinkText">${headerText}</span>
			</a>
			</div>
			</li>`
    );

		// ---------- 配列に値入れる ----------
    var headeingOffset = $(`#headeingNum${headingNum}`).offset().top;
    headeingOffsetArray.push(headeingOffset);
  });

  // ---------- ahmのliの高さ合計を計算する ----------
  headeingMenuHeightArray = [];
  $(".ahm__child").each(function () {
    var headeingMenuHeight = $(this).height();
    headeingMenuHeightArray.push(headeingMenuHeight);
  });

  // ---------- ahmのliの高さ合計 ----------
  let headeingMenuHeightTotal = headeingMenuHeightArray.reduce(function (
    sum,
    element
  ) {
    return sum + element;
  },
  0);

	// ---------- スクロールfunction ----------
	$(window).on("scroll", function () {
    var scrollPercentage = headeingMenuHeightTotal / $("html").height();

    // ---------- htmlmタグのスクロール量に併せてjs__ahmをスクロールする ----------
    var winScroll = $(window).scrollTop();
    var atmScroll = (winScroll - $(window).height()) * scrollPercentage;
//     $("#js__ahm").animate({ scrollTop: atmScroll }, 0);

		// ---------- header高さ取得 ----------
    if ($("header").height() == null) {
      var headerHeight = 45;
    } else {
      var headerHeight = $("header").height() + 20;
    }

    // ---------- activeをつける ----------
    for (let i = 1; i <= headeingOffsetArray.length; i++) {
      var lastIndex = Number(headeingOffsetArray.length) - 1;

      if (
        winScroll + headerHeight > headeingOffsetArray[i - 1] &&
        winScroll + headerHeight < headeingOffsetArray[i]
      ) {
        // ---------- 該当headingの場合 ----------
        $(`#headeingList${i}`).find(".ahm__childDot").addClass("-ahmActive");
      } else if (winScroll + headerHeight > headeingOffsetArray[lastIndex]) {
        // ---------- 一番最後だけ ----------
        $(`#headeingList${lastIndex + 1}`)
          .find(".ahm__childDot")
          .addClass("-ahmActive");
        $(`#headeingList${i - 1}`)
          .find(".ahm__childDot")
          .removeClass("-ahmActive");
      } else {
        // ---------- それ以外 ----------
        $(`#headeingList${i}`).find(".ahm__childDot").removeClass("-ahmActive");
      }
    }
  });

	// ---------- スムーズスクロール ----------
  $(".ahm__childLink").on("click", function () {
    if ($("header").height() == null) {
      var headerHeight = 40;
    } else {
      var headerHeight = $("header").height() + 20;
    }

    var href = $(this).attr("href");
    var target = $(href);
    var position = target.offset().top - headerHeight;
    $("body,html").stop().animate({ scrollTop: position }, 500);
  });
});
