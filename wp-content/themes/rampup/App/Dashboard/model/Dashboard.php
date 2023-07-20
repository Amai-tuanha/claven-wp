<?php

use Carbon\Carbon;
?>

<?php
if (!function_exists('my_admin_script')) {
	function my_admin_script($hook)
	{

		if (
			'edit.php' == $hook &&
			$_GET['post_status'] == 'cancelled' &&
			$_GET['post_type'] == 'reservation'
		) {
		}
		wp_enqueue_script('my-dashboard', get_template_directory_uri() . '/App/Dashboard/lib/js/my-dashboard.js', array('jquery'), '', true);
	}
}
add_action('admin_enqueue_scripts', 'my_admin_script');

/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
// include get_theme_file_path() . "/App/Dashboard/lib/view/pages/dashboard-front-widget1.php";
// include get_theme_file_path() . "/App/Dashboard/lib/view/pages/dashboard-front-widget2.php";
include get_theme_file_path() . "/App/Dashboard/model/dashboard-reservationPost.php";
// include get_theme_file_path() . "/App/Dashboard/lib/view/template/dashboard-reservationPost.php";
// include(get_theme_file_path() . "/App/Dashboard/model/dashboard-users.php");
// $dir = get_theme_file_path() . '/App/Dashboard/model/';
// $filelist = glob($dir . '*.php');
// foreach ($filelist as $filepath) {
//     $pieces = explode('/', $filepath);
//     $count = count($pieces) - 1;
//     if (
//         strpos($filepath, '-copy') !== false ||
//         $pieces[$count] == 'Dashboard.php' ||
//         $pieces[$count] == 'reservation-loop.php'
//     ) {
//         continue;
//     }
//     include $filepath;
// }



/*--------------------------------------------------
/* カスタム投稿追加
/*------------------------------------------------*/
// ---------- カスタム投稿（会員限定） ----------
register_post_type(
	'membership', // カスタム投稿名(半角英字)
	[
		'labels' => [
			'name' => __('学習コンテンツ'), //管理画面に表示される文字（日本語OK)
			'singular_name' => __('学習コンテンツ'),
		],
		//投稿タイプの設定
		'public' => true, //公開するかしないか(デフォルト"true")
		'has_archive' => true, //trueにすると投稿した記事のアーカイブページを生成
		'menu_position' => 5, // 管理画面上でどこに配置するか
		//投稿編集ページの設定
		'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'], //タイトル，編集，アイキャッチ対応)
		'menu_icon' => 'dashicons-lock', // アイコン
		'show_in_rest' => true,
		// 'hierarchical' => true,
	]
);

// ---------- カスタム投稿（面談日程） ----------
register_post_type(
	'reservation', // カスタム投稿名(半角英字)
	[
		'labels' => [
			'name' => __('面談日程'), //管理画面に表示される文字（日本語OK)
			'singular_name' => __('面談日程'),
		],
		//投稿タイプの設定
		'public' => true, //公開するかしないか(デフォルト"true")
		'has_archive' => true, //trueにすると投稿した記事のアーカイブページを生成
		'menu_position' => 5, // 管理画面上でどこに配置するか
		//投稿編集ページの設定
		'supports' => ['title'], //タイトル，編集，アイキャッチ対応)
		'menu_icon' => 'dashicons-calendar-alt', // アイコン
		'show_in_rest' => true,
		// 'register_meta_box_cb' => 'add_reservation_meta_box'
	]
);

// ---------- カスタム投稿（メール文章） ----------
register_post_type(
	'email', // カスタム投稿名(半角英字)
	[
		'labels' => [
			'name' => __('メール'), //管理画面に表示される文字（日本語OK)
			'singular_name' => __('メール'),
		],
		//投稿タイプの設定
		'public' => true, //公開するかしないか(デフォルト"true")
		'has_archive' => true, //trueにすると投稿した記事のアーカイブページを生成
		'menu_position' => 5, // 管理画面上でどこに配置するか
		//投稿編集ページの設定
		'supports' => ['title', 'editor'], //タイトル，編集，アイキャッチ対応)
		'menu_icon' => 'dashicons-email', // アイコン
		'show_in_rest' => true,
	]
);

