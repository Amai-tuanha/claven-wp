<?php
 if (!function_exists('add_custom_dashboard_widget_2')){
     function add_custom_dashboard_widget_2() {
         wp_add_dashboard_widget('adfads', 'オリジナルウィジェット', function() { ?>
     オリジナルウィジェット
     <?php });
     }
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget_2' );
 ?>