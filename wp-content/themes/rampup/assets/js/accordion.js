// ---------- アコーディオン ----------
$(function () {
  var target = $(".js__accordion__header");
  if (target) {
    target.next().hide();
    target.on("click", function () {
      $(this).toggleClass("-accourdion-header-active");
      $(this)
        .next()
        .css({
          height: "100%",
        })
        .slideToggle();
    });
  }
});
