(function ($) {
  $(function () {
    //// ---------- モーダル ----------
    $(".js__modal__trigger").on("click", function () {
      var modalAction = $(this).attr("modal-action");
      var calendarAction = $(this).attr("calendar-action");
      var postID = $(this).attr("calendar-post-id");

      // ---------- カレンダーアクションを追加 ----------
      $('#calendarForm').find('#calendar_action').val(calendarAction);
      $('#calendarForm').find('#calendar_post_id').val(postID);

      // ---------- モーダル全体を表示 ----------
      $(".-" + modalAction).parents(".modalWrap").fadeIn();

      // ---------- modalActionに該当するパーツを読み込み ----------
      $(".-" + modalAction).show();



      var modalContentsHeight = $(".js__modal__contentHeight").height();
      $(".js__modal__contentWrap").css({
        height: modalContentsHeight,
      });
    });
    $(".modalWrap").on("click", function (e) {
      if (!$(e.target).closest(".modal__content").length) {
        // ターゲット要素の外側をクリックした時の操作
        $(".modalWrap").fadeOut();
      } else {
        // ターゲット要素をクリックした時の操作
      }
    });
  });
})(jQuery);
