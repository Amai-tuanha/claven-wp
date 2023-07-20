<?php

use Carbon\Carbon;
// do_action('page_controlpanel_setting__send_form');
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-setting.php");

?>

<?php
/**
 * Template Name: RAMPUP セットアップ
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>

<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-setting.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">




<div class="project__wrap">
  <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>

  <div class="project__contents">

    <div class="contents__width">

      <section class="projectDetail__title">

        <div class="projectDetail__box">
          <h2 class="projectDetail__name"><?php echo ($_GET['set_p'] === 'calendar') ? 'カレンダー設定' : 'セットアップ'; ?></h2>
          <!-- <div class="projectDetail__edit">
						<a href="/">編集キャンセル</a>
					</div> -->
        </div>

      </section>

  <!-- 運営者情報設定 -->
      <?php if ($_GET['set_p'] !== 'calendar') { ?>
      <section class="list__wrap6">
        <form method="post" action="<?php echo get_permalink(); ?>">
          <input type="hidden" name="siteOptionsTrigger">

          <div class="list__tableInformation" style="margin-bottom:4rem;">
            <h2 class="list__titleBox">設定画面</h2>
            <div class="list__textBox">
              <!--  -->
              <div class="page__contact2">


                <div class="contact_form">

                  <!-- <h2 class="contact_formTitle">新規顧客の登録</h2> -->


                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">デフォルトの契約期間</h2>
                    <div class="contact_form--box--item -flex">
                      <input type="text" name="site_default_contractTerm" type="number" min="1" max="100" value="<?php echo ($site_default_contractTerm) ? $site_default_contractTerm : 12; ?>" />
                      <span class="-unit">ヶ月</span>
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">ポップアップの表示日</h2>
                    <div class="contact_form--box--item -flex">
                      <input type="text" name="site_default_contractEnd" type="number" min="1" max="100" value="<?php echo ($site_default_contractEnd) ? $site_default_contractEnd : 30; ?>" />
                      <span class="-unit">日</span>
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <!-- <div class="contact_form--box--item"> -->
                    <input type="hidden" name="site_default_contractEndCheck" />
                    <label for="site_default_contractEndCheck">
                      <input type="checkbox" id="site_default_contractEndCheck" name="site_default_contractEndCheck" <?php echo ($site_default_contractEndCheck) ? 'checked' : ''; ?> value="true" />
                      ポップアップを表示する
                    </label>
                    （チェックをつけるとユーザーのマイページに契約期間終了前にポップが出ます）
                  </div>
                  <!-- </div> -->
                  <br>
                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">メールリスト</h2>
                    <div class="contact_form--box--item">
                      <input type="text" name="site_mail_list" value="<?php echo esc_attr($site_mail_list); ?>" />
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">メール送信者名</h2>
                    <div class="contact_form--box--item">
                      <input type="text" size="40" name="site_mail_title" value="<?php echo ($site_mail_title) ? $site_mail_title : $site_title; ?>" />
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">会社名</h2>
                    <div class="contact_form--box--item">
                      <input type="text" size="40" name="site_company_name" value="<?php echo ($site_company_name) ? $site_company_name : ''; ?>" />
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">会社住所</h2>
                    <div class="contact_form--box--item">
                      <textarea name="site_company_address" cols="30" rows="4"><?php echo ($site_company_address) ? $site_company_address : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">銀行情報</h2>
                    <div class="contact_form--box--item">
                      <textarea name="site_bank_data" cols="30" rows="4"><?php echo ($site_bank_data) ? $site_bank_data : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">講座名</h2>
                    <div class="contact_form--box--item">
                      <input type="text" size="40" name="site_course_name" value="<?php echo ($site_course_name) ? $site_course_name : ''; ?>" />
                    </div>
                  </div>


                </div>


              </div>
              <!--  -->
            </div>
            <button class="send" type="submit">更新する</button><br><br><br>
          </div>
        </form>
      </section>
      <?php } ?>
      <?php if ($_GET['set_p'] === 'calendar') { ?>
<!-- Google -->
      <section class="list__wrap6">
        <form method="post" action="<?php echo get_permalink(); ?>">
          <input type="hidden" name="siteGoogleTrigger">

          <div class="list__tableInformation">
            <h2 class="list__titleBox">Google連携</h2>
            <div class="list__textBox">
              <!--  -->
              <div class="page__contact2">
    
                <div class="contact_form">
    
                  <?php if (is_developer()) { ?>
    
                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">Googleアカウント</h2>
                      <div class="contact_form--box--item">
                        <input type="text" name="site_gmail" value="<?php echo esc_attr($site_gmail); ?>" />
                      </div>
                    </div>
    
                    <div class="contact_form--box">
                      <label for="password" h2 class="contact_form--box--label">パスワード</label>
                      <div class="contact_form--box--item -flex">
                        <input class="password_input" type="password" name="site_gmail_password" value="<?php echo esc_attr($site_gmail_password); ?>" />
                        <button type="button" class="js__showPassword">
                          <i onclick="pushHideButton()" class="fas fa-eye"></i>
                          <!-- <img onclick="pushHideButton()"
                             class="table__editButton"
                             src="<?php echo get_template_directory_uri(); ?>/img/common/icon_passwordEye_1.svg"
                             alt=""> -->
                      </div>
                    </div>
    
                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">クライアントID</h2>
                      <div class="contact_form--box--item">
                        <input type="text" size="40" name="site_client_id" value="<?php echo esc_attr($site_client_id); ?>" />
                      </div>
                    </div>
    
                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">クライアントシークレット</h2>
                      <div class="contact_form--box--item">
                        <input type="text" size="40" name="site_client_secret" value="<?php echo esc_attr($site_client_secret); ?>" />
                      </div>
                    </div>
    
                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">リフレッシュトークン</h2>
                      <div class="contact_form--box--item">
                        <input type="text" size="40" name="site_refresh_token" value="<?php echo esc_attr($site_refresh_token); ?>" />
                      </div>
                    </div>
    
                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">カレンダーID</h2>
                      <div class="contact_form--box--item">
                        <input type="text" size="40" name="site_calendar_id" value="<?php echo esc_attr($site_calendar_id); ?>" />
                      </div>
                    </div>
    
                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">シークレットキー</h2>
                      <div class="contact_form--box--item">
                        <textarea name="site_secret_key" id="" cols="30" rows="10"><?php echo $site_secret_key; ?></textarea>
                      </div>
                    </div>
                  <?php } ?>
    
                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">常にGoogleカレンダーを共有するユーザー</h2>
                    <div class="contact_form--box--item">
                      <select name="site_google_calendar_email_array[]" multiple>
                        <option disabled>--- ユーザーを選んでください ---</option>
                        <?php foreach ($user_administrators_array as $user_administrator) { ?>
                          <option <?php echo (in_array($user_administrator->user_email, $site_google_calendar_email_array)) ? 'selected' : ''; ?> value="<?= $user_administrator->user_email ?>">
                            <?= $user_administrator->display_name ?>
                          </option>
                        <?php } ?>
                        <option>なし</option>
                      </select>
                      <span>Ctrlキーを押しながら選択すると複数選択できます</span>
                    </div>
                  </div>
    
    
    
    
    
                </div>
    
    
              </div>
              <!--  -->
            </div>
            <button class="send" type="submit">更新する</button><br><br><br>
          </div>
        </form>
      </section>
<!-- カレンダー -->
      <section class="list__wrap6">
        <form method="post" id="rampupCalendarSetting" action="<?php echo get_permalink() . '#rampupCalendarSetting'; ?>">
          <input type="hidden" name="rampupCalendarSetting">

          <div class="list__tableInformation" style="margin-bottom:4rem;">
            <h2 class="list__titleBox">営業時間設定</h2>
            <div class="list__textBox">
              <!--  -->
              <div class="page__contact2">

                <div class="contact_form">

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">始業時間</h2>
                    <div class="contact_form--box--item">
                      <select name="site_opening_time">
                        <?php
                        $h = 0;
                        $start_time = new Carbon('00:00');
                        for ($i = 0; $i < 48; $i++) {
                          $mintue_added = $i * 30;
                          $time = $start_time->copy()->addMinutes($mintue_added);
                          $time_format_value = $time->copy()->format('H:i');
                        ?>

                          <option <?php echo ($site_opening_time == $time_format_value) ? 'selected' : ''; ?> value="<?= $time_format_value ?>"><?= $time_format_value ?></option>
                        <?php
                        }
                        ?>
                      </select>
                      <p class="-error"><?php echo (isset($error['operation'])) ? $error['operation'] : ''; ?></p>
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">終業時間</h2>
                    <div class="contact_form--box--item">
                      <select name="site_closing_time">
                        <?php
                        $h = 0;
                        $start_time = new Carbon('00:00');
                        for ($i = 0; $i < 48; $i++) {
                          $mintue_added = $i * 30;
                          $time = $start_time->copy()->addMinutes($mintue_added);
                          $time_format_value = $time->copy()->format('H:i');
                        ?>
                          <option <?php echo ($site_closing_time == $time_format_value) ? 'selected' : ''; ?> value="<?= $time_format_value ?>"><?= $time_format_value ?></option>
                        <?php
                        }
                        ?>
                      </select>
                      <p class="-error"><?php echo (isset($error['operation'])) ? $error['operation'] : ''; ?></p>
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">面談一回あたりの<br>所要時間</h2>
                    <div class="contact_form--box--item -flex">
                      <select name="site_numberOfCalendarCell">
                        <?php
                        for ($i = 1; $i < 17; $i++) {
                          $mintue_value = $i * 30;

                          if ($mintue_value == 30) {
                            $mintue_text = $mintue_value . '分';
                          } elseif ($mintue_value % 60 == 0) {
                            $mintue_text = $mintue_value / 60 . '時間';
                          } elseif ($mintue_value % 60 == 30) {
                            $mintue_text = floor($mintue_value / 60) . '時間30分';
                          }
                        ?>
                          <option <?php echo ($site_numberOfCalendarCell == $mintue_value) ? 'selected' : ''; ?> value="<?= $mintue_value ?>"><?= $i ?>枠（<?= $mintue_text ?>）</option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">デフォルトの面談担当者</h2>
                    <div class="contact_form--box--item">

                      <select name="site_reservation_personInCharge_user_id">

                        <?php
                        foreach ($user_administrators_array as $user_administrator) {
                          if (
                            $site_reservation_personInCharge_user_id == $user_administrator->ID
                          ) {
                            $selected = 'selected';
                          } else {
                            $selected = '';
                          }
                        ?>

                          <option <?= $selected ?> value="<?= $user_administrator->ID ?>">
                            <?= $user_administrator->display_name ?>
                          </option>

                        <?php } ?>

                      </select>

                      <p class="-error"><?php echo (isset($error['personInCharge'])) ? $error['personInCharge'] : ''; ?></p>
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <h2 class="contact_form--box--label">面談担当者のリスト</h2>
                    <div class="contact_form--box--item">
                      <select name="site_reservation_personInCharge_user_id_array[]" multiple>
                        <option disabled>--- ユーザーを選んでください ---</option>
                        <?php foreach ($user_administrators_array as $user_administrator) {

                          if (
                            (int)$site_reservation_personInCharge == $user_administrator->ID ||
                            in_array($user_administrator->ID, $site_reservation_personInCharge_user_id_array)
                          ) {
                            $selected = 'selected';
                          } else {
                            $selected = '';
                          }
                        ?>

                          <option value="<?= $user_administrator->ID ?>" <?= $selected ?>>
                            <?= $user_administrator->display_name ?>
                          </option>

                        <?php } ?>
                      </select>
                      <span>Ctrlキーを押しながら選択すると複数選択できます</span>
                    </div>
                  </div>

                </div>

              </div>
            </div>

            <button class="send" type="submit">送信する</button>
            <br><br><br>

          </div>

        </form>

      </section>
      <?php } ?>
<!-- テストメール -->
      <?php if (is_developer()) { ?>
        <section class="list__wrap6">
          <form method="post" action="<?php echo get_permalink(); ?>">
            <input type="hidden" name="rampupTestEmail">

            <div class="list__tableInformation" style="margin-bottom:4rem;">
              <h2 class="list__titleBox">テストメール送信</h2>
              <div class="list__textBox">
                <!--  -->
                <div class="page__contact2">

                  <div class="contact_form">

                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">宛先</h2>
                      <div class="contact_form--box--item -flex">
                        <input type="email" name="testEmail_target_email" />
                      </div>
                    </div>

                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">件名</h2>
                      <div class="contact_form--box--item -flex">
                        <input type="text" value="件名が入ります" name="testEmail_subject" />
                      </div>
                    </div>

                    <div class="contact_form--box">
                      <h2 class="contact_form--box--label">本文</h2>
                      <div class="contact_form--box--item -flex">
                        <textarea name="testEmail_contents" id="" cols="30" rows="10">本文が入ります</textarea>
                      </div>
                    </div>

                  </div>

                </div>
              </div>

              <button class="send" type="submit">送信する</button>
              <br><br><br>

            </div>

          </form>

        </section>
      <?php } ?>















    </div>

  </div>
</div>
<script>
  (function($) {
    $('.js__showPassword').prev().css({
      'position': 'relative',
      'padding-right': '30px'
    });

    $('.js__showPassword').on('click', function() {
      $(this).toggleClass('js__hidePassword');
      if ($(this).hasClass('js__hidePassword')) {
        $(this).prev().attr('type', 'text');
      } else {
        $(this).prev().attr('type', 'password');

      }
    });


    var url = new URL(window.location.href);
    var params = url.searchParams;
    if (params.get("settings-updated") == "true") {
      alert("変更されました");
    }
  })(jQuery);
</script>
<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<? //php include(get_theme_file_path() . "/footer-default.php")
?>
<?php get_footer();
