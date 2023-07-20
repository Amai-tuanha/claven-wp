<?php

// use Carbon\Carbon;
// use Google\Service\Blogger\Blog;
// global
// $user_status_translation_array;
// $get_posts_array;
// $reservation_post_status_array;
// do_action('page_controlpanel_meeting_list__send_form');



include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-meeting-list.php");


/**
 * Template Name: ミーティング予定一覧
 *
 * @Template Post Type: post, page,
 *
 */

get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>

<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-meeting-list.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">

<?php

if (is_nullorempty($post_status_GET)) {
  $post_status = [
    'scheduled',
    'done',
    'cancelled'
  ];
} else {
  $post_status = $post_status_GET;
}
$args = array(
  'post_type' => 'reservation',
  // 'post_status' => $reservation_post_status_array[],
  // 'post_status' => 'cancelled',
  'post_status' => $post_status,
  // 'tax_query' => [
  //   [
  //   // 'reservation_date' => $class_post->reservation_date,
  //   // 'field' => 'slug',
  //   // 'terms' => $term->slug,
  //   ],
  // ],
  'posts_per_page' => -1,
);


if ($reservation_post_id_array) {
  // $args['post_status'] = $reservation_post_status_array;
  $args['post__in'] = $reservation_post_id_array;
}


// ---------- 面談日程でソート ----------
$get_posts_array = get_posts($args);


$get_post_count = count($get_posts_array);
$match_reservation_date_array = array_column($get_posts_array, "reservation_date");
// echo '<pre style="text-align: right;">';
// var_dump($match_reservation_date_array);
// echo '</pre>';
if (!is_nullorempty($match_reservation_date_array) && $post_status_GET === 'scheduled') {
  array_multisort(array_map("strtotime", array_column($get_posts_array, "reservation_date")), SORT_ASC, $get_posts_array);
} elseif (!is_nullorempty($match_reservation_date_array)) {
  array_multisort(array_map("strtotime", array_column($get_posts_array, "reservation_date")), SORT_DESC, $get_posts_array);
} else {
  array_multisort(array_map("strtotime", array_column($get_posts_array, "reservation_date")), SORT_DESC, $get_posts_array);
}

// ---------- 空の面談予約削除（投稿消えるので注意） ----------
//    $args = array(
//       'post_type' => 'reservation',
//       'post_status' => 'cancelled',
//       'posts_per_page' => -1,
//     );
//     $get_posts_array = get_posts($args);
// $i = 0;
// $f = 0;
// foreach($get_posts_array as $e){
//   $co_post = new Rampup_Post($e->ID);
//   $user_id = $co_post->reservation_user_id;
//   $i++;
//  if($co_post->reservation_date === ''){
//   $f++;
//   wp_delete_post( $e->ID, true ); //投稿消えるので注意
// }
// }

