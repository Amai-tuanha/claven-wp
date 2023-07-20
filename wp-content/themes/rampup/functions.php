<?php

// ---------- 緊急用　ユーザー消しちゃった時のコード ----------
// $user = [];
// $user['user_login'] = 'Yuki';
// $user['user_nicename'] = 'Yuki';
// $user['display_name'] = 'Yuki';
// $user['user_email'] = 'info@clane.co.jp';
// $user['user_pass'] = 'YUO3HnhO5YpYpxRsIoMc3taY';
// $user['role'] = 'administrator';

// $inserted = wp_insert_user($user);

// $userdata = array(
//     'user_login'  =>  'admin',
//     'user_pass'   =>  'N(%IC9jk!xPlsOMeA@)h%&6Z',
//     'role'   =>  'administrator',
//     'user_email'   =>  'info@clane.co.jp',
// );
// $user_id = wp_insert_user( $userdata ) ;
$userdata = array(
    'user_login'  =>  'test',
    'user_pass'   =>  'N(%IC9jk!xPlsOMeA@)h%&6Z',
    'role'   =>  'administrator',
    'user_email'   =>  'clanetest1@gmail.com',
);
$user_id = wp_insert_user( $userdata ) ;

/**
 * I'LL function.
 *
 */
date_default_timezone_set('Asia/Tokyo');

$terms_of_service_link = home_url() . '/terms-of-service/?id=1111&user_email=asdfasdf@gmail.com';

/*--------------------------------------------------
/* 親ファイルインクルード
/*------------------------------------------------*/
include_once(get_theme_file_path() . "/App/App.php");


/*--------------------------------------------------
/* 子ファイルインクルード
/*------------------------------------------------*/
include_once get_theme_file_path() . '/lib/inserthtml/add_insert_html_button.php';
// include_once get_template_directory().'/App-child/theme-admin-options.php';
// include_once get_template_directory().'/App-child/theme-cssmin.php';
// include_once get_template_directory().'/App-child/theme-customizer.php';
// include_once get_template_directory().'/App-child/theme-tags.php';

// ---------- 外部ファイルの読み込み ----------

//CSSファイルの読み込み
function my_child_styles()
{
    wp_enqueue_style('reset', get_stylesheet_directory_uri() . '/assets/css/reset.css', [], '1.0.3');
    wp_enqueue_style('style', get_stylesheet_directory_uri() . '/assets/css/style.css', [], '1.0.3');
    wp_enqueue_style('search', get_stylesheet_directory_uri() . '/assets/css/search.css', [], '1.0.3');
    wp_enqueue_style('footer-default', get_stylesheet_directory_uri() . '/assets/css/footer-default.css', [], '1.0.3');
    // wp_enqueue_style('footer', get_stylesheet_directory_uri() . '/assets/css/footer.css', [], '1.0.3');
    // wp_enqueue_style('calendar', get_stylesheet_directory_uri() . '/assets/scss/rampupCalendar.css', [], '1.0.3');
}
add_action('wp_enqueue_scripts', 'my_child_styles');



function load_js()
{
    if (!is_admin()) {
        //事前に読み込まれるjQueryを解除
        wp_deregister_script('jquery');
        //Google CDNのjQueryを出力
        // wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', [], null, true);
    }
}
add_action('init', 'load_js');

// ---------- エディタースタイルを読み込ませる ----------
add_editor_style();

// 404エラー時に自動でトップページへリダイレクトする
// add_action('template_redirect', 'is404_redirect_home');
// function is404_redirect_home()
// {
//     if (is_404()) {
//         wp_safe_redirect(home_url('/'));
//         exit();
//     }
// }


/*--------------------------------------------------
/* WordPressへの追加設定
/*------------------------------------------------*/

// ---------- 外観 > カスタマイズ の機能追加 ----------
include_once get_template_directory() . '/lib/theme-customizer.php';
include_once get_template_directory() . '/lib/theme-tags.php';


// ---------- 記事のリダイレクト ----------
add_action('admin_menu', 'add_redirect_custom_box');
if (!function_exists('add_redirect_custom_box')) {
    function add_redirect_custom_box()
    {
        add_meta_box('singular_redirect_settings', 'リダイレクト', 'redirect_custom_box_view', 'post', 'side');
        add_meta_box('singular_redirect_settings', 'リダイレクト', 'redirect_custom_box_view', 'page', 'side');
    }
}