// ---------- カスタム投稿（勉強会） ----------
register_post_type(
	'seminar', // カスタム投稿名(半角英字)
	[
		'labels' => [
			'name' => __('勉強会'), //管理画面に表示される文字（日本語OK)
			'singular_name' => __('勉強会'),
		],
		//投稿タイプの設定
		'public' => true, //公開するかしないか(デフォルト"true")
		'has_archive' => true, //trueにすると投稿した記事のアーカイブページを生成
		'menu_position' => 5, // 管理画面上でどこに配置するか
		//投稿編集ページの設定
		'supports' => ['title', 'editor', 'thumbnail'], //タイトル，編集，アイキャッチ対応)
		'menu_icon' => 'dashicons-lock', // アイコン
		'show_in_rest' => true,
	]
);

/*--------------------------------------------------
/* カスタムタクソノミー追加
/*------------------------------------------------*/
// ---------- タクソノミー 会員限定 ----------
register_taxonomy(
	'membership_taxonomy', //カスタムタクソノミー名
	'membership', //このタクソノミーが使われる投稿タイプ
	[
		'label' => 'STEP', //カスタムタクソノミーのラベル
		'labels' => [
			'popular_items' => 'よく使うカテゴリー',
			'edit_item' => 'カテゴリーを編集',
			'add_new_item' => '新規カテゴリーを追加',
			'search_items' => 'カテゴリーを検索',
		],
		'public' => true, // 管理画面及びサイト上に公開
		'description' => 'カテゴリーの説明文です。', //説明文
		'hierarchical' => true, //カテゴリー形式
		'show_in_rest' => true, //Gutenberg で表示
	]
);
// ---------- タクソノミー メール文章 ----------
register_taxonomy(
	'email_taxonomy', //カスタムタクソノミー名
	'email', //このタクソノミーが使われる投稿タイプ
	[
		'label' => 'カテゴリ', //カスタムタクソノミーのラベル
		'labels' => [
			'popular_items' => 'よく使うカテゴリー',
			'edit_item' => 'カテゴリーを編集',
			'add_new_item' => '新規カテゴリーを追加',
			'search_items' => 'カテゴリーを検索',
		],
		'public' => true, // 管理画面及びサイト上に公開
		'description' => 'カテゴリーの説明文です。', //説明文
		'hierarchical' => true, //カテゴリー形式
		'show_in_rest' => true, //Gutenberg で表示
	]
);


function insert_custom_terms()
{
	/*--------------------------------------------------
    /* メール文章カテゴリ追加
    /*------------------------------------------------*/
	// if (!term_exists("email_interviewReservation")) {
	// 	wp_insert_term(
	// 		"面談予約者への案内メール",
	// 		"email_taxonomy",
	// 		['slug' => 'email_interviewReservation']
	// 	);
	// }

	// if (!term_exists("email_application")) {
	// 	wp_insert_term(
	// 		"申し込み者への支払い案内メール",
	// 		"email_taxonomy",
	// 		['slug' => 'email_application']
	// 	);
	// }

	// if (!term_exists("email_beforeStart")) {
	// 	wp_insert_term(
	// 		"サービス開始前",
	// 		"email_taxonomy",
	// 		['slug' => 'email_beforeStart']
	// 	);
	// }
	// if (!term_exists("email_others")) {
	// 	wp_insert_term(
	// 		"その他",
	// 		"email_taxonomy",
	// 		['slug' => 'email_others']
	// 	);
	// }


	//*--------------------------------------------------
	/* 学習コンテンツカテゴリ追加
    /*------------------------------------------------*/
	if (!term_exists("step1")) {
		wp_insert_term(
			"STEP1",
			"membership_taxonomy",
			['slug' => 'step1']
		);
	}
	if (!term_exists("step2")) {
		wp_insert_term(
			"STEP2",
			"membership_taxonomy",
			['slug' => 'step2']
		);
	}
	if (!term_exists("step3")) {
		wp_insert_term(
			"STEP3",
			"membership_taxonomy",
			['slug' => 'step3']
		);
	}
}
add_action('init', 'insert_custom_terms');

/*--------------------------------------------------
/* 投稿ステータス追加
/*------------------------------------------------*/
if (!function_exists('my_custom_post_status')) {
	function my_custom_post_status()
	{
		global
			$reservation_post_status_array;

		foreach ($reservation_post_status_array as $key => $value) {
			register_post_status($key, [
				'label' => _x($value, 'reservation'),
				'public' => true,
				'exclude_from_search' => true,
				'show_in_admin_all_list' => true,
				'show_in_admin_status_list' => true,
				'label_count' => _n_noop($value . '<span class="count">(%s)</span>', $value . '<span class="count">(%s)</span>'),
			]);
		}
	}
}
add_action('init', 'my_custom_post_status');

