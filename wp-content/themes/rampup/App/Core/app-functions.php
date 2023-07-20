<?php

use Carbon\Carbon;

global $wpdb;

/*--------------------------------------------------
/* 子ファイルの大元をインクルード
/*------------------------------------------------*/
$dir = get_theme_file_path() . '/App-child/functions-to-overwrite/';
$filelist = glob($dir . '*.php');
foreach ($filelist as $filepath) {
  include $filepath;
}

/*--------------------------------------------------
/* 開発側の全共通の関数を定義するファイルです
/*------------------------------------------------*/

/**
 * 変数が「$var="";」と「$var=NULL;」と「$var=array()」の場合trueで返す。（はいれつ空の判定をする関数）
 *
 * @param $obj 変数名
 */
if (!function_exists('is_nullorempty')) {
  /**
   * validate string. null is true, "" is true, 0 and "0" is false, " " is false.
   */
  function is_nullorempty($obj)
  {
    if ($obj === 0 || $obj === "0") {
      return false;
    }

    return empty($obj);
  }
}


function dump(...$texts)
{
  foreach ($texts as $text) {
    echo '<pre>';
    var_dump($text);
    echo '</pre>';
  }
}
/**
 * CSSファイルを呼び出す
 *
 * @param $css_file_name cssのファイル名
 */
if (!function_exists('rampup_css_path')) {
  function rampup_css_path($css_file_name)
  {
    $css_file_uri = get_template_directory_uri() . '/assets/css/' . $css_file_name;
    return $css_file_uri;
  }
}
/**
 * Videoファイルを呼び出す
 *
 * @param $video_file_name videoのファイル名
 */
if (!function_exists('rampup_video_path')) {
  function rampup_video_path($video_file_name)
  {
    $video_file_uri = get_template_directory_uri() . '/assets/video/' . $video_file_name;
    return $video_file_uri;
  }
}


/**
 * ファイル名からパスを取得する関数
 * ※複数ある場合は細かくディレクトリ名までファイル名を指定する。
 *
 * @param $file_name ファイル名
 * @param $relation 相対パスかどうか
 */
if (!function_exists('rampup_get_file_path')) {
  function rampup_get_file_path($file_name, $relation = true)
  {
    $list_files = list_files(get_template_directory());
    $match_list_files = [];
    foreach ($list_files as $list_file) {
      if (strpos($list_file, $file_name) !== false) {
        array_push($match_list_files, $list_file);
        $match_list = $list_file;
      }
    }
    if ($relation === true) {
      $replace = str_replace(get_template_directory(), '', $match_list);
      $match_list = $replace;
    }

    $count = count($match_list_files);
    if ($count === 1 || $count === 0) {
      return $match_list;
    } else {
      return $match_list_files;
    }
  }
}

/**
 * 購読者一覧
 *
 * @param $args_added 追加のargs
 */
if (!function_exists('user_subscribers_array')) {
  function user_subscribers_array($args_added = [])
  {
    $args_origin = [
      'role' => 'subscriber', //購読者
    ];

    $args = array_merge($args_origin, $args_added);
    return get_users($args);
  }
  $user_subscribers_array = user_subscribers_array();
}


/**
 * 管理者一覧
 *
 * @param $args_added 追加のargs
 */
if (!function_exists('user_administrators_array')) {
  function user_administrators_array($args_added = [])
  {
    $clane_admin_id = get_user_by('email', 'info@clane.co.jp')->ID;
    $args_origin = [
      'role' => 'administrator', //管理者
      'exclude' => $clane_admin_id, //claneのアカウントを除外
    ];

    $args = array_merge($args_origin, $args_added);
    return get_users($args);
  }
}
$user_administrators_array = user_administrators_array();


/**
 * carbonで出力した値をフォーマット化する
 *
 * @param $date 時間の値 例：2022/01/31
 * @param $hours 「Y/m/d H:i:s」の内 「H:i:s」を編集できる デフォルト値は「H:i」
 * @param $year 「Y/m/d H:i:s」の内 「Y」を編集できる デフォルト値はなし
 */
if (!function_exists('carbon_formatting')) {
  function carbon_formatting($date, $hours = "H:i", $year = "", $time = false)
  {
    global $weekday_jap;
    $carbon_formatting_date = new Carbon($date);
    $carbon_formatting_weekday = $weekday_jap[$carbon_formatting_date->dayOfWeek];
    if ($time) {
      $carbon_formated_data = $carbon_formatting_date->copy()->format($year . "n月j日($carbon_formatting_weekday)");
    } else {
      $carbon_formated_data = $carbon_formatting_date->copy()->format($year . "n月j日($carbon_formatting_weekday) $hours");
    }
    return $carbon_formated_data;
  }
}

