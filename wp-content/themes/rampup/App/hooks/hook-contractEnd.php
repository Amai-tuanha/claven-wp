<?php

add_action('mypage_user_contractEnd', function ($user_id) {
  $class_user = new Rampup_User($user_id);
?>
<div class="contractEnd">
  <p class="contractEnd__text">契約終了まで後<?= $class_user->user_contractEnd; ?>日です。</p>
</div>
<?php
}); ?>