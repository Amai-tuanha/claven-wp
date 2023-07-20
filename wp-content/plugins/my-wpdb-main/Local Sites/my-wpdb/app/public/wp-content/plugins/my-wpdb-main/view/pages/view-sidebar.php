<div class="mywpdbSidebar">
  <form class="mywpdb__form"
        action="<?= get_admin_url() ?>"
        method="GET">
    <input type="hidden"
           name="page"
           value="<?= $_GET["page"] ?>">
    <?php
    foreach ($allTables_array as $tableNames) {
      foreach ($tableNames as $tableName) {
        $tableName_view = str_replace($wpdb->prefix, "", $tableName);
    ?>

    <button class="mywpdb__formButton <?php echo ($_GET["table"] === $tableName) ? '-current' : ''; ?>"
            type="submit"
            name="table"
            value="<?= $tableName ?>">wp_<?= $tableName_view ?></button>
    <?php } ?>
    <?php } ?>
  </form>
</div>