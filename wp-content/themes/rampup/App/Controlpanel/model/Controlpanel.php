<?php

/*--------------------------------------------------
/* インクルード
/*------------------------------------------------*/
include_once get_theme_file_path() . "/App/Controlpanel/model/create-page.php";
include(get_theme_file_path() . "/App/Controlpanel/model/controlpanel-variables.php");
// ---------- App/Login/model/Login.phpの下にあるファイルを全てインクルード ----------
// $dir = dirname(__FILE__) . '/';
// $file_list = glob($dir . '*.php');
// foreach ($file_list as $file_path) {
//   if (basename($file_path) == basename(__FILE__)) {
//     continue;
//   }
//   include $file_path;
// }

// ---------- /Controlpanel/model/setupの下にあるファイルを全てインクルード ----------
// $dir = get_theme_file_path() . '/App/Controlpanel/model/';
// $filelist = glob($dir . '*.php');
// foreach ($filelist as $filepath) {
//   $pieces = explode('/', $filepath);
//   $count = count($pieces) - 1;
//   if (
//     strpos($filepath, '-copy') !== false ||
//     $pieces[$count] == 'Controlpanel.php'
//   ) {
//     continue;
//   }
//   include $filepath;
// }
// echo '<pre>';
// var_dump($file_list);
// echo '</pre>';

?>