/*--------------------------------------------------
/* 投稿を保存したときカテゴリを自動追加する
/* @param int  $post_ID  投稿 'ID。
/* @param post $post 投稿オブジェクト。
/* @param bool $update   既存投稿の更新か否か。
/*------------------------------------------------*/
if (!function_exists('insert_category_to_post')) {
	function insert_category_to_post($post_id)
	{
		// ---------- カスタム投稿「「会員コンテンツ」 ----------
		if (!wp_get_post_terms($post_id, 'membership_taxonomy')) {
			$term_info_array = get_term_by('slug', 'step1', 'membership_taxonomy');
			$term_id = $term_info_array->term_id;
			wp_set_post_terms($post_id, $term_id, 'membership_taxonomy');
		}
	}
}
add_action('save_post', 'insert_category_to_post');

/*--------------------------------------------------
/* 管理画面の投稿一覧のループの設定
/*------------------------------------------------*/
add_action('pre_get_posts', function ($query) {
	global $wpdb, $pagenow;

	if (!is_admin()) {
		return;
	}

	$orderby = $query->get('orderby');
	if ($orderby == 'reservation_date') {
		$query->set('orderby', 'meta_key');
		$query->set('meta_key', 'reservation_date');
		$query->set('orderby', 'meta_value_num');
	}

	/*--------------------------------------------------
    /* 固定ページ一覧とシステムページ一覧の出しわけ
    /*------------------------------------------------*/
	$rampup_default_page_id_array = [];
	$rampup_default_pages_array = $wpdb->get_results('SELECT * FROM `' . $wpdb->prefix . 'postmeta` WHERE meta_key ="rampup_default_page"');
	foreach ($rampup_default_pages_array as $rampup_default_pages) {
		$rampup_default_page_id = $rampup_default_pages->post_id;
		array_push($rampup_default_page_id_array, $rampup_default_page_id);
	}
	$meta_key = $_GET['meta_key'];
	if (
		$pagenow == 'edit.php' &&
		$query->query['post_type'] == 'page'
	) {
		if (isset($meta_key)) {
			$query->set('post__in', $rampup_default_page_id_array);
		} else {
			$query->set('post__not_in', $rampup_default_page_id_array);
		}
	}

	return $query;
});

// ---------- カスタム投稿「面談日程」のリッチディタ削除 ----------
if (!function_exists('my_remove_meta_boxes')) {
	function my_remove_meta_boxes()
	{
		remove_meta_box('postdivrich', 'reservation', 'normal');
	}
}
add_action('admin_menu', 'my_remove_meta_boxes');

/*--------------------------------------------------
/* adminメニューバーカスタマイズ
/*------------------------------------------------*/
if (!function_exists('custom_menu_adminbar')) {
	function custom_menu_adminbar($wp_adminbar)
	{
		$wp_adminbar->add_node([
			'id' => 'mypage',
			'title' => 'マイページ',
			'href' => '/mypage',
		]);
		$wp_adminbar->add_node([
			'id' => 'controlpanel-dashboard',
			'title' => 'コントロールパネル',
			'href' => '/controlpanel-dashboard/',
		]);

		$wp_adminbar->remove_node('wp-logo');      // WPロゴ
		if(!is_developer()){

	
			// $wp_adminbar->remove_node('site-name');    // サイト名
			// $wp_adminbar->remove_node('view-site');    // サイト名 -> サイトを表示
			// $wp_adminbar->remove_node('dashboard');    // サイト名 -> ダッシュボード (公開側)
			// $wp_adminbar->remove_node('themes');       // サイト名 -> テーマ (公開側)
			// $wp_adminbar->remove_node('my-account');   // マイアカウント
			// $wp_adminbar->remove_node('user-info');    // マイアカウント -> プロフィール
			// $wp_adminbar->remove_node('edit-profile'); // マイアカウント -> プロフィール編集
			// $wp_adminbar->remove_node('logout');       // マイアカウント -> ログアウト
	
			$wp_adminbar->remove_node('customize');    // サイト名 -> カスタマイズ (公開側)
			$wp_adminbar->remove_node('comments');     // コメント
			$wp_adminbar->remove_node('updates');      // 更新
			$wp_adminbar->remove_node('view');         // 投稿を表示
			$wp_adminbar->remove_node('new-content');  // 新規
			$wp_adminbar->remove_node('new-post');     // 新規 -> 投稿
			$wp_adminbar->remove_node('new-media');    // 新規 -> メディア
			$wp_adminbar->remove_node('new-link');     // 新規 -> リンク
			$wp_adminbar->remove_node('new-page');     // 新規 -> 固定ページ
			$wp_adminbar->remove_node('new-user');     // 新規 -> ユーザー
			$wp_adminbar->remove_node('new-content');     // 新規 -> ユーザー
			$wp_adminbar->remove_node('aioseo-main');     // aioseo
			$wp_adminbar->remove_node('search');       // 検索 (公開側)
			$wp_adminbar->remove_node('updates');       // 検索 (公開側)
		}
	}
}
add_action('admin_bar_menu', 'custom_menu_adminbar', 1000);

