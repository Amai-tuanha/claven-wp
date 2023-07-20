<?php

if (isset($_POST['mywpdb_delete'])) {
  $tableName = $_POST['table_name'];
  $table_key = $_POST['table_key'];
  $table_value = $_POST['table_value'];


  $deletes = $wpdb->delete($tableName, [$table_key => $table_value]);
  wp_safe_redirect(mywpdb_get_current_link() . "&form=delete&deletes=$deletes");
  exit;
}