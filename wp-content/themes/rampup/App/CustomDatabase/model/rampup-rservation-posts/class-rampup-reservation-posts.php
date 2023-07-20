<?php

/*--------------------------------------------------
/* wp_rampup_reservation_postsのテーブルから値を取得するクラス
/*------------------------------------------------*/
class Reservation_posts
{
  public function __construct()
  {
    global $wpdb;
    $this->table_name = $wpdb->prefix . 'rampup_resevation_posts';
  }
  public $table_name = 'hello ' . 'rampup_resevation_posts';

  /*--------------------------------------------------
  /* post_idからuser_idを取得する
  /*------------------------------------------------*/
  public function get_reservation_user_id($post_id)
  {
    global $wpdb;
    $reservation_posts_row = $wpdb->get_row("SELECT * FROM `{$this->table_name}` WHERE post_id = '$post_id'");
    $reservation_user_id = $reservation_posts_row->post_id;
    return $reservation_user_id;
  }

  /*--------------------------------------------------
  /* user_idからpost_idを配列で取得する
  /*------------------------------------------------*/
  public function get_reservation_post_id($user_id)
  {
    global $wpdb;
    $reservation_post_id_array = [];
    $results = $wpdb->get_results("SELECT post_id FROM `{$this->table_name}` WHERE user_id = '$user_id'", ARRAY_N);
    foreach ($results as $result) {
      array_push($reservation_post_id_array, $result[0]);
    }
    return $reservation_post_id_array;
  }
}
// $rampup_reservation_posts = new Reservation_posts();
// $reservation_user_id  = $rampup_reservation_posts->get_reservation_user_id($post_id);
// $reservation_post_id_array = $rampup_reservation_posts->get_reservation_post_id($user_id);