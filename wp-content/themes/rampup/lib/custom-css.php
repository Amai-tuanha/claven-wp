<?php

/**
 * Theme inline style
 * @package WordPress
 * @subpackage I'LL

 *
 * 特定のページにのみ適用されるカスタムCSSの設定
 */
if (!function_exists('rampup_custom_css')) {
	function rampup_custom_css()
	{
		/*--page custom css--*/
		$rampup_custom_css_setting = post_custom('rampup_custom_css_setting');
	}
?>
	<style>
		<?php echo $rampup_custom_css_setting; ?>
	</style>


<?php
}
add_action('wp_head', 'rampup_custom_css');
