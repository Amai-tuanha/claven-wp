<?php
define('SAVEQUERIES', true);

global $wpdb;

$s = $_GET['s'];


// ---------- 全テーブル一覧 ----------
$sql = "SHOW TABLES LIKE '%'";
$allTables_array = $wpdb->get_results($sql);

// ---------- リミット ----------
$limit = 25;
// ---------- ページ ----------
if (isset($_GET['page_num'])) {
  $page_num = $_GET['page_num'];
} else {
  $page_num = 1;
}
// ---------- オフセット ----------
if (isset($_GET['page'])) {
  $offset = ($page_num * $limit) - $limit;
} else {
  $offset = 0;
}

/**
 * 現在の$_GETを全て取得しinput[type="hidden"]に変換
 *
 * @param $exclude 除外するアイテムのキー
 */
function GETS($exclude = null)
{
  foreach ($_GET as $key => $value) {
    if ($key == $exclude) {
      continue;
    }
?>
<input type="hidden"
       name="<?= $key ?>"
       value="<?= $value ?>">
<?php  }
}

/**
 * パンクズりすと
 *
 * @param $
 */
function mywpdb_breadcrumb()
{
  ?>
<div class="mywpdb_breadcrumb">
  <a href="<?= admin_url() . "?page=my_wpdb_page" ?>">テーブル一覧</a>

  <?php if (isset($_GET['table'])) { ?>
  > <a href="<?= admin_url() . "?page=my_wpdb_page&table=" . $_GET['table'] ?>"><?= $_GET['table'] ?></a>
  <?php } ?>

  <?php if (isset($_GET['where'])) {
      $first_key = array_key_first($_GET['where']);
      $first_value = $_GET['where'][$first_key];
    ?>
  > <a href="<?= admin_url() . "?page=my_wpdb_page&table=" . $_GET['table'] ?>">
    <?= "where $first_key = $first_value" ?>
  </a>

  <?php } ?>
</div>

<?php
}

/**
 * 現在のリンクを取得
 */
function mywpdb_get_current_link()
{
  return (is_ssl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


/**
 * リダイレクト関数がheaderの下でも動くように
 */
add_action('init', 'mywpdb_do_output_buffer');
function mywpdb_do_output_buffer()
{
  ob_start();
}

/**
 * ページネーション
 *
 * @param $page_count ページの数
 */
function mywpdb_pagination($page_count)
{
  global $limit;
  $offset = 0;
  $pages = $_GET['page'];
  $last = (int)$page_count + 1;
  $page_num = (int)$_GET['page_num'];
  $prev = (!isset($page_num) || (int)$page_num == 2) ? 0 : (int)$page_num - 1;
  $next = (!isset($page_num) || (int)$page_num == 2) ? 0 : (int)$page_num + 1;
?>
<form class="mywpdbPagination"
      mehtod="GET"
      action="<?= mywpdb_get_current_link() ?>">
  <?php GETS() ?>

  <select class="mywpdbPagination__select"
          name="page_num">
    <?php for ($l = 1; $l < $last; $l++) { ?>
    <option <?php echo ($l) == $page_num ? 'selected' : ''; ?>
            value="<?= $l ?>"><?= $l ?></option>
    <?php } ?>
  </select>
  <script>
  (function($) {
    $('.mywpdbPagination__select').on('change', function() {
      $('.mywpdbPagination').submit()
    })
  })(jQuery);
  </script>

  <ul class="mywpdbPagination__list">
    <li class="mywpdbPagination__listChild">

      < </li>
        <?php
          $b = 0;
          for ($i = 1; $i < $last; $i++) {
            if (
              $i == 1 ||
              $i == 2 ||
              $i == $last - 1 ||
              $i == $last - 2 ||
              $i == $page_num ||
              $i == $page_num + 1 ||
              $i == $page_num - 1
            ) {
          ?>
    <li class="mywpdbPagination__listChild <?php echo ($page_num == $i) ? '-current' : ''; ?>">
      <button name="page_num"
              value="<?= $i ?>"><?= $i ?></button>
    </li>
    <?php } else {
              $b++;
              if ($b == 4) {
                echo '<li class="mywpdbPagination__listChild">...</li>';
              }
              continue;
            }
          }
  ?>

    <li class="mywpdbPagination__listChild">
      >
    </li>
  </ul>
</form>
<?php
}