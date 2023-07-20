<?php

/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
include_once get_theme_file_path() . "/App/Mypage/model/create-page.php";
include(get_theme_file_path() . "/App/Mypage/model/mypage-variables.php");
// $dir = dirname(__FILE__) . '/';
// $file_list = glob($dir . '*.php');
// foreach ($file_list as $file_path) {
//   if (basename($file_path) == basename(__FILE__)) {
//     continue;
//   }
//   include $file_path;
// }


// ---------- 特定の倍数に繰り上げる ----------
 if (!function_exists('roundUpToAny')){
     function roundUpToAny($n, $x = 4)
     {
         return round(($n + $x / 2) / $x) * $x;
     }
}

// ---------- 分を「〜時間〜分に」変換 ----------
 if (!function_exists('convertToHoursMins')){
     function convertToHoursMins($time, $format = '%02d:%02d')
     {
         // if ($time < 1) {
         //     return;
         // }
         $hours = floor($time / 60);
         $minutes = ($time % 60);
         return sprintf($format, $hours, $minutes);
         // 使用例
         // echo convertToHoursMins(250, '%02d hours %02d minutes');
     }
}