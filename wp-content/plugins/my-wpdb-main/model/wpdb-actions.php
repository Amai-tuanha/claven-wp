<?php

$dir = plugin_dir_path(__FILE__) . "actions/";
$filelist = glob($dir . '*.php');
foreach ($filelist as $filepath) {

  $pieces = explode('/', $filepath);
  $count = count($pieces) - 1;
  if (
    strpos($filepath, '-copy') !== false ||
    $pieces[$count] == 'Dashboard.php'
  ) {
    continue;
  }
  include $filepath;
}