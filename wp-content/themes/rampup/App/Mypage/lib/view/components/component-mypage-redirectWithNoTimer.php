<?php if (!current_user_can("administrator")) { ?>
<script>
$(window).on("load focus", function() {
  // ---------- 学習スタートを押していなかったらリダイレクト ----------
  if (
    pathName !== "/mypage/" &&
    window.localStorage.getItem("timerStatusLocal") !== "start"
  ) {
    sessionStorage.setItem("timerStatus", "stop");
    location.href = "/mypage-timer/?timer-redirect=true";
  }
});
</script>
<?php } ?>