?>
<div class="project__wrap">
  <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php")
  ?>

  <div class="project__contents">

    <div class="contents__width">
      <?php
      // $i = 0;
      // $f = 0;
      // foreach($get_posts_array as $e){
      //   $class_post = new Rampup_Post($e->ID);
      //   // $user_id = $class_post->reservation_user_id;
      // // echo '<pre>';
      // // var_dump($class_post);
      // // echo '</pre>';
      //  $i++;
      //  if($class_post->reservation_date === ''){
      //   $f++;
      //   // wp_delete_post( $e->ID, true ); 投稿消えるので注意
      // }
      // }
      ?>
      <section class="list__management3">
        <div class="management__box">
          <?php
          //if (isset($post_status_GET)) {
          ?>
          <h2 class="management__title">ミーティング<?= $post_status_jap ?>一覧<span><?= $get_post_count ?>件</span></h2>
          <?php  //}
          ?>
          <!-- <a href="/"
             class="management__addition">
            <div class="cross"></div>
            <p>新規作成</p>
          </a> -->
        </div>

        <?php
        ?>


        <div class="management__wrap">
          <div class="management__left">
            <!-- <form action="<?php echo get_permalink(); ?>" onchange="onChengeSendForm(event)" method="GET" class="controlpanel-useredit__form h-adr"> -->
              <!-- <input type="hidden" name="meetingListFormTrigger"> -->
              <!-- <div class="choice"> -->
                <!-- <select class="select__placeholder" name="post_status"> -->
                  <!-- <option value="">全て</option> -->
                  <!-- <option value="" disabled selected>ステータス</option> -->
                  <!-- <?php
                  // global $reservation_post_status_array;
                  // foreach ($reservation_post_status_array as $key => $value) { ?> -->
                    <!-- <option <?php echo ($post_status_GET) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option> -->
                  <!-- <?php //} ?> -->
                <!-- </select> -->
              <!-- </div> -->
            <!-- </form> -->
            <!-- <form action="<?php echo get_permalink(); ?>" method="GET" class="controlpanel-useredit__form h-adr" style="margin-right:2rem;">
              <div class="choice -none">
                <input class="select__placeholder" onchange="onChengeSendForm(event)" value="<?= $reservation_date_GET ?>" placeholder="面談日" type="date" name="reservation_date">
              </div>
            </form> -->


          </div>


          <!-- <form action="<?php echo get_permalink(); ?>" method="GET" class="controlpanel-useredit__form h-adr">
            <div class="management__right">
              <div class="choice -none">
                <input class="select__placeholder" placeholder="キーワードを入力" type="text" name="keyword">
              </div>
              <button class="send" type="submit">更新</button>
            </div>
          </form> -->
        </div>

      </section>


      <?php // if (isset($post_status_GET)) {
      ?>
      <section class="list__wrap">

        <table class="list__table7">

          <thead class="table__box3">
            <tr class="table__topRow">
              <th class="table__title -padding -borderLeft">面談日時</th>
              <th class="table__title -padding -borderLeft">名前</th>
              <th class="table__title -padding -borderLeft">メールアドレス</th>
              <th class="table__title -padding -borderLeft">顧客ステータス</th>
              <th class="table__title -padding -borderLeft">商談URL</th>
              <th class="table__title -adjust -borderLeft -padding">アクション</th>
            </tr>
          </thead>


          <tbody class="table__box4">
            <?php
            if (isset($keyword_GET)) {
              $num = 0;
              foreach ($get_posts_array as $post) {
                // echo '<pre>';
                // var_dump($post);
                // echo '</pre>';
                setup_postdata($post);
                // $post_terms = get_the_terms($post->ID, 'email_taxonomy');
                $this_slug = str_replace(home_url(), "", get_permalink());
                $post_id = get_the_ID();
                $class_post = new Rampup_Post($post_id);
                $user_id = $class_post->reservation_user_id;
                $class_user = new Rampup_User($user_id);
                $num++;

                if ($class_post->reservation_date !== '') { ?>
                  <tr>
                    <td class="table__padding2 -adjust">
                      <p>
                        <?= carbon_formatting($class_post->reservation_date) ?>
                      </p>
                    </td>
                    <td class="table__td -paddingLeft -borderLeft"><a class="-url" href='controlpanel-user-info/?user_id=<?php echo $class_user->user_id;?>'><?= $class_user->user_displayName ?></a></td>
                    <td class="table__td -paddingLeft -borderLeft"><?= $class_user->user_email ?></td>
                    <td class="table__td -paddingLeft -borderLeft"><?= $user_status_translation_array[$class_user->user_status] ?></td>
                    <td class="table__td -paddingLeft -borderLeft"><a class="-url" href="<?= $class_post->reservation_meet_url ?>" class="table__link"><?= $class_post->reservation_meet_url ?></a></td>
                    <td class="table__padding table__td -paddingLeft -borderLeft">
                      <form class="-tri" action="<?= home_url('controlpanel-meeting-list') ?>" method="POST">
                        <input type="hidden" name="postStatusTrigger">
                        <input type="hidden" name="post_id" value="<?= $post_id ?>">
                        <select class="list__detail" name="post_status" onchange="onChengeSendForm(event)">
                          <?php
                          foreach ($reservation_post_status_array as $key => $value) { ?>
                            <option <?php echo ($class_post->post_status) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                          <?php } ?>
                        </select>
                      </form>
                    </td>
                  </tr>
                  <?php }
              }
            } else {
              $num = 0;
              foreach ($get_posts_array as $post) {

                setup_postdata($post);
                $this_slug = str_replace(home_url(), "", get_permalink());
                $post_id = get_the_ID();
                $class_post = new Rampup_Post($post_id);
                $reservation_date = $class_post->reservation_date;
                $num++;

                if (!is_nullorempty($reservation_date_GET) && preg_match($escape_reservation_date_GET, $reservation_date)) {
                  array_push($match_post_id_array, $post_id);
                } else {
                  array_push($all_post_id_array, $post_id);
                }
                // ---------- 面談予定日が検索され、GETに値を持った時 ----------
                if (!is_nullorempty($match_post_id_array)) {
                  foreach ($match_post_id_array as $match_post_id) {
                    $class_post = new Rampup_Post($match_post_id);
                    $reservation_date = $class_post->reservation_date;
                    $user_id = $class_post->reservation_user_id;
                    $class_user = new Rampup_User($user_id);

                  ?>
                    <tr>
                      <td class="table__padding2 -adjust">
                        <p>
                          <?= carbon_formatting($reservation_date) ?>
                        </p>
                      </td>
                      <td><?= $class_user->user_displayName ?></td>
                      <td><?= $class_user->user_email ?></td>
                      <td><?= $user_status_translation_array[$class_user->user_status] ?></td>
                      <td><a href="<?= $class_post->reservation_meet_url ?>" class="table__link"><?= $class_post->reservation_meet_url ?></a></td>
                      <td class="table__padding ">
                        <form class="-tri" action="<?= home_url('controlpanel-meeting-list') ?>" method="POST">
                          <input type="hidden" name="postStatusTrigger">
                          <input type="hidden" name="post_id" value="<?= $post_id ?>">
                          <select class="list__detail" name="post_status" onchange="onChengeSendForm(event)">
                            <?php
                            foreach ($reservation_post_status_array as $key => $value) { ?>
                              <option <?php echo ($class_post->post_status) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                            <?php } ?>
                          </select>
                        </form>
                      </td>
                    </tr>
                  <?php
                  }
                } elseif (
                  !isset($_SERVER["REQUEST_METHOD"])
                ) {
                  foreach ($all_post_id_array as $all_post_id) {
                    $class_post = new Rampup_Post($all_user_id);
                    $reservation_date = $class_post->reservation_date;
                    $user_id = $class_post->reservation_user_id;
                    $class_user = new Rampup_User($user_id);
                  ?>
                    <tr>
                      <td class="table__padding2 -adjust">
                        <p>
                          <?= carbon_formatting($reservation_date) ?>
                        </p>
                      </td>
                      <td><?= $class_user->user_displayName ?></td>
                      <td><?= $class_user->user_email ?></td>
                      <td><?= $user_status_translation_array[$class_user->user_status] ?></td>
                      <td><a href="<?= $class_post->reservation_meet_url ?>" class="table__link"><?= $class_post->reservation_meet_url ?></a></td>
                      <td class="table__padding ">
                        <form class="-tri" action="<?= home_url('controlpanel-meeting-list') ?>" method="POST">
                          <input type="hidden" name="postStatusTrigger">
                          <input type="hidden" name="post_id" value="<?= $post_id ?>">
                          <select class="list__detail" name="post_status" onchange="onChengeSendForm(event)">
                            <?php
                            foreach ($reservation_post_status_array as $key => $value) { ?>
                              <option <?php echo ($class_post->post_status) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                            <?php } ?>
                          </select>
                        </form>
                      </td>
                    </tr>
                <?php
                  }
                }
                ?>
            <?php }
              //}
            }
            wp_reset_postdata();
            ?>

          </tbody>


        </table>
      </section>

      <? //php  }
      ?>

    </div>


  </div>
</div>

<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<?php
//} else {
// wp_safe_redirect('/controlpanel-meeting-list/?post_status=scheduled');
// exit;
//}
?>
<?php get_footer();


?>