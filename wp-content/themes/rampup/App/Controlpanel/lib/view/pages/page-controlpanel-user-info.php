<?php

use Carbon\Carbon;

/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>

<link rel="stylesheet"
      href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet"
      href="<?= rampup_css_path('page-controlpanel-user-info.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('controlpanel-header.css') ?>">

<link rel="stylesheet"
      href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet"
      href="<?= rampup_css_path('customPost-reservation.css') ?>">



<?php if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
  $class_user = new Rampup_User($user_id);
?>

<div class="project__wrap">

<?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>


  <div class="project__contents">

    <div class="contents__width">

      <section class="projectDetail__title">

        <div class="projectDetail__box">
          <h2 class="projectDetail__name">顧客情報 - <?php echo $class_user->user_displayName ?></h2>
          <a href="<?php echo '/controlpanel-user-edit/?user_id=' . $user_id; ?>"
             class="projectDetail__edit">
            <p>編集</p>
          </a>
        </div>

      </section>

      <!-- ボタンで文章の切り替え -->
      <div class="switching">

        <div class="switching__box">


          <div class="tabs">


            <div class="tabs_flex">

              <div class="tabs_flexItem">
                <input id="tab1"
                       type="radio"
                       name="tab_item"
                       <?php echo (!isset($_GET['user_id'])) ? 'checked=""' : ''; ?>
                       value="info"
                       class="selected">
                <label class="tab_item"
                       for="tab1">基本情報</label>
              </div>

              <div class="tabs_flexItem">
                <input id="tab2"
                       type="radio"
                       name="tab_item"
                       value="progress"
                       <?php echo (isset($_GET['user_id'])) ? 'checked=""' : ''; ?>>
                <label class="tab_item"
                       for="tab2">学習進捗</label>
              </div>


            </div>



            <!-- 基本情報（大枠） -->
            <div class="tab_content"
                 id="tab1_content">

              <!-- 中身 -->
              <!-- 基本情報-->
              <section class="list__wrap2">

                <table class="list__tableInformation">

                  <thead>
                    <tr>
                      <th class="table__title -padding"
                          colspan="5">基本情報</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="table__padding2">名前</td>
                      <td><?= $class_user->user_displayName ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">ユーザー名</td>
                      <td><?= $class_user->user_login ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">ニックネーム</td>
                      <td><?= $class_user->user_nickname ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">メールアドレス</td>
                      <td><?= $class_user->user_email ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">電話番号</td>
                      <td><?= $class_user->user_tel ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">住所</td>
                      <!-- <td>〒102-0074　　東京地千代田区九段南4-5-12</td> -->
                      <td>〒<?= $class_user->user_postNumber; ?>　　<?= $class_user->user_address ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">性別</td>
                      <td><?= $user_gender_array[$class_user->user_gender] ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">生年月日</td>
                      <td><?= $class_user->user_birthday; ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">職業</td>
                      <td><?= $class_user->user_profession; ?></td>
                    </tr>

                  </tbody>

                </table>

              </section>


              <!-- 顧客ステータス -->
              <section class="list__wrap2">

                <table class="list__tableInformation">

                  <thead>
                    <tr>
                      <th class="table__title -padding"
                          colspan="5">顧客ステータス</th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr>
                      <td class="table__padding2">ステータス</td>
                      <td><?= $user_status_translation_array[$class_user->user_status] ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">決済方法</td>
                      <td><?= $user_paymentMethod_array[$class_user->user_paymentMethod] ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">プラン</td>
                      <td><?= $user_paymentPlan_array[$class_user->user_paymentPlan][0]->name ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">申し込み日</td>
                      <td><?= $class_user->user_applicationDate; ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">契約日</td>
                      <td><?= $class_user->user_contractDate; ?></td>
                    </tr>
                    <tr>
                      <td class="table__padding2">利用規約の同意</td>
                      <?php if ($class_user->user_termsOfService === 'on') {
                          $termsOfService_check = '同意済み';
                        } ?>
                      <td><?= $termsOfService_check; ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">流入経路</td>
                      <td><?= $user_inflowRoute_array[$class_user->user_inflowRoute] ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">担当者</td>
                      <td><?= $class_user->user_personInCharge ?></td>
                    </tr>

                    <tr>
                      <td class="table__padding2">担当者記入欄</td>
                      <td><?= $class_user->user_interviewContent ?></td>
                    </tr>

                  </tbody>

                </table>

              </section>


              <!-- 備考 -->
              <!-- <section class="list__wrap2">

                <div class="list__tableInformation">
                  <div class="list__remarksTitle">
                    <h2>備考</h2>
                  </div>
                  <div class="list__remarksText">
                    <p><?= $class_user->user_remarks ?></p>
                  </div>

                </div>

              </section> -->

            </div>
            <!-- 基本情報（大枠）/ -->

            <!-- 学習進捗（大枠） -->
            <div class="tab_content"
                 id="tab2_content">
              <section class="list__wrap3">

                <?php

                  $user_target = $_GET['user_target'];
                  $user_id = $_GET['user_id'];

                  // include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-frame.php";
                  // include get_theme_file_path() . "/App/Core/app-variables.php";
                  ?>

                <link rel="stylesheet"
                      href="<?= rampup_css_path('page-mypage.css') ?>">

                <div class="mypage">
                  <div class="inner -mypageInner">
                    <div class="mypage__contents">

                      <div class="mypage__contentsFlex">
                        <div class="mypage__main">
                          <div class="mypage__mainChild">
                            <?php
                              // ---------- 契約期間終了したらポップを出す ----------
                              global
                                $site_default_contractEnd,
                                $site_default_contractEndCheck;
                              if (
                                $class_user->user_contractEnd <= $site_default_contractEnd &&
                                $site_default_contractEndCheck == 'true'
                              ) {
                                do_action('mypage_user_contractEnd', $user_id);
                              }

                              include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-timer.php"); ?>
                          </div>
                        </div>

                        <div class="mypageSidebar -sticky">
                          <?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-sidebar.php" ?>
                        </div>
                      </div>



                    </div>
                  </div>
                </div>
                <script>
                $('.mypageTimer__calendarValue').prop('disabled', true);
                </script>
              </section>

            </div>
            <!-- 面談日程（大枠） -->
            <div class="tab_content"
                 id="tab3_content">
              <section class="list__wrap3">

                <?php
                  // ---------- 日程追加ボタン ----------
                  if (current_user_can('administrator')) { ?>
                <br><br>
                <div class="-tac">
                  <button type="button"
                          modal-action="change-reservation"
                          calendar-action="add_calendar"
                          class="-button js__modal__trigger">日程追加</button>
                </div>
                <br><br>
                <?php }

                  $user_id = $_GET['user_id'];
                  global $reservation_post_active_status_array;
                  // $user_info_array = get_user_by('slug', get_query_var('author_name'));

                  // ---------- 面談日程ループ ----------
                  // include(get_theme_file_path() . "/App/Dashboard/model/reservation-loop.php");

                  // ---------- 面談日程 ----------
                  $i = 0;
                  $b = 0;
                  $reservation_args = array(
                    'post_type' => 'reservation',
                    'post_status' => $reservation_post_active_status_array,
                    'order' => 'ASC',
                    'orderby' => 'meta_value',
                    'meta_key' => 'reservation_date',
                    'meta_query' => [
                      [
                        'key'     => 'reservation_user_id',
                        'value'   => $user_id,
                        'compare' => '=',
                      ]
                    ],
                    'posts_per_page' => -1,
                  );
                  $WP_post = new WP_Query($reservation_args);
                  // echo '<pre>';
                  // var_dump($WP_post);
                  // echo '</pre>';
                  if ($WP_post->have_posts()) {
                    while ($WP_post->have_posts()) {
                      $WP_post->the_post();
                      $post_id = get_the_ID();
                      // echo " $post_id <br> ";
                      $class_post = new Rampup_Post($post_id);
                      $reservation_date_carbon = new carbon($class_post->reservation_date);
                      // echo "$reservation_date_carbon  <br> ";
                      //   }
                      // }

                      if (
                        $reservation_date_carbon > $now // 未来の日程
                      ) {
                        // ---------- 投稿情報をインクルード ----------
                        include(get_theme_file_path() . "/App/Dashboard/lib/view/pages/single-reservation-info-component.php");
                      }
                    }

                  }
                  //   // ---------- カレンダーモーダルインクルード ----------
                  include(get_theme_file_path() . "/App/Calendar/lib/view/components/component-calendar-modal.php");
                  wp_reset_postdata();


                  ?>
                <script>
                // $('.mypageTimer__calendarValue').prop('disabled', true);
                </script>
              </section>

            </div>
          </div>

        </div>
      </div>


    </div>



  </div>

