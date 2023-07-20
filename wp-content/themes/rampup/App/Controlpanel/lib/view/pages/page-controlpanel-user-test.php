<?php

use Carbon\Carbon;

header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-user-test.php");


// echo '<pre>';
// var_dump($keyword_GET);
// echo '</pre>';




/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
  <link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
  <link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-user-list.css') ?>">
  <link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
  <link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">

  <div class="project__wrap">

      <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>


    <div class="project__contents">

      <div class="contents__width">

        <section class="list__management3">
            <?php

            // foreach($user_subscribers_array as $b){
            //   $class_user = new Rampup_User($b);
            //   // if($class_user->user_status == ''){
            //     echo '<pre>';
            //   var_dump($class_user->user_status);
            //   var_dump($class_user->user_displayName);
            //   echo '</pre>';

            //   // }
            // }
            ?>

          <div class="management__box">
            <h2 class="management__title">顧客管理<span><?= $user_list_count ?>件</span></h2>

            <a href="<?= home_url(); ?>/wp-admin/user-new.php" class="management__addition">
              <div class="cross"></div>
              <p>顧客を新規追加</p>
            </a>
          </div>
          <!-- <form action="<?php echo get_permalink(); ?>" method="GET" class="controlpanel-useredit__form h-adr"> -->
          <input type="hidden" name="serchFormTrigger">
          <div class="management__wrap">

            <div class="management__left">
              <!-- <form action="<?php echo get_permalink(); ?>" onchange="onChengeSendForm(event)" method="GET" class="controlpanel-useredit__form h-adr" style="margin-right:2rem;"> -->
              <input type="hidden" name="serchFormTrigger">
              <!-- <div class="js__accordion__header">ステータス</div> -->
              <!-- <select class="select__placeholder" name="user_status"> -->
              <!-- <option value="" disabled selected>ステータス</option> -->
              <!-- <?php
              // global $user_status_translation_array;
              // foreach ($user_status_translation_array as $key => $value) {
              ?> -->
              <!-- <option <?php echo ($class_user->user_status) == $key ? '' : ''; ?> value="<?= $key ?>"><?= $value ?></option> -->
              <!-- <?php //}
              ?> -->
              <!-- </select> -->
                <?php global $user_status_translation_array; ?>
              <a class="status__link <?= (!$_GET['user_status']) ? '-current' : '';?>" href="<?= home_url("/controlpanel-user-list/"); ?>">全て</a>
                <?php foreach ($user_status_translation_array as $key => $value) { ?>
                  <a class="status__link <?= ($_GET['user_status'] === $key) ? '-current' : '';?>" href="<?= home_url("/controlpanel-user-list/?serchFormTrigger=&user_status=$key"); ?>"><?= $value ?></a>

                <?php } ?>

              <!-- </form> -->
              <!-- <form action="<?php echo get_permalink(); ?>"
                  method="GET"
                  class="controlpanel-useredit__form h-adr"
                  style="margin-right:2rem;">
              <div class="choice -none">
                <input class="select__placeholder"
                       onchange="onChengeSendForm(event)"
                       value="<?= $contract_date_GET ?>"
                       placeholder="契約日"
                       type="date"
                       name="contract_date">
              </div>
            </form>
            <form action="<?php echo get_permalink(); ?>"
                  method="GET"
                  class="controlpanel-useredit__form h-adr"
                  style="margin-right:2rem;">
              <div class="choice -none">
                <input class="select__placeholder"
                       onchange="onChengeSendForm(event)"
                       value="<?= $reservation_date_GET ?>"
                       placeholder="面談日"
                       type="date"
                       name="reservation_date">
              </div>
            </form>
            <form action="<?php echo get_permalink(); ?>"
                  method="GET"
                  class="controlpanel-useredit__form h-adr"
                  style="margin-right:2rem;">
              <div class="choice">
                <select class="select__placeholder"
                        onchange="onChengeSendForm(event)"
                        name="personInCharge">
                  <option value=""
                          disabled
                          selected>担当者</option>
                  <?php global $user_administrators_array;
              foreach ($user_administrators_array as $user_administrator) { ?>
                  <option <?php echo ($class_user->user_personInCharge) === $user_administrator->display_name ? 'selected' : ''; ?>>
                    <?= $user_administrator->display_name ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </form> -->


            </div>

            <!-- <form action="<?php echo get_permalink(); ?>"
                method="GET"
                class="controlpanel-useredit__form h-adr">
            <div class="management__right">
              <div class="choice -none">
                <input class="select__placeholder"
                       placeholder="キーワードを入力"
                       type="text"
                       name="keyword">
              </div>
              <button class="send"
                      type="submit">更新</button>
            </div>
          </form>
        </div> -->
            <!-- </form> -->

        </section>
          <?php $i = 0; ?>
          <?php foreach ($user_status_array as $user_status) { ?>
              <?php $user_status_jap = $user_status_translation_array[$user_status]; ?>
            <section class="list__wrap">
                <?php if(!$_GET['user_status']){ ?>
                  <h2 class="list__title"><?= $user_status_jap ?><span class="list__flexNum"><span class="list__titleNum1"></span>件</span></h2>
                <?php } ?>
              <table class="list__table7">

                <thead class="table__box3">
                <tr class="table__row -border5">
                  <th class="table__title -padding -borderLeft">名前</th>
                  <th class="table__title -padding -borderLeft">メールアドレス</th>
                  <th class="table__title -padding -borderLeft">電話番号</th>
                  <th class="table__title -padding -borderLeft">担当者</th>
                  <th class="table__title -padding -borderLeft">面談予定</th>
                  <th class="table__title -padding -borderLeft"></th>
                </tr>
                </thead>
                  <?php
                  // $keyword_subscribers_user_id_array
                  if (!is_nullorempty($keyword_GET)) {
                      foreach ($keyword_subscribers_user_id_array as $keyword_subscribers_user_id) {
                          $class_user = new Rampup_User($keyword_subscribers_user_id);
                          $keyword_subscribers_user_status = $class_user->user_status;

                          if ($keyword_subscribers_user_status === $user_status) {
                              ?>
                            <tr>
                              <td class="table__padding2 -adjust"><?php echo $class_user->user_displayName ?></td>
                              <td><?php echo $class_user->user_email ?></td>
                              <td><?php echo $class_user->user_tel; ?></td>
                              <td><?php echo $class_user->user_personInCharge; ?></td>
                              <td><?php echo $reservation_date; ?></td>
                              <td class="table__padding"><a href="<?php echo '/controlpanel-user-info/?user_id=' . $keyword_user_id; ?>" class="list__detail">詳しく見る</a></td>
                            </tr>
                              <?php
                          }

                          ?>

                        </tbody>
                          <?php
                      }
                  } else {
                      ?>
                    <tbody class="table__box4">
                    <?php
                    $user_array = rampup_get_user_by_meta('user_status', $user_status);
                    $match_user_id_array = [];
                    $all_user_id_array = [];

                    foreach ($user_array as $user) {
                        $user_id = $user->ID;
                        $reservation_date = find_closest_reservation_date($user_id);
                        $class_user = new Rampup_User($user_id);
                        $contract_date = $class_user->user_contractDate;
                        $personInCharge = $class_user->user_personInCharge;

                        // ---------- GETの値が空じゃなくて、部分一致していた時 ----------
                        if (!is_nullorempty($contract_date_GET) && preg_match($escape_contract_date_GET, $contract_date)) {
                            array_push($match_user_id_array, $user_id);
                        } elseif (!is_nullorempty($reservation_date_GET) && preg_match($escape_reservation_date_GET, $reservation_date)) {
                            array_push($match_user_id_array, $user_id);
                        } elseif (!is_nullorempty($personInCharge_GET) && preg_match($escape_personInCharge_GET, $personInCharge)) {
                            array_push($match_user_id_array, $user_id);
                        } else {
                            array_push($all_user_id_array, $user_id);
                        }
                        ?>
                    <?php  }; ?>
                    <?php
                    // ---------- 検索結果に該当ありのとき、GETに値がリクエストされていないとき ----------
                    if (
                        !is_nullorempty($match_user_id_array) ||
                        $i < 0 ||
                        !isset($_SERVER["REQUEST_METHOD"])
                    ) {
                        foreach ($match_user_id_array as $match_user_id) {
                            // ---------- i++は該当したものが同じステータスの中で複数存在するかチェックする ----------
                            $i++;
                            $reservation_date = find_closest_reservation_date($match_user_id);
                            $class_user = new Rampup_User($match_user_id);
                            ?>
                          <tr>
                            <td class="table__padding2 -adjust"><?php echo $class_user->user_displayName ?></td>
                            <td><?php echo $class_user->user_email ?></td>
                            <td><?php echo $class_user->user_tel; ?></td>
                            <td><?php echo $class_user->user_personInCharge; ?></td>
                            <td><?php echo $reservation_date; ?></td>
                            <td class="table__padding"><a href="<?php echo '/controlpanel-user-info/?user_id=' . $match_user_id; ?>" class="list__detail">詳しく見る</a></td>
                          </tr>
                            <?php
                        }
                        // ---------- 検索結果が該当しないorステータス検索をしている時に使用 ----------
                    } elseif (
                        $i === 0
                        xor
                        !preg_match($escape_contract_date_GET, $contract_date)
                        xor
                        !preg_match($escape_reservation_date_GET, $reservation_date)
                        xor
                        !preg_match($escape_personInCharge_GET, $personInCharge)
                    ) {
                        $i = 0;
                        foreach ($all_user_id_array as $all_user_id) {

                            $reservation_date = find_closest_reservation_date($all_user_id);
                            $class_user = new Rampup_User($all_user_id);
                            ?>
                          <tr>
                            <td class="table__padding2 -adjust"><?php echo $class_user->user_displayName ?></td>
                            <td class="table__td -paddingLeft"><?php echo $class_user->user_email ?></td>
                            <td class="table__td -paddingLeft"><?php echo $class_user->user_tel; ?></td>
                            <td class="table__td -paddingLeft"><?php echo $class_user->user_personInCharge; ?></td>
                            <td class="table__td -paddingLeft"><?php echo $reservation_date; ?></td>
                            <td class="table__td -detail"><a href="<?php echo '/controlpanel-user-info/?user_id=' . $all_user_id; ?>" class="list__detail">詳しく見る</a></td>
                          </tr>
                            <?php
                        }
                    }
                    ?>

                    </tbody>
                      <?php
                  }
                  ?>

              </table>
            </section>
              <?php
              $keyword_user_id_array = [];
              ?>
          <?php } //foreach
          ?>

          <?php //}
          ?>


      </div>


    </div>


  </div>

  <script>
    $(function() {
      $('.list__wrap').each(function() {
        var rowCount = $(this).find('tbody tr').length
        console.log(rowCount)
        $(this).find('.list__titleNum1').text(rowCount)
      })
    });
    document.getElementsByClassName('.list__Wrap')
    // $(function() {
    //   $('.list__wrap').each(function() {
    //     var rowCount = $(this).find('tbody tr').length
    //     console.log(rowCount)
    //     $(this).find('.list__titleNum1').text(rowCount)
    //   })
    // });
  </script>



  </tbody>


  </table>
  </section>



  </div>


  </div>


  </div>
  <script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<?php get_footer();
