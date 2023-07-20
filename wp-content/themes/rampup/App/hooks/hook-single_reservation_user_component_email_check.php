<?php
add_filter('single_reservation_user_component_email_check', function ($user_id, $post_id) {
  $class_user = new Rampup_User($user_id);
  $class_post = new Rampup_Post($post_id);

  if ($class_user->user_status == 'application') {
    global $site_mail_list, $site_course_name;
    $cloudsign_reservation_date = carbon_formatting($class_post->reservation_date);

    $subject_to_admin = "【 $site_course_name 】決済メールを送付";
    $message_to_admin = "
    $class_user->user_displayName 様に決済メールを送付させていただきました。
    契約書の送付をよろしくお願いします。

    ■氏名
    $class_user->user_displayName

    ■メールアドレス
    $class_user->user_email";
    
    my_PHPmailer_function(
      $subject_to_admin,
      $message_to_admin,
      $site_mail_list,
    );
  }
}, 10, 2);