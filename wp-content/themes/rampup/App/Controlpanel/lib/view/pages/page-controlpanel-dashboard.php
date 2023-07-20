<?php

use Carbon\Carbon;
// do_action('page_controlpanel_dashboard__send_form');
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-dashboard.php");

$dt = new Carbon('today', 'Asia/Tokyo');

/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>

<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-dashboard.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">


<?php
class ControlpanelDashboard
{

  public function choiceDate($a, $b)
  {
    if (!is_nullorempty($a)) {
      return get_user_meta($b, $a, true);
    }
    return null;
  }

  public function choice($a, $b, $c)
  {
    if (!is_nullorempty($a)) {
      return $b;
    }
    return $c;
  }

  public function difDate($a, $b)
  {
    if (strpos($a, '-') === false) {
      // $date = Carbon::createFromTimestamp($a)->copy()->format('Y年m月d日');
      return Carbon::createFromTimestamp($a)->copy()->diffInDays($b);
    } else {
      // $carbon = new Carbon($a);
      return Carbon::parse($a)->diffInDays($b);
      // $date = $carbon->copy()->format('Y年m月d日');
    }
  }
  public function date($a)
  {
    if (strpos($a, '-') === false) {
      $date = Carbon::createFromTimestamp($a)->copy()->format('Y年m月d日');
      // $dif = Carbon::createFromTimestamp($a)->copy()->diffInDays($b);
    } else {
      $carbon = new Carbon($a);
      $date = $carbon->copy()->format('Y年m月d日');
      // $dif = $carbon->diffInDays($b);
    }
  }
}
$class_controlpanelDashboard = new ControlpanelDashboard();
?>

