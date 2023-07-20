// ----------------------------------------
// コンテンツの完了ボタンのアニメーション ライブライりmo.jsを使っている。CDN読み込んでいる。
// ----------------------------------------
$(function () {
  const completionButton = document.querySelector('#completion');

  // ---------- 6つの点が飛び散るところの処理 ----------
  const completionBurst = new mojs.Burst({

    parent: completionButton, //この部分でSVGを配置する場所を指定している。(親要素を決めている。)
    left: '50%',
    top: '50%',
    count: 6,
    radius: { 40: 110 },
    children: {
      shape: 'circle', //図形の形を決めている。
      radius: { 12: 0 },
      scale: 1,
      fill: ['#e3b0d4', '#EDBB80', '#D5A5E6', '#90D2D8', '#EAD681', '#9595c4'],
      strokeWidth: 2,
      delay: 300,
      duration: 3000,
      easing: mojs.easing.bezier(0.1, 1, 0.3, 1)
    }
  });

  // ---------- 円状に広がっていく部分の処理 ----------
  const completionCircle = new mojs.Shape({
    // parent: '.-mojs-parent',
    parent: completionButton,
    el: completionButton,
    left: '50%',
    top: '50%',
    // origin: '-100% 0%',
    stroke: '#22B573',
    strokeWidth: { 50: 0 },
    fill: 'none',
    scale: { 0: 1.5 },
    radius: { 10: 60 },
    opacity: 1,
    duration: 750,
    easing: mojs.easing.bezier(0.1, 1, 0.3, 1),

    // ---------- 以下の部分でCSSを変更することができる ----------
    onUpdate: function (progress) {
      completionButton.style.transform = `scale3d(${progress}, ${progress}, 1)`;
    }
  });

  // ---------- ボタンがバウンドする部分の処理 ----------
  const completionTween = new mojs.Tween({
    duration: 800,
    easing: mojs.easing.bezier(0.1, 1, 0.3, 1),
    onUpdate: function (progress) {
      completionButton.style.transform = `translate(0, 0)`;
    }
  });

  // ---------- すべての処理を実行する部分 ----------
  completionButton.addEventListener('click', function () {
    const { x, y } = completionButton.getBoundingClientRect();
    const coords = { x: x , y: y }; //実行する部分の位置を調整できる。例)x+100、y+100のような形
    completionBurst.replay();
    completionCircle.replay();
    completionTween.replay();
  });
  });
