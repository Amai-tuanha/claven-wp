<?php
$tableName = $_GET["table"];
$table_cols = $wpdb->get_col("DESC {$tableName}", 0);
$where_key = array_key_first($_GET['where']);
$table_row = $wpdb->get_results('SELECT * FROM ' . $tableName . ' WHERE ' .  $where_key . ' = '  . '"' . $_GET['where'][$where_key] . '" ', ARRAY_A);

?>

<h1><?= mywpdb_breadcrumb() ?></h1>
<br>

<form action="<?= mywpdb_get_current_link() ?>" method="POST">

  <table class="mywpdbTable -w100">
    <?php foreach ($table_row[0] as $table_key => $table_value) {

      $letter_count = mb_strlen($table_value);

    ?>
      <tr class="mywpdbTable__tr">
        <th class="mywpdbTable__head -tal"><?= $table_key ?></th>
        <td class="mywpdbTable__desc -w100">

          <?php if ($letter_count > 200) { ?>
            <textarea name="<?= $table_key ?>" id="" cols="30" rows="10"><?= $table_value ?></textarea>
          <?php } else { ?>
            <input type="text" name="<?= $table_key ?>" value="<?= $table_value ?>">
          <?php } ?>
        </td>
      </tr>
    <?php } ?>
  </table>

  <br>
  <button class="-btn" name="mywpdbUpdateTrigger">変更する</button>
</form>
