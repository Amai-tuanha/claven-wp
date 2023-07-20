<?php 
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);
if (
  $class_user->user_status == "paid" &&
  !$class_user->user_termsOfService
) {
  wp_safe_redirect($class_user->user_termsOfService_link());
  exit;
}
?>