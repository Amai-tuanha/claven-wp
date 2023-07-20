<?php

use Carbon\Carbon;

// add_action('page_controlpanel_setting__send_form' , 'page_controlpanel_setting__send_form');

// function page_controlpanel_setting__send_form() {

$error = [];
/*--------------------------------------------------
	/* wp_optionsに追加する
	/*------------------------------------------------*/
// ---------- register our settings ----------

global $site_secret_key;
if (isset($_POST['siteOptionsTrigger']) || isset($_POST['siteGoogleTrigger'])) {

	foreach ($_POST as $key => $value) {
		// ---------- $keyが「site_」を含んでいた場合 ----------
		if (strpos($key, 'site_') !== false) {
			if (is_array($value)) {
				$value = json_encode($value);
			}
			// register_setting('controlpanel-setting', $key);
			update_option($key, stripslashes($value));
		}
	}

	// ---------- シークレットキーを上書き ----------
	$secretKeyPath = get_theme_file_path() . '/App/GoogleService/SecretKey.json';
	ob_start();
		$contents = $site_secret_key;
	ob_end_clean();
	file_put_contents(get_theme_file_path() . '/App/GoogleService/SecretKey.json', $contents);
	wp_safe_redirect(get_current_link());
	// wp_safe_redirect( wp_get_referer(  ) );
	exit;
}

/*--------------------------------------------------
/* RAMPUPカレンダーの設定
/*------------------------------------------------*/
if (isset($_POST['rampupCalendarSetting'])) {

	// ---------- 始業時間と終業時間のバリデーション ----------
	$site_opening_time_POST = $_POST['site_opening_time'];
	$site_closing_time_POST = $_POST['site_closing_time'];
	$site_opening_time_carbon = new Carbon($site_opening_time_POST);
	$site_closing_time_carbon = new Carbon($site_closing_time_POST);

	if ($site_opening_time_carbon > $site_closing_time_carbon) {
		$error['operation'] = '終業時間は始業時間より後の時間を指定してください';
	}

	// ---------- デフォルトの面談担当者のバリデーション ----------
	$site_reservation_personInCharge_user_id_array_POST = $_POST['site_reservation_personInCharge_user_id_array'];
	$site_reservation_personInCharge_user_id_POST = $_POST['site_reservation_personInCharge_user_id'];
	if (!in_array($site_reservation_personInCharge_user_id_POST, $site_reservation_personInCharge_user_id_array_POST)) {
		$error['personInCharge'] = 'デフォルトの面談担当者は面談担当者のリストの中から選んでください';
	}

	if (0 === count($error)) {
		foreach ($_POST as $key => $value) {
			// ---------- $keyが「site_」を含んでいた場合 ----------
			if (strpos($key, 'site_') !== false) {
				if (is_array($value)) {
					$value = json_encode($value);
				}
				update_option($key, stripslashes($value));
			}
		}

		// wp_safe_redirect(get_current_link());
		wp_safe_redirect( wp_get_referer(  ) );
		exit;
	}
}

/*--------------------------------------------------
/* テストメール送信
/*------------------------------------------------*/
if (isset($_POST['rampupTestEmail'])) {
	$testEmail_target_email = esc_attr($_POST['testEmail_target_email']);
	$testEmail_subject = esc_attr($_POST['testEmail_subject']);
	$testEmail_contents = esc_attr($_POST['testEmail_contents']);
	my_PHPmailer_function(
		do_shortcode($testEmail_subject),
		do_shortcode($testEmail_contents),
		$testEmail_target_email,
		null,
		null
	);

	wp_safe_redirect(get_current_link());
	exit;
}


// }