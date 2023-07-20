<?php
/*--------------------------------------------------
/* このクラスは現在(2022/01/31)使っていません
/* 使う可能性があるので残して置いてます
/*------------------------------------------------*/

/**
 * 投稿とユーザーの検索
 *
 * @param $
 */
class Rampup_search
{

  public function __construct($get_request = [])
  {
    global $reservation_post_status_array;
    $this->get_request = $get_request;
    $this->reservation_post_status_array = $reservation_post_status_array;
  }

  /**
   * ユーザーの検索結果をIDで出力
   *
   * @param $get_requestには$_GETを入れる
   * 対象のinputタグ = usermeta[user_contractDate][meta_value][]
   */
  function search_wp_usermeta($get_request = [])
  {
    $meta_query_args = [];

    if (!empty($get_request['usermeta'])) {

      foreach ($get_request['usermeta'] as $meta_key => $meta_value_array) {

        if (!empty($meta_value_array['meta_value'][0])) {
          $meta_query_args_child = [];
          if (count($meta_value_array['meta_value']) == 1) {
            $meta_value = $meta_value_array['meta_value'][0];
            $compare = 'LIKE';
          } else {
            $meta_value = $meta_value_array['meta_value'];
            $compare = 'IN';
          }

          $meta_query_args_child = [
            'key' => $meta_key,
            'value' => $meta_value,
            'compare' => $compare,
          ];
        }

        array_push($meta_query_args,  $meta_query_args_child);
      }

      // ---------- $_GETから配列を＄wp_user_query_argsに代入 ----------
      $wp_user_query_args = [
        'role_in' => 'subscriber',
        'meta_query' => $meta_query_args,
      ];

      // ---------- 配列が複数あったらrelation追加 ----------
      if (count($meta_query_args) > 1) {
        $meta_query_args['relation'] += 'AND';
      }

      $user_query = new WP_User_Query($wp_user_query_args);

      $user_query_id_array = [];
      foreach ($user_query->results as $user_search_result) {
        $user_query_id_array[] = $user_search_result->ID;
      }

      return $user_query_id_array;
    } else {
      return [];
    }
  }

  /**
   * wp_posts内の検索
   *
   * @param $
   */
  function search_wp_posts()
  {
  }

  /**
   * 面談日程の検索結果からユーザーのIDで出力
   *
   * @param $get_requestには$_GETを入れる
   * 対象のinputタグ = postmeta[reservation_date][meta_value][]
   */
  function search_wp_postmeta($get_request = [])
  {
    $meta_query_args = [];

    if (!empty($get_request['postmeta'])) {

      foreach ($get_request['postmeta'] as $meta_key => $meta_value_array) {
        if (!empty($meta_value_array['meta_value'][0])) {
          $meta_query_args_child = [];
          if (count($meta_value_array['meta_value']) == 1) {
            $meta_value = $meta_value_array['meta_value'][0];
            $compare = 'LIKE';
          } else {
            $meta_value = $meta_value_array['meta_value'];
            $compare = 'IN';
          }

          $meta_query_args_child = [
            'key' => $meta_key,
            'value' => $meta_value,
            'compare' => $compare,
          ];
        }

        array_push($meta_query_args,  $meta_query_args_child);
      }

      // ---------- $_GETから配列を＄wp_query_argsに代入 ----------
      $wp_query_args = [
        'post_type' => 'reservation',
        'posts_per_page' => -1,
        'post_status' => $this->reservation_post_status_array,
        'meta_query' => $meta_query_args,
      ];

      // ---------- 配列が複数あったらrelation追加 ----------
      if (count($meta_query_args) > 1) {
        $meta_query_args['relation'] += 'AND';
      }

      $wp_query = new WP_Query($wp_query_args);

      $wp_query_id_array = [];
      foreach ($wp_query->posts as $wp_search_result) {
        $user_id = get_post_meta($wp_search_result->ID, 'reservation_user_id', true);
        $wp_query_id_array[] = (int)$user_id;
      }

      return $wp_query_id_array;
    } else {
      return [];
    }
  }

  /**
   * この関数用にカスタマイズしたarray_intersect
   *
   * @param $array 複数の配列
   */
  function sq_array_intersect(...$array)
  {

    if ($array) {
      $search_result_id_array = array_intersect(...$array);
      return array_values($search_result_id_array);
    } else {
      return false;
    }
  }

  function convert_user_id_to_post_id($user_id_array)
  {
    global $wpdb;
    $post_id_array = [];
    foreach ($user_id_array as $user_id) {
      # code...
      $post_id = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = 'reservation_user_id' AND `meta_value` = '$user_id' ")[0]->post_id;
      $post_id_array[] = $post_id;
    }

    return $post_id_array;
  }

  function sq_name_attribute($type, $meta_key)
  {
    $name_attribute = $type . '[' . $meta_key . '][meta_value][]';
    return $name_attribute;
  }
}


// /**
//  * 面談日程に関する関数をまとめたもの
//  *
//  * @param $
//  */
// class Rampup_Users
// {

//   function user_count($args_added = [])
//   {
//     $args_initial = [
//       'role' => 'Subscriber',
//     ];
//     $args = array_merge($args_added, $args_initial);
//     $wp_user_query = new WP_User_Query($args);
//     $wp_user_query_result = $wp_user_query->get_results();
//     $user_count = count($wp_user_query_result);
//     return $user_count;
//   }
// }
// ---------- ↓Rampup_searchの使用例↓ ----------
// $rampup_search = new Rampup_search();
// $user_query_id_array = $rampup_search->user_query_id_array($_GET);
// $wp_query_id_array = $rampup_search->wp_query_id_array($_GET);
// $search_result_user_id_array = array_intersect($user_query_id_array, $wp_query_id_array);