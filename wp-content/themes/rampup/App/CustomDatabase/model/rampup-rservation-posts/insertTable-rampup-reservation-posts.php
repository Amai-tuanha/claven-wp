<?php


// /*--------------------------------------------------
//     /* データベースを生成
//     /*------------------------------------------------*/
// if (!function_exists('rampup_create_cutom_db_table')) {
//   function rampup_create_cutom_db_table()
//   {
//     global $wpdb;
//     // ---------- テーブル名 ----------
//     $table = $wpdb->prefix . 'rampup_resevation_posts';

//     $charset_collate = $wpdb->get_charset_collate();
//     // ---------- クエリ ----------
//     $query =  "CREATE TABLE IF NOT EXISTS  " . $table . " (
//                       ID bigint(22) AUTO_INCREMENT,
//                       post_id bigint(22),
//                       post_status varchar(20),
//                       reservation_date varchar(100),
//                       user_id bigint(22),
//                       user_email varchar(100),
//                       PRIMARY KEY(ID)
//                       )$charset_collate;";

//     // ---------- 実行 ----------
//     return $wpdb->query($query);
//   }
// }

// /*--------------------------------------------------
// /* データベーステーブル 削除
// /*------------------------------------------------*/
// function rampup_delete_db_table($table_name = '')
// {
//   global $wpdb;
//   $table_name_to_drop = $wpdb->prefix . $table_name;
//   $sql = "DROP TABLE IF EXISTS $table_name_to_drop";
//   $wpdb->query($sql);
// }
// // rampup_delete_db_table('rampup_resevation_posts');

// /*--------------------------------------------------
// /* データーベーステーブル カラム追加
// /*------------------------------------------------*/
// function rampup_insert_custom_column()
// {
//   global $wpdb;
//   $dbname = $wpdb->dbname;

//   $target_table_name = $wpdb->prefix . "rampup_resevation_posts";
//   $col_name_to_insert = "post_status";
//   $data_type_of_col = "varchar(20)";

//   $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
//   WHERE table_name = `{$target_table_name}` AND column_name = '$col_name_to_insert'");

//   if (empty($row)) {
//     $wpdb->query("ALTER TABLE `{$target_table_name}` ADD $col_name_to_insert $data_type_of_col AFTER post_id NOT NULL");
//   }
// }
// // rampup_insert_custom_column();

// /*--------------------------------------------------
//     /* データベースに値を挿入
//     /*------------------------------------------------*/
// function rampup_insert_to_custom_db_table()
// {
//   global $wpdb, $reservation_post_status_array;

//   $args = array(
//     'post_type' => 'reservation',
//     'post_status' => $reservation_post_status_array,
//     'posts_per_page' => -1,
//   );
//   $WP_post = new WP_Query($args);
//   if ($WP_post->have_posts()) {
//     while ($WP_post->have_posts()) {
//       $WP_post->the_post();

//       // ---------- 面談日程 投稿ID ----------
//       $post_id = get_the_ID();
//       $post_status = get_post_status($post_id);


//       // ---------- 面談日程に付属しているユーザーID ----------
//       $user_id = get_post_meta($post_id, 'reservation_user_id', true);

//       // ---------- 同じ値がないかのチェック ----------
//       $post_id_exits = $wpdb->get_row("SELECT post_id FROM `{$wpdb->prefix}rampup_resevation_posts` WHERE post_id = $post_id", ARRAY_A)['post_id'];
//       if ((int)$post_id_exits !== (int)$post_id) {
//         // ---------- 同じ値が無ければinsert実行 ----------
//         $wpdb->insert(
//           'wp_rampup_resevation_posts',
//           array(
//             'post_id' => $post_id,
//             '$post_status' => $$post_status,
//             'user_id' => $user_id
//           ),
//           array(
//             '%d',
//             '%s',
//             '%d'
//           ),
//         );
//       }
//     }
//   }
//   wp_reset_postdata();
// }

// rampup_insert_to_custom_db_table();