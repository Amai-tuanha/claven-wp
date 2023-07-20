
// ---------- ボタンクリック御にローディングアニメーション追加 ----------
function onChengeSendForm(e) {
  var selectbox = $(e.target)
  selectbox.parents('form').submit();
  
}
