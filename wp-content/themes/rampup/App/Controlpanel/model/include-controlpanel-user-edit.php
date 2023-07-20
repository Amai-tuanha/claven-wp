<?php

use Carbon\Carbon;

global $site_default_contractTerm;


/*--------------------------------------------------
/* user_idを定義
/*------------------------------------------------*/
if (is_page('mypage-change-reservation')) {
	$user_id = get_current_user_id();
} else {
	$user_id = esc_attr($_GET['user_id']);
}

/*--------------------------------------------------
/* 送信された情報をユーザーメタで更新
/*------------------------------------------------*/
if (isset($_POST['usereditFormTrigger'])) {

	// ---------- 変数定義 ----------
	// $post_id = $post->ID;
	if (is_page('mypage-change-reservation')) {
		$user_id = get_current_user_id();
	} else {
		$user_id = esc_attr($_POST['user_id']);
	}

	$class_post = new Rampup_Post($post_id);
	$class_user = new Rampup_User($user_id);
	$now = new Carbon('now');
	$now_formatted = $now->format('Y-m-d');

	// ---------- wp_usersの情報を変更 ----------
	if(isset($_POST['user_email']) && isset($_POST['user_displayName'])){
		wp_update_user([
			'ID' => $user_id,
			'user_email' => $_POST['user_email'],
			'display_name' => $_POST['user_displayName']
		]);
	}

	// ---------- ユーザーメタアップデート ----------
	foreach ($_POST as $key => $value) {
		if (strpos($key, 'user_') !== false) {
			update_user_meta($user_id, $key, $value);

			// ---------- ステータスが申込に変更されたら申込日を取得 ----------
			if (empty($class_user->user_applicationDate) && $_POST['user_status'] == 'application') {
				update_user_meta($user_id, 'user_applicationDate', $now_formatted);
			}
			// ---------- ステータスが決済に変更されたら契約日を取得 ----------
			if (empty($class_user->user_contractDate) && $_POST['user_status'] == 'paid') {
				update_user_meta($user_id, 'user_contractDate', $now_formatted);
			}
		}
	}

	// ---------- メールチェックが入ったら ----------
	if (isset($_POST['emailCheck'])) {
		session_start();
		$class_user = new Rampup_User($user_id);

		$post_id = find_closest_reservation_date($user_id, 'id');
		$class_post = new Rampup_Post($post_id);

		$_SESSION['post_id'] = $post_id;
		$_SESSION['user_id'] = $class_user->user_id;
		$_SESSION['user_status'] = $class_user->user_status;
		$_SESSION['user_paymentMethod'] = $class_user->user_paymentMethod;
		$_SESSION['reservation_meet_url'] = $class_post->reservation_meet_url;

		user_update_email_to_send(
			$class_user->user_email,
			$_SESSION['user_status'],
			$_SESSION['user_paymentMethod'],
			$_SESSION['reservation_meet_url']
		);


		/*--------------------------------------------------
			  /* 「申し込み」の時は追加メールを管理者に送信
			  /*------------------------------------------------*/
		do_action('single_reservation_user_component_email_check', $user_id, $post_id);

		// return carbon_formatting($class_post->reservation_date);
		session_unset();
	}


	/*--------------------------------------------------
	/* プロフィール画像
	/*------------------------------------------------*/
	if (isset($_FILES)) {
		if (!function_exists('wp_generate_attachment_metadata')) {
			include_once ABSPATH . "wp-admin" . '/includes/image.php';
			include_once ABSPATH . "wp-admin" . '/includes/file.php';
			include_once ABSPATH . "wp-admin" . '/includes/media.php';
		}


		foreach ($_FILES as $file => $array) {
			if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
				wp_safe_redirect(get_current_link());
				exit;
			}
			$attach_id = media_handle_upload($file, $new_post);
			$user_avatarURL = wp_get_attachment_url($attach_id);
			update_user_meta($user_id, "user_avatarURL", $user_avatarURL);
		}
		wp_safe_redirect(get_current_link());
		exit;
	}


	do_action('single_reservation_user_component_posts', $user_id);

	wp_safe_redirect(get_current_link());
	exit;
}

// ---------- 変数定義 ----------
// $post_id = $post->ID;
// $booking = $_GET["booking"];
// $class_post = new Rampup_Post($post_id);
$class_user = new Rampup_User($user_id);

// ---------- ユーザーが契約切れの時はステータスを休止中にする ----------

if ($class_user->user_contractEnd <= 0 && $class_user->user_contractDate) {
	update_user_meta($user_id, 'user_status', 'rest');
}