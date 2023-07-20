<?php
/**
* Tinymce add insert_html button
* @package WordPress
* @subpackage I'LL

*/

// ビジュアルエディタにボタン追加
add_filter( 'mce_buttons', 'add_insert_html_button' );
function add_insert_html_button( $buttons ) {
	$buttons[] = 'button_insert_html';
	return $buttons;
}

// // HTML挿入ボタン動作用のJavaScript追加
// add_filter( 'mce_external_plugins', 'add_insert_html_button_plugin' );
// function add_insert_html_button_plugin( $plugin_array ) {
// 	$plugin_array['custom_button_script'] = get_template_directory_uri() . "/lib/inserthtml/plugin_insert_html.js";
// 	return $plugin_array;

// }

// ダイアログのTitle設定
add_action( 'admin_head', 'add_insert_insert_html_title_js' );
function add_insert_insert_html_title_js() {
	echo '<script type="text/javascript">
	var insert_html_button_title = "'.__( 'Insert HTML', 'rampup' ).'";
	var insert_html_window_title = "'.__( 'Please insert HTML', 'rampup' ).'";';
	echo '</script>';
}