<div class="project__wrap">
  <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>
  <!-- <div class="space__block"></div> -->

  <div class="project__contents -dashboard">

    <div class="contents__width">
      <?php
      // ---------- 決済済みユーザーからuseridのみの配列を生成する。 ----------
      $user_paid_array = rampup_get_user_by_meta('user_status', 'paid');
      $users = array_map(function ($user) {
        $user_id = $user->ID;
        return $user_id;
      }, $user_paid_array);
      ?>

      <div class="dashboard__membership">
        <div class="dashboard__flexTitle">
          <h2 class="dashboard__title">学習進捗 </h2>
          <form action="<?= $permalink; ?>" method="GET" enctype="multipart/form-data" class="dashboardForm h-adr">
            <input type="number" name="elapsedDay" class="dashboardInput">
            <button class="dashboardButton" type="submit">経過日数で絞り込む</button>
          </form>
        </div>
        <ul class="dashboard__ul">
          <?php
          // ---------- コンテンツのカテゴリー一覧を取得 ----------
          // ---------- scriptにてリスト人数を計測 ----------
          $terms = get_terms('membership_taxonomy');
          $count = 0;
          foreach ($terms as $term) {
          ?>
            <li class="dashboard__li">
              <button class="dashboard__tabButton <?php echo ($count === 0) ? 'checked' : ''; ?>"><?= $term->name ?><p id="li_count<?= $count; ?>" class="count">0<span></button>
            </li>
          <?php
            $count++;
          }
          ?>
        </ul>

        <?php

        ?>
        <div class="panel-group">
          <!--タブを切り替えて表示する-->
          <?php for ($i = 0; $i < $count; $i++) {
            // ---------- 各カテゴリのクエリにアクセスする ----------
            $membership_args = [
              'post_type' => 'membership',
              'post_status' => 'publish',
              'order' => 'ASC',
              'posts_per_page' => -1,
              'tax_query' => array(                      //タクソノミーに関する指定はこの中にすべて
                'relation' => 'AND',                     //条件1,2をどのような関係で指定するか
                array(
                  'taxonomy' => "membership_taxonomy",
                  'field' => 'slug',
                  'terms' => "STEP${i}",                //タームをスラッグで指定('field'が'slug'なので)
                )
              )
            ];
            $WP_post = new WP_Query($membership_args);
          ?>
            <!-- タブ切り替えの本体部分 -->
            <div class="panel <?php echo ($i === 0) ? 'show' : ''; ?>">
              <!-- Content <?= $i ?> -->
              <div class="panel__flex">
                <div class="panel__user -b">名前</div>
                <div class="panel__step -b">課題タイトル</div>
                <div class="panel__date -b">経過日数</div>
              </div>
              <?php
              // ---------- コンテンツのslugをまとめる配列を定義 ----------
              $post_infos = [];
              // ---------- 呼び出した投稿の前と次の記事のslug一覧をまとめる配列を定義 ----------
              $nextposts = [];
              $prevposts = [];
              if ($WP_post->have_posts()) {
                while ($WP_post->have_posts()) {
                  $WP_post->the_post();
                  $post_info = [];
                  $post_id = get_the_ID();
                  $post_name = $post->post_name;
                  $title = $post->post_title;
                  $order = $post->menu_order;
                  $next_post_name = get_next_post()->post_name;
                  $post_info += array(
                    'post_id' => $post_id,
                    'post_name' => $post_name,
                    'post_title' => $title,
                    'order' => $order,
                  );
                  // $post_info += array(
                  //   'post_id' => get_next_post()->ID,
                  //   'post_name' => get_next_post()->post_name,
                  //   'title' => get_next_post()->post_title,
                  //   'order' => get_next_post()->menu_order,
                  // );
                  // echo '<pre>';
                  // // var_dump($post_info);
                  // var_dump($next_post_name);
                  // var_dump(get_next_post()->post_title);
                  // echo '</pre>';
                  $prev_post_name = get_previous_post()->post_name;
                  // $next_post_name = get_previous_post()->post_title;
                  array_push($prevposts, $prev_post_name);
                  array_push($nextposts, $next_post_name);
                  array_push($post_infos, $post_info);
              ?>
                  <?php
                }
              }
              wp_reset_postdata();
              // echo '<pre>';
              // // var_dump($prevposts);
              // var_dump($nextposts);
              // // var_dump($post_infos);
              // echo '</pre>';
              // ---------- User一覧を生成 ----------
              foreach ($users as $user) {
                $user_id_array = [];
                $user_completedPost_array = [];
                array_push($user_id_array, $user);

                $class_user = new Rampup_User($user);
                $num = 0;

                // ---------- 各ユーザーの持っているslugのmeta情報を一つずつ入手 ----------
                foreach ($post_infos as $post_info_each) {
                  $post_info_id = $post_info_each["post_id"];
                  $post_info_name = $post_info_each["post_name"];
                  $post_info_title = $post_info_each["post_title"];
                  $user_meta_completedDate = get_user_meta($user, $post_info_name, true);

                  if (!is_nullorempty($user_meta_completedDate)) {
                    $user_completedPost_array += [
                      $post_info_id => $user_meta_completedDate,
                      // $post_info_name => $post_info_title
                    ];
                  }
                }
                // ---------- 一つ前のSTEP ----------
                $next_post_first_name = current($nextposts);
                $next_post_first_date = $class_controlpanelDashboard->choiceDate($next_post_first_name, $user, null);
                // $next_post_first_date = (function ($a ,$b){
                //   if(!is_nullorempty($a)){
                //     return get_user_meta($b, $a, true);
                //   }
                //   return null;
                // })($next_post_first_name,$user);
                // ---------- 現在のSTEP ----------

                $current_post_first_id = $post_infos[0]['post_id'];
                $current_post_end_name = end($post_infos)['post_name'];
                $current_post_end_date = get_user_meta($user, $current_post_end_name, true);
                // ---------- 現在のSTEPでユーザーの完了している最後のChapter ----------
                $user_current_post_id = array_key_last($user_completedPost_array);
                $user_current_post_date = end($user_completedPost_array);
                $user_current_post_id_sort = $class_controlpanelDashboard->choice($user_completedPost_array, $user_current_post_id, $current_post_first_id);

                // $user_current_post_id_sort = (function($a,$b,$c){
                // if(!is_nullorempty($a)){
                //     return $b;
                //   }
                //   return $c;
                // })($user_completedPost_array,$user_current_post_id,$current_post_first_id);

                // ----------  ----------
                $class_post = new Rampup_Post($user_current_post_id_sort);
                $elapsedDay = ($_GET['elapsedDay']) ? $_GET['elapsedDay'] : '10000';

                // echo '<pre>';
                // var_dump($user_completedPost_array);
                // var_dump($user_current_post_date);
                // echo '</pre>';

                // echo '<pre>';
                // // var_dump($user_completedPost_array);
                // var_dump($class_post);
                // // // var_dump($next_post_first);
                // // // var_dump($post_infos);
                // // var_dump($user_completedPost_array);
                // // var_dump($class_user->user_id);
                // // var_dump($class_user->user_displayName);
                // // var_dump(end($user_completedPost_array));
                // // var_dump(!is_nullorempty($next_post_state));
                // // // var_dump($next_post_state);
                // // // var_dump(end($nextposts));
                // // // var_dump(end($user_completedPost_array));
                // echo '</pre>';
                // if ($user_current_post_date == !'' || !is_nullorempty($next_post_first_date)) {

                // ---------- step0のとき ----------
                if ($i === 0) {
                  // ---------- 前STEPの最後の課題進捗日が空ではない、かつ現STEPの最後の課題進捗日が空のとき ----------
                  if (is_nullorempty($current_post_end_date)) {
                    // if (true) {
                    if ($user_current_post_date === false) {
                      $dif = Carbon::parse($class_user->user_contractDate)->diffInDays($dt);
                    } else {
                      $dif = $class_controlpanelDashboard->difDate($user_current_post_date, $dt);
                    }

                    if ($dif <= $elapsedDay) {
                      // if (true) {
                  ?>
                      <div class="panel__flex js__content">
                        <div class="panel__user"><?= $class_user->user_displayName ?></div>
                        <div class="panel__step"><?= $class_post->post_title ?></div>
                        <div class="panel__date"><?= $dif . "日" ?></div>
                      </div>
                      <?php
                    }
                  // }elseif (is_nullor){
                    ?>
                    <div class="panel__flex js__content">
                      <div class="panel__user"><?= $class_user->user_displayName ?></div>
                      <div class="panel__step"><?= $class_post->post_title ?></div>
                      <div class="panel__date"><?= $dif . "日" ?></div>
                    </div>
                <?php 
                  }
                  // ---------- STEP0以外のとき ----------
                } elseif ($i == !0) {
                  // ---------- 前STEPの最後の課題進捗日が空ではない、かつ現STEPの最後の課題進捗日が空のとき ----------
                  if (is_nullorempty($current_post_end_date) && !is_nullorempty($next_post_first_date)) {
                    if ($user_current_post_date === false) {
                      $dif = $class_controlpanelDashboard->difDate($next_post_first_date, $dt);
                    } else {
                      $dif = $class_controlpanelDashboard->difDate($user_current_post_date, $dt);
                    }

                    if ($dif <= $elapsedDay) {
                      // if (true) {
                    ?>
                      <div class="panel__flex js__content">
                        <div class="panel__user"><?= $class_user->user_displayName ?></div>
                        <div class="panel__step"><?= $class_post->post_title ?></div>
                        <div class="panel__date"><?= $dif . "日" ?></div>
                      </div>
              <?php
                    }
                  }
                }

                array_push($user_id_array, $user_completedPost_array);
              } //user_foreach
              ?>

            </div>
          <?php
          }; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const tabs = document.getElementsByClassName('dashboard__tabButton');
  for (let i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener('click', tabSwitch, false);
  }

  function tabSwitch() {
    // タブのclassの値を変更
    document.getElementsByClassName('checked')[0].classList.remove('checked');
    this.classList.add('checked');
    // コンテンツのclassの値を変更
    document.getElementsByClassName('show')[0].classList.remove('show');
    const arrayTabs = Array.prototype.slice.call(tabs);
    console.dir(arrayTabs);
    const index = arrayTabs.indexOf(this);
    console.dir(index);
    document.getElementsByClassName('panel')[index].classList.add('show');
  };


  // タブないの人数をカウント

  $(function() {
    let num = 0;
    $('.panel').each(function() {
      var rowCount = $(this).find('.js__content').length;
      console.log(rowCount);
      $(".dashboard__membership").find("#li_count" + num).text(`(${rowCount}人)`);
      num++
    })
  });
</script>
<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>

<?php get_footer();