/*--------------------------------------------------
/* サイドバーのメニューを非表示にする
/*------------------------------------------------*/
function remove_menus() {
	if(!is_developer()){
		// remove_menu_page( 'index.php' ); // ダッシュボード
		// remove_menu_page( 'edit.php' ); // 投稿
		remove_menu_page( 'edit.php?post_type=campaign' ); // カスタム投稿タイプcampaign
		// remove_menu_page( 'upload.php' ); // メディア
		remove_menu_page( 'edit.php?post_type=reservation' ); // 面談予約
		remove_menu_page( 'edit.php?post_type=email' ); // メール
		remove_menu_page( 'edit-comments.php' ); // コメント
		remove_menu_page( 'themes.php' ); // 外観
		remove_menu_page( 'plugins.php' ); // プラグイン
		remove_menu_page( 'users.php' ); // ユーザー
		remove_menu_page( 'tools.php' ); // ツール
		remove_menu_page( 'options-general.php' ); // 設定 
		remove_menu_page( 'ai1wm_export' ); // WP_mygration 
		remove_menu_page( 'aioseo' ); // All in one SEO 
		remove_menu_page( 'edit.php?post_type=acf-field-group' ); // カスタムフィールド
		remove_menu_page( 'wpfs-transactions' ); // WP_FULL_STRIPE 
		remove_menu_page( 'edit.php?post_type=tinymcetemplates' ); // TinyMCE
		remove_menu_page( 'my_wpdb_page' ); // MYWPDB 
	}

} 
add_action( 'admin_menu', 'remove_menus', 10000 );

/*--------------------------------------------------
/* サイドバーにオリジナルページ追加
/*------------------------------------------------*/
// ---------- ページ追加 ----------
if (!function_exists('wp_my_settings_create_menu')) {
	function wp_my_settings_create_menu()
	{

		/*--------------------------------------------------
         /* 固定ページのサブメメニューに「システムページ」追加
         /*------------------------------------------------*/
		add_submenu_page(
			'edit.php?post_type=page',
			'システムページ',
			'システムページ',
			'administrator',
			'edit.php?post_type=page&meta_key=rampup_default_page',
			0
		);
	}
}
add_action('admin_menu', 'wp_my_settings_create_menu');

/*--------------------------------------------------
/* 管理画面サイドバーをカスタマイズ
/*------------------------------------------------*/
// ---------- 管理画面サイドバー　文言変更 ----------
if (!function_exists('change_admin_sidebar_label')) {
	function change_admin_sidebar_label()
	{
		global $menu;
		global $submenu;

		/*--------------------------------------------------
         /* サイドバーの文言変更
         /*------------------------------------------------*/
		// ---------- ユーザー ----------
		$menu[70][0] = "ユーザー管理";
		$submenu['users.php'][5][0] = "顧客一覧";
		$submenu['users.php'][5][2] = "users.php?role=subscriber";
		$submenu['users.php'][15][0] = "管理者一覧";
		$submenu['users.php'][15][2] = "users.php?role=administrator";

		// ---------- 面談日程 ----------
		// $menu[7][0] = "asdfadsf";
		// $menu[7][2] = "edit.php?post_status=scheduled&post_type=reservation";
		$submenu['edit.php?post_type=reservation'][5][2] = "edit.php?post_status=scheduled&post_type=reservation";

		/*--------------------------------------------------
         /* サイドバーの項目削除
         /*------------------------------------------------*/
		unset($submenu['users.php'][10]); // ユーザー新規追加
	}
}
add_action('admin_menu', 'change_admin_sidebar_label');