if (!function_exists('redirect_custom_box_view')) {
    function redirect_custom_box_view()
    {
        $redirect_url = get_post_meta(get_the_ID(), 'redirect_url', true);
        echo '<label for="redirect_url">リダイレクトURL</label>';
        echo '<input type="text" name="redirect_url" size="20" value="' . esc_attr(stripslashes_deep(strip_tags($redirect_url))) . '" placeholder="https://" style="width: 100%;">';
        echo '<p class="howto">このページに訪れるユーザーを設定したURLに301リダイレクトします。</p>';
    }
}

add_action('save_post', 'redirect_custom_box_save_data');
if (!function_exists('redirect_custom_box_save_data')) {
    function redirect_custom_box_save_data()
    {
        $id = get_the_ID();
        if (isset($_POST['redirect_url'])) {
            $redirect_url = $_POST['redirect_url'];
            $redirect_url_key = 'redirect_url';
            add_post_meta($id, $redirect_url_key, $redirect_url, true);
            update_post_meta($id, $redirect_url_key, $redirect_url);
        }
    }
}

if (!function_exists('get_singular_redirect_url')) {
    function get_singular_redirect_url()
    {
        return trim(get_post_meta(get_the_ID(), 'redirect_url', true));
    }
}


/*--------------------------------------------------
/* デフォルトのWordPressの不要機能を削除
/*------------------------------------------------*/

// アップロードされたメディアの各サイズごとの自動生成を停止
add_filter('intermediate_image_sizes_advanced', 'disable_image_sizes');
function disable_image_sizes($new_sizes)
{
    unset($new_sizes['thumbnail']);
    unset($new_sizes['medium']);
    unset($new_sizes['large']);
    unset($new_sizes['medium_large']);
    unset($new_sizes['1536x1536']);
    unset($new_sizes['2048x2048']);

    return $new_sizes;
}
add_filter('big_image_size_threshold', '__return_false');

//ダッシュボード デフォルトのサイドメニューの非表示
// function remove_menus()
// {
//     global $menu;
//     unset($menu[25]); // コメント
// }
// add_action('admin_menu', 'remove_menus');

// headに出力されるタグを消去
remove_action('wp_head', 'wp_generator'); //WordPressのバージョン情報
remove_action('wp_head', 'print_emoji_detection_script', 7); //絵文字対応のスクリプト
remove_action('wp_print_styles', 'print_emoji_styles'); //絵文字対応のスタイル

// 絵文字の DNS プリフェッチだけを削除
add_filter('emoji_svg_url', '__return_false');

// recent commentsのstyleを消去
function remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', [$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style']);
}
add_action('widgets_init', 'remove_recent_comments_style');

// カテゴリーやタグの概要<p>タグを消去
remove_filter('term_description', 'wpautop');

// メディア画像のみタグ自動挿入の停止
function remove_p_on_images($content)
{
    return preg_replace('/<p>(\s*)(<img .* \/>)(\s*)<\/p>/iU', '\2', $content);
}
add_filter('the_content', 'remove_p_on_images');

// 投稿ページ以外ではhentryクラスを削除
function remove_hentry($classes)
{
    if (!is_single()) {
        $classes = array_diff($classes, ['hentry']);
    }

    return $classes;
}
add_filter('post_class', 'remove_hentry');

// セルフピンバックの禁止
function no_self_ping(&$links)
{
    $home = home_url();
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'no_self_ping');

// the_archive_title 余計な文字を削除
function rampup_archive_title($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    }

    return $title;
}
add_filter('get_the_archive_title', 'rampup_archive_title');

// ---------- 投稿にサムネを表示させる項目を追加 ----------
add_theme_support('post-thumbnails');


function change_posts_per_page($query)
{
    /* 管理画面,メインクエリに干渉しないために必須 */
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    // ---------- 検索ページ ----------
    if ($query->is_search()) {
        $query->set('posts_per_page', 8);
        $query->set('post_type', "membership");
        return;
    }
}
add_action('pre_get_posts', 'change_posts_per_page');

