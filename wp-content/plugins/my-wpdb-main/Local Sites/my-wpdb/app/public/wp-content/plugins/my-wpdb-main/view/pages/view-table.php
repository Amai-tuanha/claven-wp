<?php
// ---------- テーブル ----------
$tableName = $_GET["table"];
$table_cols = $wpdb->get_col("DESC {$tableName}", 0);
$table = $wpdb->get_results("SELECT * FROM $tableName LIMIT $limit OFFSET $offset", ARRAY_A);

// ---------- カウント ----------
$row_count = count($wpdb->get_results("SELECT * FROM $tableName", ARRAY_A));
$page_count = $row_count / $limit;
?>

<h1><?= mywpdb_breadcrumb() ?></h1>
<table class="mywpdbTable">
  <tr class="mywpdbTable__tr">
    <th class="mywpdbTable__head">修正</th>

    <?php foreach ($table_cols as $table_colName) { ?>
    <th class="mywpdbTable__head"><?= $table_colName ?></th>
    <?php } ?>
  </tr>

  <?php foreach ($table as $table_values) {
    $first_key = array_key_first($table_values);
    $first_value = $table_values[$first_key];
  ?>
  <tr class="mywpdbTable__tr">
    <td class="mywpdbTable__desc">
      <div class="mywpdbTable__flex">

        <form action="<?= get_admin_url() ?>"
              method="GET">
          <?= GETS() ?>

          <button type="submit"
                  name="where[<?= $first_key ?>]"
                  value="<?= $first_value ?>"><?= "編集" ?></button>
        </form>
      </div>
    </td>
    <?php foreach ($table_values as $table_value) {
        $table_value = strip_tags($table_value);
        $table_value = mb_substr($table_value, 0, 100);
      ?>
    <td class="mywpdbTable__desc"><?= $table_value ?></td>
    <?php } ?>
  </tr>
  <?php } ?>
</table>

<?php mywpdb_pagination($page_count) ?>