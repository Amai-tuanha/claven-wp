<?php

/**
 * Template Name: デバッグページ1
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>

<?php if (current_user_can('administrator')) {

  // if (is_developer("k.tanba@clane.co.jp")) {
  if (is_developer()) {
    // ---------- ここに丹波さんのデバッグ ----------

    /*--------------------------------------------------
    /* ユーザー情報の上書き　（危険なので慎重に！）
    /*------------------------------------------------*/
    $user_array = get_users(
      array(
        'orderby' => 'ID',
        'order' => 'ASC'
      )
    );
    foreach ($user_array as $user) {
      $user_id = $user->ID;
      $class_user = new Rampup_User($user_id);
      // if($class_user->user_lastName === '$class_user->user_displayName'){
      //   echo '<pre>';
      //   var_dump($user_id);
      //   var_dump($class_user->user_displayName);
      //   echo '</pre>';
      //   // update_user_meta($user_id, 'user_lastName', "$class_user->user_displayName");
      // }

      if ($class_user->user_lastName === "") {
        // update_user_meta($user_id, 'user_lastName', "$class_user->user_displayName");
      }
      if ($class_user->user_firstName === "") {
        // update_user_meta($user_id, 'user_firstName', '　');
      }
      if ($class_user->user_termsOfService === "") {
        // update_user_meta($user_id, 'user_termsOfService', 'on');
      }
      if ($class_user->user_question === "") {
        // update_user_meta($user_id, 'user_question', '　');
      }
      if ($class_user->user_tel === "") {
        // update_user_meta($user_id, 'user_tel', '00000000000');
      }
    }
    /*--------------------------------------------------
    /* ステータスないユーザーにステータス付与
    /*------------------------------------------------*/
    // $user_subscribers_object = get_users([
    //   'role' => 'subscriber', //購読者
    // ]);

    // $user_subscribers_array = [];
    // foreach ($user_subscribers_object as $user_subscribers) {
    //   $user_subscribers_id = $user_subscribers->ID;
    //   array_push($user_subscribers_array, $user_subscribers_id);
    // }
    
    // foreach($user_subscribers_array as $b){
    //   $class_user = new Rampup_User($b);
    //   if($class_user->user_status == ''){
    //     update_user_meta($b, 'user_status', 'paid');
    //   }
    // }
    /*--------------------------------------------------
    /* 面談予約がない投稿データの削除
    /*------------------------------------------------*/
    // $args = array(
    //   'post_type' => 'reservation',
    //   'post_status' => 'cancelled',
    //   'posts_per_page' => -1,
    // );
    // $get_posts_array = get_posts($args);
// $i = 0;
// $f = 0;
// foreach($get_posts_array as $e){
//   $class_post = new Rampup_Post($e->ID);
//   $user_id = $class_post->reservation_user_id;
//   $i++;
//  if($class_post->reservation_date === ''){
//   $f++;
//   // wp_delete_post( $e->ID, true ); 投稿消えるので注意
// }
// }



}


?>
<?php } else {
  wp_safe_redirect(home_url());
} ?>
<?php get_footer();
