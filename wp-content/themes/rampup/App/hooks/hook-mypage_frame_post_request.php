<?php

add_action('mypage_frame_post_request', function () {

  // ---------- 勉強会の課題提出 ----------
  if (isset($_POST["user_uploadSeminarFiles"])) {

    $user_id = get_current_user_id();
    if (isset($_FILES["user_uploadSeminarFile"])) {
      if (!function_exists('wp_generate_attachment_metadata')) {
        include_once(ABSPATH . "wp-admin" . '/includes/image.php');
        include_once(ABSPATH . "wp-admin" . '/includes/file.php');
        include_once(ABSPATH . "wp-admin" . '/includes/media.php');
      }

      foreach ($_FILES as $file => $array) {
        if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
          wp_safe_redirect(get_permalink());
          exit;
        }

        $attach_id = media_handle_upload($file, $new_post);
        update_post_meta($attach_id, 'attachment_type', 'seminar_file_submittion');
        update_post_meta($attach_id, 'attachment_user_id', $user_id);
      }

      // ---------- リダイレクト ----------
      wp_safe_redirect(get_permalink() . '/?form=sent');
      exit;
    }
  }
});