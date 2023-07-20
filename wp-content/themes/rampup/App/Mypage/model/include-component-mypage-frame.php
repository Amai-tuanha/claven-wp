<?php
add_action('component_mypage_frame__file_inclusion' , 'component_mypage_frame__file_inclusion');

function component_mypage_frame__file_inclusion() {
      if (is_page("mypage")) {
      include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-timer.php";
      }
      // ---------- 学習進捗ページ ----------
      elseif (is_page("mypage-progress")) {

      include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-progress.php";
      }
      // ---------- 勉強会ページ ----------
      elseif (is_page("mypage-seminar")) {
      include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-seminar.php";
      }
}

add_action('component_mypage_frame__send_form', 'component_mypage_frame__send_form');
function component_mypage_frame__send_form () {
  global $wpdb;

  $user_id = get_current_user_id();

      if ('POST' === $_SERVER['REQUEST_METHOD']) {

            /*--------------------------------------------------
              /* mypageSetting.php内の処理
              /*------------------------------------------------*/
            if (isset($_POST["user_updateAvatarTrigger"])) {
              // ---------- 表示名変更 ----------
              $wpdb->update(
                'wp_users',
                ['display_name' => $_POST["display_name"]],
                ['ID' => $user_id],
                ['%s'],
                ['%s']
              );

              // ---------- パスワード変更 ----------
              if (!empty($_POST["user_pass"])) {
                wp_update_user([
                  'ID' => $user_id,
                  'user_pass' => $_POST["user_pass"],
                ]);
              }

              if (isset($_FILES["user_avatar"])) {
                if (!function_exists('wp_generate_attachment_metadata')) {
                  include_once ABSPATH . "wp-admin" . '/includes/image.php';
                  include_once ABSPATH . "wp-admin" . '/includes/file.php';
                  include_once ABSPATH . "wp-admin" . '/includes/media.php';
                }

                foreach ($_FILES as $file => $array) {
                  if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                    wp_safe_redirect(get_permalink());
                    exit;
                  }
                  $attach_id = media_handle_upload($file, $new_post);
                  $user_avatarURL = wp_get_attachment_url($attach_id);
                  update_user_meta($user_id, "user_avatarURL", $user_avatarURL);
                }
              }

              wp_safe_redirect(get_permalink());
              exit;
            }
            // ---------- changeRecord.phpの処理 ----------
            elseif (isset($_POST["changeRecord__formTrigger"])) {
              // ---------- 変数 ----------
              $changeRecord_timeTotalInput = $_POST["changeRecord__timeTotalInput"];
              $changeRecord_date = $_POST["changeRecord__date"];
              $changeRecord_inputHours = $_POST["changeRecord__inputHours"];
              $changeRecord_inputHours60 = $changeRecord_inputHours * 60;
              $changeRecord_inputMinutes = $_POST["changeRecord__inputMinutes"];
              $changeRecord_inputTotal = $changeRecord_inputHours60 + $changeRecord_inputMinutes;

              $class_user = new Rampup_User($user_id);

              // ---------- wp_usermetaの値(配列) ----------
              $user_recordDate = $wpdb->get_row(
                '
                            SELECT * FROM ' . $wpdb->usermeta . '
                            WHERE user_id =' . '"' . $user_id . '"
                            AND meta_key LIKE"' . $changeRecord_date . '%"
                      '
              );

              $c_userMetaMax = get_user_meta($user_id, "max-" . $changeRecord_date, true);

              $changeRecord_limit = $_POST["changeRecord__originValue"];

              // ---------- DB値 変更 ----------
              // ---------- DB値に既存の値があったら ----------
              if ($user_recordDate) {
                update_user_meta($user_id, $user_recordDate->meta_key, $changeRecord_inputTotal);
              }
              // ---------- DB値に既存の値がなかったら ----------
              else {
                update_user_meta($user_id, $changeRecord_date . " 00:00:00", $changeRecord_inputTotal);
              }

              // ---------- リダイレクト ----------
              $redirect_link = get_permalink();
              $changeRecord_getPeriod = $_POST["changeRecord__getPeriod"];
              $changeRecord_getPagination = $_POST["changeRecord__getPagination"];
              if (!empty($changeRecord_getPagination)) {
                $redirect_link .= "?period=" . $changeRecord_getPeriod . "&pagination=" . $changeRecord_getPagination;
              }

              wp_safe_redirect($redirect_link);
              exit;
            }

            do_action('mypage_frame_post_request', $user_id);
          }
}
