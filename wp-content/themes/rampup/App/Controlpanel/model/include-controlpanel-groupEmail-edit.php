<?php

use Carbon\Carbon;
// add_action('page_controlpanel_groupEmail_edit__send_form' , 'page_controlpanel_groupEmail_edit__send_form');

// function page_controlpanel_groupEmail_edit__send_form() {

	if (isset($_POST['postStatusTrigger'])) {
		// ---------- 変数定義 ----------
	
		// ---------- ユーザーメタアップデート ----------
			$my_post = array(
				'ID'           => $_POST['post_id'],
				'post_status'   => $_POST['post_status'],
			);
			// データベースにある投稿を更新する
			wp_update_post( $my_post );
	
	
	
		wp_safe_redirect(get_current_link());
		exit;
	}
	
// }

/**
 * Template Name: ミーティング予定一覧
 *
 * @Template Post Type: post, page,
 *
 */
?>

