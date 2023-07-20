<?php
add_filter('add_meeting_thanks_post', function ($array) {
  global $site_mail_list;
  $user_id = $array['user_id'];
  $calendar_action = $array['calendar_action'];
  $class_user = new Rampup_User($user_id);

  if (
    $calendar_action == 'add_calendar' &&
    $class_user->user_status == 'before_contract'
  ) {
    $emailPost_id = slug_to_post_id('email-before-contract-to-admin');
    // $site_mail_list = 'kawakatsu@clane.co.jp';
    my_PHPmailer_function(
      do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
      do_shortcode(get_post_field('post_content', $emailPost_id)),
      $site_mail_list,
      null,
      null,
      'false'
    );
  }
});