</div>
<?php } else {
  wp_safe_redirect('/controlpanel-user-list');
  exit;
?>

<?php } ?>

<script>
$(function() {
  // URLを取得
  var url = new URL(window.location.href);
  // URLSearchParamsオブジェクトを取得
  var params = url.searchParams;
  // getにuser_targetの値がある時に変数を定義する
  $user_target = params.get('user_target');
  // checkedのtoggleをする。
  var $radio = $('input[type="radio"]');
  $radio.removeAttr('checked').prop('checked', false).change();
  $radio.attr('checked', true).prop('checked', true).change();

  // getにuser_targetの値が定義されている時
  if ($user_target) {
    $('input[name=tab_item]:eq(1)').prop('checked', true).change();
    $('#tab2_content').addClass('selected');
  } else {
    $('input[name=tab_item]:eq(0)').prop('checked', true).change();
    $('#tab1_content').addClass('selected');

  }

  $('.tabs_flex .tabs_flexItem input[type="radio"]').click(function() {
    let num = $('.tabs_flex .tabs_flexItem input').index(this);
    $('.tab_content').removeClass('selected')
    $('.tab_content').eq(num).addClass('selected');
    $('.tabs_flex .tabs_flexItem input').removeClass('selected')
    $('.tabs_flex .tabs_flexItem input').eq(num).addClass('selected');
  })

})
</script>
<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<?php get_footer();