// // ---------- テスト用コード ----------
// add_action('admin_init', 'dump_admin_menu');
// function dump_admin_menu()
// {
// if (is_admin()) {
// header('Content-Type:text/plain');
// var_dump($GLOBALS['menu']);
// // var_dump($GLOBALS['submenu']);
// exit;
// }
// }

/*--------------------------------------------------
/* 管理画面のサイドバーのセパレーター設置
/*------------------------------------------------*/
if (!function_exists('add_separators_admin_menu')) {
	function add_separators_admin_menu()
	{
		//ADD SEPARATORS AFTER THESE MENU ITEMS
		$separatorsAfter = [
			'ダッシュボード',
			'固定ページ',
			'勉強会',
			'メール文章',
			'顧客管理',
			'RAMPUP',
		];

		global $menu;
		if (is_admin()) {
			foreach ((array) $separatorsAfter as $s) {
				foreach ((array) $menu as $key => $item) {
					if (strpos($item[0], $s) !== false) {
						array_splice($menu, $key + 1, 0, array(array(
							0 => '',
							1 => 'read',
							2 => 'separator-last',
							3 => '',
							4 => 'wp-menu-separator',
						)));
						break;
					}
				}
			}
		}
	}
}
add_action('admin_init', 'add_separators_admin_menu');

/*--------------------------------------------------
/* 管理画面のサイドバーのメニューの並び替え
/*------------------------------------------------*/
if (!function_exists('my_custom_menu_order')) {
	function my_custom_menu_order($menu_order)
	{

		if (!$menu_order) {
			return true;
		}

		return array(
			'index.php', //ダッシュボード

			'edit.php', //投稿
			'upload.php', //メディア
			'edit.php?post_type=page', //固定ページ

			'edit.php?post_type=membership', //カスタムポスト
			'edit.php?post_type=seminar', //カスタムポスト

			'edit.php?post_type=reservation', //カスタムポスト
			'edit.php?post_type=email', //カスタムポスト

			'users.php?role=subscriber', //ユーザー
			'users.php', //ユーザー

			'themes.php', //外観
			'plugins.php', //プラグイン
			'tools.php', //ツール
			'options-general.php', //設定
			'rampup-setting-page', //設定
		);
	}
}
add_filter('custom_menu_order', 'my_custom_menu_order');
add_filter('menu_order', 'my_custom_menu_order');

/*--------------------------------------------------
/* 「ダッシュボードページ」のウィジェットを削除
/*------------------------------------------------*/
if (!function_exists('remove_dashboard_widgets')) {
	function remove_dashboard_widgets()
	{
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
	}
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

/*--------------------------------------------------
/* 外観 > メニューの位置設定
/*------------------------------------------------*/
add_theme_support('title-tag');
add_theme_support('customize-selective-refresh-widgets');

/*--------------------------------------------------
/* メニューの追加
/*------------------------------------------------*/
register_nav_menus(
	[
		'mypage-menu-before-contract' => __('マイページメニュー 契約前'),
		'mypage-menu-after-contract' => __('マイページメニュー 契約後'),
		'header-menu-controlpanel' => __('コントロールパネル'),
	],
);

/*--------------------------------------------------
/* 「マイページメニュー 契約前」の初期値を設定
/*------------------------------------------------*/
$menuname = 'マイページメニュー 契約前';
$menu_exists = wp_get_nav_menu_object($menuname);
if (empty($menu_exists)) {
	$menu_id = wp_create_nav_menu($menuname);
	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('面談日程追加'),
		'menu-item-classes' => 'menu-mypage-add-reservation',
		'menu-item-url' => home_url('/mypage-add-reservation/'),
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('面談日程変更'),
		'menu-item-classes' => 'menu-mypage-change-reservation',
		'menu-item-url' => home_url('/mypage-change-reservation/'),
		'menu-item-status' => 'publish',
	));

	$menu_slug = 'mypage-menu-before-contract';
	if (!has_nav_menu($menu_slug)) {
		$locations = get_theme_mod('nav_menu_locations');
		$locations[$menu_slug] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations);
	}
}

