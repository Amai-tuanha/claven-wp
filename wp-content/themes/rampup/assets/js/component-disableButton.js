
// ---------- ボタンクリック御にローディングアニメーション追加 ----------
function disableButton(e) {
  var btn = e.target,
    spinner = '<span class="spinner"></span>',
    btnHeight = $(btn).innerHeight();

  $(btn).addClass('loading');

  // ---------- スタイル ----------
  $('.loading').css({
    'padding-left': btnHeight * 1.5 + 'px',
  });

  setTimeout(function () {
    $(btn).append(spinner);

    btnColor = $(btn).css('color')
    $('.spinner').css({
      height: btnHeight * 0.7 + 'px',
      width: btnHeight * 0.7 + 'px',
      'border-top-color': btnColor,
      'left': (btnHeight * 1.5) / 2 + 'px'
    })
  }, 500);

}
