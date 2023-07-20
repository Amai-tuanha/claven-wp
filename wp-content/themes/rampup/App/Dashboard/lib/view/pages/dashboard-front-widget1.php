<?php
 if (!function_exists('add_custom_dashboard_widget_1')){
     function add_custom_dashboard_widget_1() {
         wp_add_dashboard_widget('オリジナルウィジェット', 'オリジナルウィジェット', function() { ?>
     オリジナルウィジェット
     <?php });
     }
 } 
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget_1' );
 ?>