/*--------------------------------------------------
/* 「マイページメニュー 契約後」の初期値を設定
/*------------------------------------------------*/
$menuname = 'マイページメニュー 契約後';
$menu_exists = wp_get_nav_menu_object($menuname);
if (empty($menu_exists)) {
	$menu_id = wp_create_nav_menu($menuname);
	// wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
	//     'menu-item-title' => __('課題一覧'),
	//     'menu-item-classes' => 'membership',
	//     'menu-item-url' => home_url('/course/'),
	//     'menu-item-status' => 'publish',
	// ));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('学習記録'),
		'menu-item-classes' => 'menu-mypage',
		'menu-item-url' => home_url('/mypage-timer/'),
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('学習進捗'),
		'menu-item-classes' => 'menu-mypage-progress',
		'menu-item-url' => home_url('/mypage-progress/'),
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('勉強会出席'),
		'menu-item-classes' => 'menu-mypage-seminar',
		'menu-item-url' => home_url('/mypage-seminar/'),
		'menu-item-status' => 'publish',
	));

	$menu_slug = 'mypage-menu-after-contract';
	if (!has_nav_menu($menu_slug)) {
		$locations = get_theme_mod('nav_menu_locations');
		$locations[$menu_slug] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations);
	}
}

/*--------------------------------------------------
/* 「管理者用メニュー」の初期値を設定
/*------------------------------------------------*/
$menuname = '管理者用メニュー';
$menu_exists = wp_get_nav_menu_object($menuname);
if (empty($menu_exists)) {
	$menu_id = wp_create_nav_menu($menuname);

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('WEBサイト'),
		'menu-item-classes' => 'menu-controlpanel-home',
		'menu-item-url' => home_url(),
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('マイページ'),
		'menu-item-classes' => 'menu-mypage',
		'menu-item-url' => home_url('/mypage-timer/'),
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('WP管理画面'),
		'menu-item-classes' => 'menu-wp-admin',
		'menu-item-url' => home_url('/wp-admin/'),
		'menu-item-status' => 'publish',
	));

	wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
		'menu-item-title' => __('コントロールパネル'),
		'menu-item-classes' => 'menu-wp-admin',
		'menu-item-url' => home_url('/controlpanel-dashboard/'),
		'menu-item-status' => 'publish',
	));



	// wp_update_nav_menu_item($menu_id, $menu_exists->term_id, array(
	//     'menu-item-title' => __('課題一覧'),
	//     'menu-item-classes' => 'membership',
	//     'menu-item-url' => home_url('/course/'),
	//     'menu-item-status' => 'publish',
	// ));

	$menu_slug = 'controlpanel-menu-header';
	if (!has_nav_menu($menu_slug)) {
		$locations = get_theme_mod('nav_menu_locations');
		$locations[$menu_slug] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations);
	}
}





/*--------------------------------------------------
/*
/* 以下使ってない処理
/*
/*------------------------------------------------*/
// /*--------------------------------------------------
// /* カスタム投稿の一覧ページのカラム追加
// /*------------------------------------------------*/
// // ---------- カラムを追加 ----------
// if (!function_exists('add_custom_columns')) {
//     function add_custom_columns($column_name, $post_id)
//     {
//         $post = get_post($post_id);
//         $post_status = $post->post_status;
//         $post_content = $post->post_content;

//         $reservation_user_id = get_post_meta($post_id, "reservation_user_id", true);
//         $user_info_array = get_user_by('id', $reservation_user_id);

//         // ---------- 会員限定 ----------
//         if ($column_name == 'membership_taxonomy') {
//             echo get_the_term_list($post_id, 'membership_taxonomy', '', ', ');
//         }
//         // ---------- 面談日 ----------
//         elseif ($column_name == 'reservation_date') {
//             $reservation_date = get_post_meta($post_id, "reservation_date", true);
//             echo carbon_formatting($reservation_date);
//         }
//         // ---------- キャンセルされた面談日 ----------
//         elseif ($column_name == 'reservation_date_cancelled') {
//             $reservation_date = get_post_meta($post_id, "reservation_date_cancelled", true);
//             echo carbon_formatting($reservation_date);
//         }
//         // ---------- メールアドレス ----------
//         elseif ($column_name == 'reservationPost_user_email') {
//             echo $user_info_array->user_email;
//         }
//         // ---------- TEL ----------
//         elseif ($column_name == 'reservationPost_user_tel') {
//             echo get_user_meta($reservation_user_id, "user_tel", true);
//         }
//         // ---------- キャンセル理由 ----------
//         elseif ($column_name == 'reservationPost_cancel_reason') {
//             echo get_post_meta($post_id, "cancel_reason", true);
//         }
//         // ---------- キャンセル理由 ----------
//         elseif ($column_name == 'overview_textarea') {
//             echo get_post_meta($post_id, "overview_textarea", true);
//         }
//     }
// }
// add_action('manage_posts_custom_column', 'add_custom_columns', 10, 2);