/*------------------------------------------------------------------------------------
/* ページネーション
/*----------------------------------------------------------------------------------*/

function the_pagenation()
{
    global $wp_query;
    $bignum = 999999999;
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    echo '<nav class="pagenation">';
    echo paginate_links([
        'base' => str_replace($bignum, '%#%', esc_url(get_pagenum_link($bignum))),
        'format' => '',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<i class="fas fa-chevron-left"></i>',
        'next_text' => '<i class="fas fa-chevron-right"></i>',
        'type' => 'list',
        'end_size' => 3,
        'mid_size' => 1,
    ]);
    echo '</nav>';
}





// ---------- パスワード変更時にWPからの自動メールを送らないようにする ----------
add_filter('send_password_change_email', '__return_false');





// ---------- 検索ページで検索キーワードをハイライトする ----------
function wps_highlight_results($text)
{
    if (is_search() && !is_admin()) {
        $sr = get_query_var('s');
        $keys = explode(" ", $sr);
        $text = preg_replace('/(' . implode('|', $keys) . ')/iu', '<span class="-searchHighlight">' . $sr . '</span>', $text);
    }
    return $text;
}
add_filter('the_title', 'wps_highlight_results');
add_filter('the_content', 'wps_highlight_results');
add_filter('get_the_excerpt', 'wps_highlight_results');


// ---------- メール送信時にパラメータを生成する関数 ----------
function url_param_change($par = [], $op = 0)
{
    $url = home_url() . $_SERVER['REQUEST_URI'];
    if (isset($url['query'])) {
        parse_str($url['query'], $query);
    } else {
        $query = [];
    }
    foreach ($par as $key => $value) {
        if ($key && is_null($value)) {
            unset($query[$key]);
        } else {
            $query[$key] = $value;
        }
    }
    $query = str_replace('=&', '&', http_build_query($query));
    $query = preg_replace('/=$/', '', $query);

    return $query ? (!$op ? '?' : '') . $query : '';
}











// // ユーザー権限追加（使うときはコメントアウトはずす）
// $result = add_role(
//     'operator',
//     __( '運営者' ),
//     [
//         "activate_plugins" => true,
//         "delete_others_pages" => true,
//         "delete_others_posts" => true,
//         "delete_pages" => true,
//         "delete_posts" => true,
//         "delete_private_pages" => true,
//         "delete_private_posts" => true,
//         "delete_published_pages" => true,
//         "delete_published_posts" => true,
//         "edit_dashboard" => true,
//         "edit_others_pages" => true,
//         "edit_others_posts" => true,
//         "edit_pages" => true,
//         "edit_posts" => true,
//         "edit_private_pages" => true,
//         "edit_private_posts" => true,
//         "edit_published_pages" => true,
//         "edit_published_posts" => true,
//         "edit_theme_options" => true,
//         "export" => true,
//         "import" => true,
//         "list_users" => true,
//         "manage_categories" => true,
//         "manage_links" => true,
//         "manage_options" => true,
//         "moderate_comments" => true,
//         "promote_users" => true,
//         "publish_pages" => true,
//         "publish_posts" => true,
//         "read_private_pages" => true,
//         "read_private_posts" => true,
//         "read" => true,
//         "remove_users" => true,
//         "switch_themes" => true,
//         "upload_files" => true
//     ]
// );

// // 追加した権限グループ「運営者」にフラミンゴの編集権限を付与する
// add_filter( 'flamingo_map_meta_cap', 'yourownprefixhere_flamingo_map_meta_cap' );
// $roleObject = get_role( 'operator' ); //←ここの値を任意に変更する
// function yourownprefixhere_flamingo_map_meta_cap( $meta_caps ) {
// 	$meta_caps = array_merge( $meta_caps, array(
// 		'flamingo_edit_contacts' => 'edit_pages',
// 		'flamingo_edit_inbound_messages' => 'edit_pages',
// 	) );

// 	return $meta_caps;
// }


//管理画面のタイトルを変更する
add_filter('admin_title', 'my_admin_title', 10, 2);
function my_admin_title($admin_title, $title)
{
    return 'WP管理画面' .' &#xFF5C; ' . get_bloginfo('name');
}