// ---------- スラッグから投稿ID取得 ----------
if (!function_exists('slug_to_post_id')) {
  function slug_to_post_id($slug, $post_type = 'email')
  {
    return get_page_by_path($slug, "OBJECT", $post_type)->ID;
  }
}

// ---------- ランダムな数を出力 ----------
if (!function_exists('random')) {
  function random($length = 16)
  {
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
  }
  $user_random_pass = random();
}

// ---------- 2語で分割する場合 ----------
if (!function_exists('double_explode')) {
  function double_explode($word1, $word2, $str)
  {
    $return = [];
    $array = explode($word1, $str);
    foreach ($array as $value) {
      $return = array_merge($return, explode($word2, $value));
    }

    return $return;
  }
}

// ---------- 現在のURLをパラメータ付きで出力する ----------
if (!function_exists('get_current_link')) {
  function get_current_link()
  {
    return (is_ssl() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }
}

// ---------- user DB tableの列数をカウント ----------
$wpUsers_DB_rows = $wpdb->get_results('SELECT * FROM `wp_users`');
$wpUsers_DB_rowCount = count($wpUsers_DB_rows) + 1;

// ---------- メールアドレス系 ----------
$clane_email1 = 'info@clane.co.jp';
// $clane_email2 = 'h.mitsuya@clane.co.jp';
// $clane_email3 = 'tsurumi@clane.co.jp';
$user_randomNumber = substr(mt_rand(10000, 19999), 1, 4);
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
// var_dump('intern-'.$rowcount);

// ---------- 特定のスラッグを持つ投稿がデータベース上に存在する場合の関数 ----------
if (!function_exists('the_slug_exists')) {
  function the_slug_exists($post_name)
  {
    global $wpdb;
    if ($wpdb->get_row(
      "SELECT post_name FROM wp_posts
             WHERE post_name = '" . $post_name . "'",
      'ARRAY_A'
    )) {
      return true;
    } else {
      return false;
    }
  }
}

/**
 * 特定のタイトルの投稿が存在するかの条件分岐関数
 *
 * @param $post_title 投稿タイトル
 */
if (!function_exists('the_post_title_exists')) {
  function the_post_title_exists($post_title)
  {
    global $wpdb;
    if ($wpdb->get_row("SELECT post_title FROM wp_posts WHERE post_type = 'reservation' AND post_title = '" . $post_title . "'", 'ARRAY_A')) {
      return true;
    } else {
      return false;
    }
  }
}

// ---------- ユーザーが存在するかどうが調べる関数 ----------
if (!function_exists('user_exists')) {
  function user_exists($field, $user)
  {
    // $field = wp_usersのcolumn名 e.g. user_login, ID, user_emailなど
    // $user = 値
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE $field =  %s", $user));
    if ($count == 1) {
      return true;
    } else {
      return false;
    }
  }
}

// ---------- 現在時刻から一番近い次回面談日を出力 ----------
if (!function_exists('find_closest_reservation_date')) {
  function find_closest_reservation_date($user_id, $format = 'true')
  {
    global $wpdb;
    // ---------- 現在時刻 ----------
    $now = new Carbon('now');
    // ---------- user_idとマッチする投稿idの一覧を取得 ----------
    $reservation_post_array = $wpdb->get_results('SELECT post_id FROM `' . $wpdb->prefix . 'postmeta`  WHERE meta_key = "reservation_user_id" AND meta_value =' . '"' . $user_id . '"');
    $reservation_date_array = [];
    $reservation_date_object = [];
    $reservation_object = [];
    foreach ($reservation_post_array as $reservation_post) {
      $reservation_post_id = $reservation_post->post_id;
      $reservation_post_status = get_post_status($reservation_post_id);
      $reservation_date = get_post_meta($reservation_post_id, 'reservation_date', true);
      $reservation_date_carbon = new Carbon($reservation_date);
      if (
        $reservation_date
        && $now < $reservation_date_carbon
        && $reservation_post_status == 'scheduled'
      ) {
        array_push($reservation_date_array, $reservation_date);
        $reservation_date_object[$reservation_date] = $reservation_post_id;
        $reservation_object[$reservation_post_id] = $reservation_date;
      }
    }

    if ($reservation_date_array) {
      foreach ($reservation_date_array as $reservation_date) {
        $interval[] = abs(strtotime($now) - strtotime($reservation_date));
      }
      asort($interval);
      $closest = key($interval);

      if ($format == 'true') {
        return $reservation_date_array[$closest];
      } elseif ($format == 'array') {
        return $reservation_date_array;
      } elseif ($format == 'id') {
        return $reservation_date_object[$reservation_date_array[$closest]];
      } elseif ($format == 'object') {
        return $reservation_object;
      }
    } else {
      return false;
    }
  }
}

// ---------- 面談日程の投稿作成 ----------
if (!function_exists('insert_reservation_post')) {
  function insert_reservation_post(
    $user_displayName,
    $post_title,
    $reservation_user_id,
    $reservation_date,
    $reservation_type = null,
    $reservation_reminder_emails_preset = null
  ) {
    global $site_numberOfCalendarCell;

    $today = Carbon::today()->format('Y-m-d 9:00:00');
    $reservation_date_carbon = new carbon($reservation_date);
    $reservation_date_diff = $reservation_date_carbon->diffInDays($today);

    // ---------- 投稿作成 ----------
    $reservationPost_args = array(
      "post_type" => "reservation",
      "post_title" => $user_displayName . "様の" . $post_title,
      "post_content" => "",
      "post_status" => "scheduled",
    );
    $reservation_post_id = wp_insert_post($reservationPost_args);

    // ---------- 投稿メタデータ配列 ----------
    $reservation_post_meta_array = [
      "reservation_user_id" => $reservation_user_id,
      "reservation_date" => $reservation_date,
      "reservation_type" => $reservation_type,
      "reservation_reminder_emails_preset" => $reservation_reminder_emails_preset,
      "reservation_duration" => $site_numberOfCalendarCell,
    ];

    // ---------- 二日前に予約していたらリマインダーが飛ばないようにする ----------
    if (
      $reservation_date_diff == 2 &&
      $reservation_reminder_emails_preset == 'before_contract'
    ) {
      $reservation_post_meta_array['reminder_before_contract_2day'] = 'sent';
    }

    // ---------- メタ情報登録実行 ----------
    foreach ($reservation_post_meta_array as $reservation_post_meta_key => $reservation_post_meta_value) {
      update_post_meta($reservation_post_id, $reservation_post_meta_key, $reservation_post_meta_value);
    }

    return $reservation_post_id;
  }
}

/**
 * 投稿を削除するリンクを取得する（管理者用）
 *
 * @param $post_id 該当の投稿ID
 */
if (!function_exists('delete_post_link')) {
  function delete_post_link($post_id)
  {
    $post = get_post($post_id);
    $post_type = get_post_type($post_id);
    $delLink = wp_nonce_url(admin_url() . "post.php", "post=" . $post_id . "&action=delete");
    return $delLink;
  }
}


/**
 * カスタム投稿「reservation」のリマインダーのメタバリューを全て削除する
 * これを実行することでリマインダーが再度送信されるようになる。
 * 面談日程を変更した時などに使う
 *
 * @param $post_id 該当の投稿ID
 */
if (!function_exists('reset_reminder')) {
  function reset_reminder($post_id)
  {
    delete_post_meta($post_id, 'reminder_before_contract_2day');
    delete_post_meta($post_id, 'reminder_before_contract_dday');
    delete_post_meta($post_id, 'reminder_after_contract_1day');
  }
}

// ---------- 開発者用の条件分岐関数 ----------
if (!function_exists('is_developer')) {
  function is_developer($login_email = 'info@clane.co.jp')
  {
    $user_id = get_current_user_id();
    $user_info_array = get_user_by('ID', $user_id);
    $user_email = $user_info_array->user_email;

    if ($user_email == $login_email) {
      return true;
    } else {
      return false;
    }
  }
}

// ---------- 検証用関数 ----------
if (!function_exists('ovd')) {
  function ovd($var_dump)
  {
    ob_start();
    var_dump($var_dump);
    $dump = ob_get_contents();
    ob_end_clean();
    file_put_contents(get_template_directory() . '/App/dump.php', $dump, FILE_APPEND);
  }
}


/**
 * 特定の投稿タイプが存在しているか
 *
 * @param $post_type 投稿タイプ
 */
if (!function_exists('the_post_type_exists')) {
  function the_post_type_exists($post_type)
  {
    global $wpdb;
    $return = $wpdb->get_results("SELECT ID FROM $wpdb->prefix" . "posts WHERE post_type = '" . $post_type . "'");
    if (empty($return)) {
      return false;
    } else {
      return true;
    }
  }
}


/**
 * 現在の$_GETを全て取得しinput[type="hidden"]に変換
 *
 * @param $exclude 除外するアイテムのキー
 */
if (!function_exists('rampup_GETS')) {
  function rampup_GETS($exclude = [])
  {
    foreach ($_GET as $key => $value) {
      if (in_array($key, $exclude)) {
        continue;
      }
?>
       <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
 <?php  
}
  }
}


// ---------- ユーザーのIDとメールアドレスを照合する ----------
if (!function_exists('Rampup_User_match')) {
  function Rampup_User_match($user_id, $user_login)
  {
    $class_user = new Rampup_User($user_id);
    if ($user_login == $class_user->user_login) {
      return true;
    } else {
      return false;
    }
  }
}

// ---------- 数字にコンマをつける ----------
if (!function_exists('num')) {
  function num($number)
  {
    return number_format($number);
  }
}

/**
 * 投稿一覧から「非公開」を除外する
 *
 * @param $where
 */
if (!function_exists('no_privates')) {
  function no_privates($where)
  {
    if (is_admin()) return $where;

    global $wpdb;
    return " $where AND {$wpdb->posts}.post_status != 'private' ";
  }
  add_filter('posts_where', 'no_privates');
}
/**
 * ステータス変更に応じたメール送信
 *
 * @param $user_email
 *        $user_status
 *        $user_paymentMethod
 *        $google_meet
 */
if (!function_exists('user_update_email_to_send')) {
  function user_update_email_to_send(
    $user_email,
    $user_status,
    $user_paymentMethod,
    $google_meet
    // $user_numberOfDivision,
  ) {
    // ---------- 決済リクエスト ----------
    if ($user_status === 'application') {
      // ---------- カード決済 リクエスト ----------
      if ($user_paymentMethod === 'card') {

        $emailPost_id = slug_to_post_id("email-payment-card-request");
        my_PHPmailer_function(
          do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
          do_shortcode(get_post_field('post_content', $emailPost_id)),
          $user_email,
          $google_meet,
          null
        );
      }
      // ---------- 銀行振り込み リクエスト ----------
      elseif ($user_paymentMethod === 'bank') {
        $emailPost_id = slug_to_post_id("email-payment-bank-request");
        my_PHPmailer_function(
          do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
          do_shortcode(get_post_field('post_content', $emailPost_id)),
          $user_email,
          $google_meet,
          null
        );
      }
    } elseif ($user_status === 'paid') {
      // ---------- カード決済 完了 ----------
      if ($user_paymentMethod === 'card') {
        // stripeのhookで自動でメール送信
        // メール文はEmail.phpに記載
      }
      // ---------- 銀行振り込み 完了 ----------
      elseif ($user_paymentMethod === 'bank') {
        $emailPost_id = slug_to_post_id("email-payment-bank-complete");
        my_PHPmailer_function(
          do_shortcode(get_post_meta($emailPost_id, "email_subject", true)),
          do_shortcode(get_post_field('post_content', $emailPost_id)),
          $user_email,
          $google_meet,
          null
        );
      }
    }
  }
}


/**
 * info@clane.co.jp以外の管理者のメールアドレスを取得する関数
 */
if (!function_exists('administrator_emails_array')) {
  function administrator_emails_array()
  {
    $clane_admin_id = get_user_by('email', 'info@clane.co.jp')->ID;
    $user_administrators_array = get_users([
      'role' => 'administrator', //管理者
      'exclude' => $clane_admin_id, //claneのアカウントを除外
    ]);

    $administrator_emails_array = [];
    foreach ($user_administrators_array as $user_administrator) {
      array_push($administrator_emails_array, $user_administrator->user_email);
    }
    return $administrator_emails_array;
  }
  $administrator_emails_array = administrator_emails_array();
}


/**
 * ユーザー一覧をメタデータを指定して配列で出力
 *
 * @param $meta_key ユーザーメタ
 * @param $meta_value ユーザーメタの値
 */
if (!function_exists('rampup_get_user_by_meta')) {
  function rampup_get_user_by_meta($meta_key, $meta_value)
  {
    $array = get_users(
      [
        'role' => 'subscriber',
        'meta_key' => $meta_key,
        'meta_value' => $meta_value,
      ]
    );
    return $array;
  }
}

/**
 * 特定のファイルで定義した全てのショートコードを一覧で出力
 *
 * @param $fileName ファイル名
 */
if (!function_exists('get_list_of_shortcodes')) {
  function get_list_of_shortcodes($fileName)
  {

    // Get the array of all the shortcodes
    global $shortcode_tags;
    $shortcodes = $shortcode_tags;
    $shortcodes_array = [];

    foreach ($shortcodes as $shortcode => $value) {
      if (function_exists($shortcode)) {

        $reflectionFunction = new ReflectionFunction($shortcode);
        $shortcode_filePath = $reflectionFunction->getFileName();
        $shortcode_filePath_pieces = explode("/", $shortcode_filePath);
        $shortcode_fileName = $shortcode_filePath_pieces[count($shortcode_filePath_pieces) - 1];
        if ($shortcode_fileName == $fileName) {
          array_push($shortcodes_array, $shortcode);
          // $shortcodes_array += $shortcode_fileName;
        }
      }
    }
    return $shortcodes_array;
  }
}

/**
 *  フックに何の関数がかかっているか知るための関数
 *
 * @param $hook フックの名前
 */
if (!function_exists('print_filters_for')) {
  function print_filters_for($hook = '')
  {
    global $wp_filter;
    if (empty($hook) || !isset($wp_filter[$hook])) return;

    $ret = '';
    foreach ($wp_filter[$hook] as $priority => $realhook) {
      foreach ($realhook as $hook_k => $hook_v) {
        $hook_echo = (is_array($hook_v['function']) ? get_class($hook_v['function'][0]) . ':' . $hook_v['function'][1] : $hook_v['function']);
        $ret .=  "\n$priority $hook_echo";
      }
    }
    return $ret;
  }
}

/**
 *  emailをチェックする
 *
 * @param $email 検索文字　$search_array 配列
 */
if (!function_exists('email_check_varidation')) {
  function email_check_varidation($email, $search_array)
  {
    $check_array = [];
    foreach ($search_array as $search) {
      if (strpos($email, $search) !== false) {
        array_push($check_array, 'ok');
      }
    }
    $check = in_array('ok', $check_array);
    $result = $check === false;
    return $result;
  }
}


/**
 *  ページ生成用関数
 *
 * @param string $slug  スラッグ名
 * @param string $title  タイトル名
 * @param string $relation_file_path　読み込むページファイルの相対パス
 * @param string $subject　メール文の件名
 * @param string $content　メール文の内容
 * @param string $type　固定ページ（page）or メール（email）
 */
if (!function_exists('rampup_create_page')) {
  function rampup_create_page($slug, $title, $page_file_name = "", $subject = "", $content = "", $type = "page")
  {
    if (!the_slug_exists($slug)) {
      if ($type === "page") {
        $relation_file_path = rampup_get_file_path($page_file_name);
      }
      if (is_array($relation_file_path)) {
        $return_text = dump('ERROR: page_file_name are multiple exist.', $relation_file_path);
        return $return_text;
        exit;
      }

      $post_array = array(
        "post_type"      => $type,
        "post_name"      => $slug,
        "post_title"     => $title,
        "post_content"   => $content,
        "post_status"    => "publish",
        "post_author"    => 1,
        "post_parent"    => 0,
        "comment_status" => "closed"
      );
      $inserted_post_id = wp_insert_post($post_array);


      if ($inserted_post_id && $type === "page") {
        // ---------- メタ情報追加 ----------
        update_post_meta($inserted_post_id, "_wp_page_template", $relation_file_path);
        update_post_meta($inserted_post_id, "rampup_default_page", 'true');
      }

      if ($inserted_post_id && $type === "email") {
        // ---------- カテゴリ追加 ----------
        // wp_set_object_terms($email_post_id, "email_beforestart", "email_taxonomy", false);
        // ---------- メタ情報追加 ----------
        update_post_meta($inserted_post_id, 'email_subject', $subject);
      }
    }
  }
}

/**
 * 該当する投稿に関する全ての情報を削除する
 *
 * @param int $post_id 投稿ID
 * @param bool $kill 真偽値（trueの場合：投稿の完全削除、falseの場合：ゴミ箱）
 */

function rampup_delete_post_all_info($post_id, $kill = true)
{
  $meta_values_array = get_post_meta($post_id,'', true);
  // foreach ($meta_values_array as $key) {
  //   delete_post_meta($post_id, $key);
  // }
  wp_delete_post($post_id, $kill);
}
