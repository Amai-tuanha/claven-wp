<div class="mywpdb">
  <div class="inner">
    <a href="<?= get_admin_url() . '/?page=my_wpdb_page' ?>">テーブル一覧へ戻る</a>
    <br><br>

    <h1>検索フォーム</h1>
    <form action="<?= get_admin_url() ?>"
          method="GET">

      <input type="hidden"
             name="page"
             value="my_wpdb_page">
      <input type="hidden"
             name="search_result">
      <input type="text"
             value="<?= $s ?>"
             name="s">

      <button type="submit">送信</button>
    </form>
    <br>
    <?php

    // ---------- 検索 ----------
    if (isset($_GET["search_result"])) {
      include(plugin_dir_path(__FILE__) . "view-search.php");
    } else {
      // ---------- 各テーブル 1行 ----------
      if (
        !empty($_GET["where"]) &&
        isset($_GET["table"]) &&
        isset($_GET["page"])
      ) {
        include(plugin_dir_path(__FILE__) . "view-table-row.php");
      }
      // ---------- 各テーブル ----------
      elseif (
        isset($_GET["table"]) &&
        isset($_GET["page"])
      ) {
        include(plugin_dir_path(__FILE__) . "view-table.php");
      }
      // ---------- 初期画面 ----------
      elseif (isset($_GET["page"])) {
        include(plugin_dir_path(__FILE__) . "view-tables.php");
      }
    }



    // ---------- サイドバー ----------
    include(plugin_dir_path(__FILE__) . "view-sidebar.php");
    ?>
  </div>
</div>