// // ---------- カラムを表示 ----------
// if (!function_exists('sort_column')) {
//     function sort_column($columns)
//     {
//         if (is_admin()) {
//             if ($_GET["post_type"] === "membership") {
//                 $columns = [
//                     'cb' => '',
//                     'title' => 'タイトル',
//                     'membership_taxonomy' => 'STEP',
//                     'date' => '日時',
//                 ];
//             } elseif ($_GET["post_type"] === "reservation") {
//                 $columns = [
//                     'cb' => '',
//                     'title' => '名前',
//                     'date' => '日時',
//                 ];
//                 if ($_GET['post_status'] == 'cancelled') {
//                     $columns += [
//                         'reservation_date_cancelled' => 'キャンセルされた面談日',
//                         'reservationPost_cancel_reason' => 'キャンセル理由',
//                     ];
//                 } else {
//                     $columns += [
//                         'reservation_date' => '面談日',
//                         'reservationPost_user_email' => 'お客様メールアドレス',
//                         'reservationPost_user_tel' => 'TEL',
//                     ];
//                 }
//             } elseif ($_GET["post_type"] === "seminar") {
//                 $columns = [
//                     'cb' => '',
//                     'title' => '名前',
//                     'overview_textarea' => '概要',
//                 ];
//             }
//             return $columns;
//         }
//     }
// }
// add_filter('manage_edit-membership_columns', 'sort_column');
// add_filter('manage_edit-reservation_columns', 'sort_column');
// add_filter('manage_edit-seminar_columns', 'sort_column');

// // ---------- ソートを可能にする ----------
// add_filter('manage_edit-reservation_sortable_columns', function ($columns) {
//     $columns['reservation_date'] = '面談日';
//     return $columns;
// });

/**
 * 投稿保存直前時に処理を追加する
 *
 * @param int  $post_id  投稿 ID。
 */
function vanilla_pre_post_update($post_id)
{

	/*--------------------------------------------------
  /* 管理画面で固定ページを編集した時に
  /* _wp_page_templateのmetaデータを引き継ぐ
  /*------------------------------------------------*/
	$_wp_page_template = get_post_meta($post_id, '_wp_page_template', true);
	session_start();
	$_SESSION['wp_page_template'] = [$post_id => $_wp_page_template];
}
add_action('pre_post_update', 'vanilla_pre_post_update');


/**
 * 投稿保存時に処理を追加する
 *
 * @param int  $post_id  投稿 ID。
 */
function vanilla_edit_post($post_id)
{

	/*--------------------------------------------------
  /* 管理画面で固定ページを編集した時に
  /* _wp_page_templateのmetaデータを引き継ぐ
  /*------------------------------------------------*/
	session_start();
	foreach ($_SESSION['wp_page_template'] as $key => $value) {
		$slash_count = substr_count($value, "/");
		if ($slash_count >= 2) {
			update_post_meta($key, '_wp_page_template', $value);
		}
	}
	session_unset();
}
add_action('edit_post', 'vanilla_edit_post');




/*--------------------------------------------------
/* カスタム投稿「勉強会」の投稿数が0だったら自動で10件出力する
/*------------------------------------------------*/
$serminarPost_count = wp_count_posts( 'seminar' )->publish;
if ($serminarPost_count == 0) {
    $a = 0;
    while ($a < 10) {
        $a++;
    // ---------- formというスラッグのページがなかったらページ作成 ----------
    $slug = "seminar$a";
    if (!the_slug_exists($slug)) {

        // ---------- 固定ページ作成 ----------
        $seminar_post_array = array(
            'post_status'    => 'publish',
            'post_type'      => 'seminar',
            'post_author'    => 1,
            'post_name'      => $slug,
            'post_title'     => "第" .$a . "回 勉強会",
            'post_content'   => "",
            'post_parent'    => 0,
            'comment_status' => 'closed'
        );
        $inserted_page_id = wp_insert_post($seminar_post_array);
        update_post_meta($inserted_page_id, 'overview_textarea' , "第" .$a . "回 勉強会の概要文が入ります");

    }
        # code...
    }
}