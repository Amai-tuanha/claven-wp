<?php
// $error = [];

// if ('POST' === $_SERVER['REQUEST_METHOD']) {

// }

?>
<div class="modal__template">
  <div class="modal__title">
    <h4 class="modal__titleText">【<span class="modal__titleDate"
        id="js__modal__titleDate"></span>】学習時間の変更</h4>
  </div>

  <div class="modal__main">
    <p class="modal__mainText -tac">記録が間違っている場合は、正しい学習時間に変更しましょう。</p>

    <form class="modal__form"
          action="<?php echo get_permalink(); ?>"
          method="POST">
      <!-- . "/?period=" . $_GET["period"] ."&pagination=" . $_GET['pagination'] -->
      <!-- &pagination=$_GET['pagination'] -->
      <input class="changeRecord__formTrigger"
             id="changeRecord__formTrigger"
             type="hidden"
             name="changeRecord__formTrigger">
      <input class="changeRecord__date"
             id="changeRecord__date"
             type="hidden"
             name="changeRecord__date">
      <input class="changeRecord__originValue"
             id="changeRecord__originValue"
             type="hidden"
             name="changeRecord__originValue">
      <input class="changeRecord__timeTotalInput"
             id="changeRecord__timeTotalInput"
             type="hidden"
             name="changeRecord__timeTotalInput">
      <input class="changeRecord__getPeriod"
             id="changeRecord__getPeriod"
             type="hidden"
             name="changeRecord__getPeriod"
             value="<?= $_GET["period"] ?>">
      <input class="changeRecord__getPagination"
             id="changeRecord__getPagination"
             type="hidden"
             name="changeRecord__getPagination"
             value="<?= $_GET["pagination"] ?>">

      <div class="modal__flex">
        <div class="modal__flexChild -bgBlue">
          <p class="modal__flexTime">現在の記録 : <span id="changeRecord__timeTotalText"></span></p>
        </div>
        <div class="modal__flexChild -bgLightgray">
          <input class="modal__flexInput"
                 id="changeRecord__inputHours"
                 type="number"
                 name="changeRecord__inputHours"
                 max="23"
                 min="0"
                 value="">
          <span>:</span>
          <input class="modal__flexInput"
                 id="changeRecord__inputMinutes"
                 type="number"
                 name="changeRecord__inputMinutes"
                 max="59"
                 min="0"
                 value="">
        </div>
      </div>


      <div class="modal__mainButtonWrap">
        <button type="submit"
                class="modal__mainButton -center">変更を保存</button>
        <span class="js__modal__close modal__closeButton">キャンセルする</span>
      </div>
    </form>

  </div>

</div>