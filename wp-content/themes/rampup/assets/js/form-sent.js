$(function () {
  // $(".-formNum" + (i - 1)).after(
  //   '<div class="-form-message -tac">変更されました</div>'
  // );

  var url = new URL(window.location.href);
  var params = url.searchParams;
  if (params.get("form") == "sent") {
    // $(".-form-message").slideToggle();
    alert("変更されました");

    setTimeout(function () {
      params.delete("form");
      history.replaceState("", "", url.pathname);
      // $(".-form-message").slideToggle();
    }, 2000);
  }
});
