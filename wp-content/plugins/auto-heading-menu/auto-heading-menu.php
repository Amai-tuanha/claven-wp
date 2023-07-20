<?php
/*
  Plugin Name: Auto heading menu
  Description: Hタグを自動取得し、ページの左右にメニュー表示させる
  Version: 1.0.1
  Author:Shota Kawakatsu
  Author URI: https://github.com/Samurai6111/auto-heading-menu
  License: GPLv2
 */
//ショートコードで「hello shortcode!」と出力
function auto_heading_menu() {
    // return "hello shortcode!";
    wp_enqueue_script('auto-heading-menu', plugins_url('/assets/js/auto-heading-menu.js', __FILE__), [], '1.0.0', true);
    wp_enqueue_style('ahm-front', plugins_url('/assets/css/ahm-front.css', __FILE__), '', '20160608');

    return;
}
add_shortcode('auto_heading_menu', 'auto_heading_menu');
