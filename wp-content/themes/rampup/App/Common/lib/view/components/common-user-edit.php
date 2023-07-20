<?php

use Carbon\Carbon;

?>

<div class="project__contents">

  <div class="contents__width">
    <?php
    if (!is_page('mypage-change-reservation')) { ?>
      <section class="projectDetail__title">
        <div class="projectDetail__box">
          <h2 class="projectDetail__name">顧客情報の編集<br class="projectDetail__br"> - <?php echo $class_user->user_lastName ?> <?php echo $class_user->user_firstName ?></h2>
          <div class="projectDetail__edit">
            <a href="<?php echo '/controlpanel-user-info/?user_id=' . $user_id; ?>" class="projectDetail__edit">編集キャンセル</a>
          </div>
        </div>
      </section>
    <?php }; ?>



    <form action="<?php echo $permalink; ?>" method="POST" enctype="multipart/form-data" class="controlpanel-useredit__form h-adr">
      <input type="hidden" name="usereditFormTrigger">
      <input type="hidden" name="user_id" value="<?= esc_attr($_GET['user_id']) ?>">
      <section class="list__wrap6">

        <div class="list__tableInformation">

          <h2 class="list__titleBox">基本情報</h2>
          <div class="list__textBox">
            <div class="page__contact2">


              <div class="contact_form">

                <div class="contact_form--box">
                  <p class="contact_form--box--label">プロフィール設定</p>
                  <div class="contact_form--box--item -profileImage">
                    <img class="js-profileImage" src="<?= $class_user->user_avatarURL ?>" alt="アバター">
                    <br>
                    <!-- <label for="profileImage"
                           class="modal__setting__Profilebutton js-profileInput">画像を選択する</label> -->
                    <input id="profileImage" type="file" name="user_avatar" onchange="OutputImage(this);">
                  </div>
                </div>
                <br>


                <div class="contact_form--box">
                  <p class="contact_form--box--label">名前</p>
                  <div class="contact_form--box--item -flex">
                    <input name="user_displayName" type="text" value="<?= $class_user->user_displayName; ?>">
                  </div>
                </div>

                <div class="contact_form--box">
                  <p class="contact_form--box--label">ログインID</p>
                  <div class="contact_form--box--item">
                    <!-- <input name="user_login" type="text" value="<?= $class_user->user_login; ?>"> -->
                    <p class="contact_form--box--label" style="font-weight:400"><?= $class_user->user_login; ?></p>
                  </div>
                </div>



                <div class="contact_form--box">
                  <p class="contact_form--box--label">メールアドレス</p>
                  <div class="contact_form--box--item">
                    <input name="user_email" type="email" value="<?= $class_user->user_email ?>">
                  </div>
                </div>

                <div class="contact_form--box">
                  <p class="contact_form--box--label">電話番号</p>
                  <div class="contact_form--box--item">
                    <input name="user_tel" type="tel" value="<?= $class_user->user_tel ?>">
                  </div>
                </div>


                <?php
                // ---------- 閲覧ユーザーが購読者のときのみ表示する ----------
                if (current_user_can('administrator')) { ?>

                  <!-- <div class="contact_form--box">
                  <p class="contact_form--box--label">郵便番号</p>
                  <div class="contact_form--box--item -width">
                    <input name="user_postNumber"
                           type="text"
                           value="<?= $class_user->user_postNumber; ?>">
                  </div>
                </div>


                <div class="contact_form--box">
                  <p class="contact_form--box--label">住所</p>
                  <div class="contact_form--box--item">
                    <textarea id="user_address"
                              name="user_address"
                              rows="5"
                              cols="33"
                              wrap><?= $class_user->user_address ?></textarea>
                  </div>
                </div> -->


                  <div class="contact_form--box">
                    <p class="contact_form--box--label">性別</p>
                    <div class="contact_form--box--item -arrow -width2">
                      <select name="user_gender">
                        <option value="" selected disabled>未選択</option>
                        <?php
                        global $user_gender_array;
                        foreach ($user_gender_array as $key => $value) {  ?>
                          <option <?php echo ($class_user->user_gender) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>


                  <div class="contact_form--box">
                    <p class="contact_form--box--label">生年月日</p>
                    <div class="contact_form--box--item -flex">
                      <input style="margin-right:1rem" type="text" name="user_dob_year" value="<?= $class_user->user_dob_year; ?>">
                      <input style="margin-right:1rem" type="text" name="user_dob_month" value="<?= $class_user->user_dob_month; ?>">
                      <input type="text" name="user_dob_date" value="<?= $class_user->user_dob_date; ?>">
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <p class="contact_form--box--label">郵便番号</p>
                    <div class="contact_form--box--item -flex">
                      <input style="margin-right:1rem" type="text" name="user_postalcode_first" value="<?= $class_user->user_postalcode_first; ?>">
                      <input type="text" name="user_postalcode_last" value="<?= $class_user->user_postalcode_last; ?>">
                    </div>
                  </div>

                  <div class="contact_form--box">
                    <p class="contact_form--box--label">都道府県</p>
                    <div class="contact_form--box--item -width">
                      <input type="text" class="p-extended-address formTemplate__input" name="user_region" value="<?= $class_user->user_region; ?>" />
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <p class="contact_form--box--label">市区町村</p>
                    <div class="contact_form--box--item -width">
                      <input type="text" class="p-extended-address formTemplate__input" name="user_locality" value="<?= $class_user->user_locality; ?>" />
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <p class="contact_form--box--label">丁目・番地・号</p>
                    <div class="contact_form--box--item -width">
                      <input type="text" class="p-extended-address formTemplate__input" name="user_chome" value="<?= $class_user->user_chome; ?>" />
                    </div>
                  </div>
                  <div class="contact_form--box">
                    <p class="contact_form--box--label">建物名・部屋番号</p>
                    <div class="contact_form--box--item -width">
                      <input type="text" class="p-extended-address formTemplate__input" name="user_building" value="<?= $class_user->user_building; ?>" />
                    </div>
                  </div>
                  <!-- <div class="contact_form--box">
                    <p class="contact_form--box--label">生年月日</p>
                    <div class="contact_form--box--item -width">
                      <input name="user_birthday" class="user_birthday" type="date" value="<?= $class_user->user_birthday; ?>">
                    </div>
                  </div> -->


                  <div class="contact_form--box">
                    <p class="contact_form--box--label">職業</p>
                    <div class="contact_form--box--item">
                      <input name="user_profession" type="text" value="<?= $class_user->user_profession; ?>">
                    </div>
                  </div>

                <?php
                }
                ?>



              </div>


            </div>
          </div>
          <button class="send" type="submit">更新</button>
        </div>

      </section>
    </form>


    <?php
    // ---------- 閲覧ユーザーが購読者のときのみ表示する ----------
    if (current_user_can('administrator')) { ?>
    <form action="<?php echo $permalink; ?>" method="POST" enctype="multipart/form-data" class="controlpanel-useredit__form h-adr">
      <input type="hidden" name="usereditFormTrigger">
      <input type="hidden" name="user_id" value="<?= esc_attr($_GET['user_id']) ?>">
      <section class="list__wrap6">

        <div class="list__tableInformation">
          <h2 class="list__titleBox">顧客ステータス</h2>
          <div class="list__textBox">
            <div class="page__contact2">


              <div class="contact_form">

                <!-- <h2 class="contact_formTitle">新規顧客の登録</h2> -->


                <div class="contact_form--box">
                  <p class="contact_form--box--label">ステータス</p>
                  <div class="contact_form--box--item">
                    <select name="user_status">
                      <?php
                      global $user_status_translation_array;
                      foreach ($user_status_translation_array as $key => $value) { ?>
                        <option <?php echo ($class_user->user_status) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="contact_form--box">
                  <p class="contact_form--box--label">決済方法</p>
                  <div class="contact_form--box--item">
                    <select name="user_paymentMethod">
                      <option value="" selected disabled>未決定</option>
                      <?php foreach ($user_paymentMethod_array as $key => $value) {
                      ?>
                        <option <?php echo ($class_user->user_paymentMethod) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>



                <div class="contact_form--box">
                  <p class="contact_form--box--label">プラン</p>
                  <div class="contact_form--box--item -arrow">
                    <select name="user_paymentPlan">
                      <?php global $user_paymentPlan_array;
                      foreach ($user_paymentPlan_array as $key => $value) { ?>
                        <option <?php echo ($class_user->user_paymentPlan) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value[0]->name ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="contact_form--box">
                  <p class="contact_form--box--label">申込日</p>
                  <div class="contact_form--box--item">
                    <input name="user_applicationDate" type="text" value="<?= $class_user->user_applicationDate; ?>">
                  </div>
                </div>

                <div class="contact_form--box">
                  <p class="contact_form--box--label">契約日</p>
                  <div class="contact_form--box--item">
                    <input name="user_contractDate" type="text" value="<?= $class_user->user_contractDate; ?>">
                  </div>
                </div>

                <?php if (is_developer()) { ?>
                  <div class="contact_form--box">
                    <p class="contact_form--box--label">利用規約</p>
                    <div class="contact_form--box--item">
                      <input type="text" id="termsCheck" value="<?= $class_user->user_termsOfService ?>" name="user_termsOfService">
                      <? //= $class_user->user_termsOfService
                      ?>
                    </div>
                  </div>
                <?php } ?>
                <div class="contact_form--box">
                  <p class="contact_form--box--label">契約期間</p>
                  <div class="contact_form--box--item -flex">
                    <input name="user_contractTerm" class="-width" type="number" min="1" max="100" value="<?php echo ($class_user->user_contractTerm) ? $class_user->user_contractTerm : $site_default_contractTerm; ?>">
                    <span class="-unit">ヶ月</span>
                  </div>
                </div>


                <div class="contact_form--box">
                  <p class="contact_form--box--label">流入経路</p>
                  <div class="contact_form--box--item -arrow">
                    <select name="user_inflowRoute">
                      <?php
                      // global $user_inflowRoute_array;
                      foreach ($user_inflowRoute_array as $key => $value) { ?>
                        <option <?php echo ($class_user->user_inflowRoute) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>



                <div class="contact_form--box">
                  <p class="contact_form--box--label">担当者</p>

                  <div class="contact_form--box--item -arrow -width2">
                    <select name="user_personInCharge">
                      <option value="" disabled selected>担当者を選択</option>
                      <?php global $user_administrators_array;
                      foreach ($user_administrators_array as $user_administrator) { ?>
                        <option <?php echo ($class_user->user_personInCharge) === $user_administrator->display_name ? 'selected' : ''; ?> value="<?= $user_administrator->display_name ?>"><?= $user_administrator->display_name ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>


                <div class="contact_form--box">
                  <p class="contact_form--box--label">担当者記入欄</p>
                  <div class="contact_form--box--item">
                    <textarea name="user_interviewContent" rows="5" cols="33" wrap><?= $class_user->user_interviewContent ?></textarea>
                  </div>
                </div>

              </div>


            </div>
          </div>
          <?php if (current_user_can('administrator')) { ?>
            <br><br>
            <div class="singelReservation__center -center" style="text-align:center;">
              <label for="emailCheck">
                <input type="checkbox" id="emailCheck" name="emailCheck">
                メールを送信する場合はこちらにチェック
              </label>
            </div>
          <?php } ?>
          <button class="send" type="submit">更新</button>
        </div>

      </section>
    </form>
    <?php
    }
    ?>





    <section class="list__wrap6">

      <div class="list__tableInformation">
        <h2 class="list__titleBox">面談日程</h2>
        <div class="list__textBox2">
          <div class="tab_content" id="tab3_content">
            <section class="list__wrap3">

              <?php

              $user_id = ($_GET['user_id']) ? $_GET['user_id'] : get_current_user_id();
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

              // ---------- 日程追加ボタン ----------
              if (current_user_can('administrator')) { ?>
                <br><br>
                <div class="-tac">
                  <button type="button" modal-action="change-reservation" calendar-action="add_calendar" class="send -mauto0 js__modal__trigger">日程追加</button>
                </div>
                <br><br>
              <?php }
              ?>
              <script>
                // $('.mypageTimer__calendarValue').prop('disabled', true);
              </script>
            </section>

          </div>
        </div>
      </div>

    </section>

    <!-- <section class="list__wrap6">

          <div class="list__tableInformation">
            <h2 class="list__titleBox">備考</h2>
            <div class="list__textBox2">
              <textarea class="list__textItem" name="user_remarks" rows="5" cols="33" wrap><?= $class_user->user_remarks ?></textarea>
            </div>
          </div>

        </section> -->


  </div>
</div>