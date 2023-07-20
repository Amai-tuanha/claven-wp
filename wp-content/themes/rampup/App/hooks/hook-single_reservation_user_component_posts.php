<?php
add_filter('single_reservation_user_component_posts', function ($user_id) {
  $class_user = new Rampup_User_child($user_id);
?>
<?php if (is_author()) { ?>

<?php } ?>
<?php
});