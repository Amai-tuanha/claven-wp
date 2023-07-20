<?php


/**
 * Errorアラートを表示する関数
 *
 * @param [String] $content
 * @return script
 */
 if (!function_exists('error_alert')){
     function error_alert($content)
     {
         $error_alert = "<script type='text/javascript'>alert('$content');</script>";
         echo $error_alert;
     }
}
