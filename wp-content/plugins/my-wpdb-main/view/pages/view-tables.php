<h1>テーブル一覧</h1>

<form class="mywpdb__form"
      action="<?= get_admin_url() ?>"
      method="GET">
  <input type="hidden"
         name="page"
         value="<?= $_GET["page"] ?>">

  <table class="mywpdbTable">
    <?php
    foreach ($allTables_array as $tableNames) { ?>
    <tr class="mywpdbTable__tr">
      <td class="mywpdbTable__head">
        <?php
          foreach ($tableNames as $tableName) {
          ?>
        <button class="mywpdb__formButton"
                type="submit"
                name="table"
                value="<?= $tableName ?>"><?= $tableName ?></button>
        <?php } ?>
      </td>
    </tr>
    <?php } ?>
  </table>
</form>