<?php

/**
 * Template header
 * @package WordPress
 * @subpackage I'LL

 */
// function rampup_html_compress_start() {
// 	$html_minified = get_theme_mod( 'html_minified', '' );
// 	if ( $html_minified ) {
// 		$ob_start = ob_start();
// 		return $ob_start;
// 	}
// }
?>
<!DOCTYPE html>
<?php rampup_html_compress_start(); ?>
<html <?php language_attributes(); ?>
      dir="ltr">

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
  <!-- Google Tag Manager -->

  <!-- End Google Tag Manager -->

  <meta http-equiv="Content-Type"
        content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
  <meta name="viewport"
        content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <?php wp_head(); ?>

  <!-- Stylesheet	 -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
  <script src="https://kit.fontawesome.com/6ff808ba48.js"
          crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.js"
          integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
          crossorigin="anonymous"></script>
</head>

<body id="top"
      class="body">

  <!-- Google Tag Manager (noscript) -->
  
  <!-- End Google Tag Manager (noscript) -->