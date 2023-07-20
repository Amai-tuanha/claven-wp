<?php
/*
@package    WordPress
@subpackage my_plugin
@author  Samurai6111 <samurai.blue6111@gmail.com>

Plugin Name: My wpdb
Text Domain: my_plugin
Description: Wordpressで管理画面からDBを編集することが出来るプラグイン
Author: Shota Kawakatsu
Author URI: https://github.com/Samurai6111
Version: 1.0
Plugin URI: https://github.com/Samurai6111/my-wpdb
*/



// ---------- ダッシュボードにページ作成 ----------
function add_custom_page()
{
    add_menu_page(
        __('MY WPDB'),
        __('MY WPDB'),
        'manage_options',
        'my_wpdb_page',
        'my_wpdb_page',
        'dashicons-calendar-alt',
        100
    );
}
add_action('admin_menu', 'add_custom_page');

function my_wpdb_page()
{

    if (is_admin() && $_GET['page'] == 'my_wpdb_page') {
        include_once(plugin_dir_path(__FILE__) . "load-head.php");

        // ---------- 変数ファイルインクルード ----------
        include_once(plugin_dir_path(__FILE__) . "variables.php");

        // ---------- wpdbのアクション ----------
        include_once(plugin_dir_path(__FILE__) . "/model/wpdb-actions.php");

        // ---------- テンプレート読み込み ----------
        include_once(plugin_dir_path(__FILE__) . "view/pages/views.php");

        // ---------- 読み込み ----------
        include_once(plugin_dir_path(__FILE__) . "load-footer.php");
    }
}
