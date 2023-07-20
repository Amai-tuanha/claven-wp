<?php

// use Carbon\Carbon;

// do_action('page_controlpanel_groupEmail_edit__send_form');
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-groupEmail-edit.php");

/**
 * Template Name: ミーティング予定一覧
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-meeting-list.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-groupEmail-edit.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">

<div class="project__wrap">

  <div class="space__block"></div>



  <div class="project__contents">

    <div class="contents__width">








      <div class="page__title">
        <h1>一斉配信メール新規作成</h1>
      </div>


      <div class="mail__template">
        <p>配信するメールテンプレートを作成</p>
        <div class="choice2">
          <select required class="select__placeholder">
            <option hidden>カード決済　リクエストメール</option>
            <?php
            $args = array(
              'post_type' => 'email',
              'posts_per_page' => -1,
            );
            $postslist = get_posts($args);
            if ($postslist) : foreach ($postslist as $post) : setup_postdata($post); ?>
                <option class="_post" href="<?php echo get_permalink(); ?>"><?= $post->post_title ?>
                </option>
            <?php endforeach;
            endif;
            wp_reset_postdata(); ?>
            <?php

            // $args = array(
            //   'post_type' => 'email',
            //   'posts_per_page' => -1,
            // );
            // $WP_post = new WP_Query($args);
            // if ($WP_post->have_posts()) {
            //   while ($WP_post->have_posts()) {
            //       $WP_post->the_post();
            //       $post_terms = get_the_terms($post->ID, 'email_taxonomy');
            //       $this_slug = str_replace(home_url(), "", get_permalink());
            ?>

            <!-- <option value=""><? //= $post->post_title
                                  ?></option> -->
            <?php
            //   }}
            ?>
          </select>
          <?php
          // echo '<pre>';
          // var_dump($WP_post);
          // echo '</pre>';
          ?>
        </div>
      </div>





      <section class="list__wrap">
        <!-- <h2 class="list__title">配信先<span>合計16件</span></h2> -->

        <div class="list__management3">
          <div class="management__box">
            <h2 class="management__title">配信先<span>合計16件</span></h2>

            <!-- <a href="/" class="management__addition">
							<div class="cross"></div>
							<p>顧客を新規追加</p>
						</a> -->
          </div>


          <div class="management__wrap">

            <div class="management__left">

              <div class="choice">
                <select required class="select__placeholder">
                  <option value="" disabled selected>ステータス</option>
                  <?php
                  foreach ($user_status_translation_array as $user_status_translation) { ?>
                    <option><?= $user_status_translation ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="choice -none">
                <input class="select__placeholder" placeholder="申込日" type="text">
              </div>

              <div class="choice -none">
                <input class="select__placeholder" placeholder="契約日" type="text">
              </div>

              <div class="choice">
                <select required class="select__placeholder">
                  <option value="" disabled selected>担当者</option>
                  <?php global $user_administrators_array;
                  foreach ($user_administrators_array as $user_administrator) { ?>
                    <option <?php echo ($class_user->user_personInCharge) === $user_administrator->display_name ? 'selected' : ''; ?>>
                      <?= $user_administrator->display_name ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="choice">
                <select required class="select__placeholder">
                  <option value="" selected disabled>性別</option>
                  <?php global $user_gender_array;
                  foreach ($user_gender_array as $key => $value) {
                  ?>
                    <option <?php echo ($class_user->user_gender) == $key ? 'selected' : ''; ?> value="<?= $key ?>"><?= $value ?></option>
                  <?php } ?>
                </select>
              </div>

            </div>


            <div class="management__right">
              <form class="search-wrap" action="https://" method="get">
                <input type="hidden" name="method" value="keyword">
                <input type="text" name="keyword" value="" class="search-box" placeholder="キーワードで検索">
                <input type="hidden" name="sort" value="desc">
                <label for="search" class="search-button"></label>
                <input id="search" type="submit" value="">
              </form>

              <!-- <form class="search-wrap2" action="https://" method="get">
								<label for="sort" class="search-sort">
									<article class="search-sort_icon"></article>
									<span class="search-sort_text">更新順</span>
								</label>
								<input type="hidden" name="method" value="order">
								<input type="hidden" name="keyword" value="">
								<input type="hidden" name="sort" value="desc">
								<input id="sort" style="display:none;" type="submit" value="">
							</form> -->
            </div>

          </div>

        </div>






        <table class="list__table7">

          <thead class="table__box3">
            <tr>

              <th class="table__title -padding">
                <input type="checkbox" class="" name="checkboxall">
              </th>
              <th class="table__title -padding">名前</th>
              <th class="table__title">メールアドレス</th>
              <th class="table__title">ステータス</th>
              <th class="table__title">申し込み日</th>
              <th class="table__title">契約日</th>
              <th class="table__title">担当者</th>
              <th class="table__title">性別</th>
              <th class="table__title"></th>
            </tr>
          </thead>

          <tbody class="table__box4">
            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>aaaa田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>釣見駿</td>
              <td>男性</td>
              <td></td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" class="" name="checkboxname1">
              </td>
              <td class="table__padding2 -adjust">
                <p>田中 太郎</p>
              </td>
              <td>info@clane.co.jp</td>
              <td>決済完了</td>
              <td>2021/06/24</td>
              <td>2021/12/11</td>
              <td>三矢宏貴</td>
              <td>男性</td>
              <td></td>
            </tr>



          </tbody>

        </table>
      </section>



      <button class="send" type="submit">登録</button>



    </div>


  </div>
</div>

<script type="text/javascript">
  $(function() {



    $('input[name=checkboxall]').click(function() {
      if ($('input[name=checkboxall]').prop("checked") == true) {
        $('input[name=checkboxname1]').prop("checked", false);
        $('input[name=checkboxname1]').trigger("click");
        // $('input[name=checkboxname1]').parents('tr').toggleClass('color__green');
      } else {
        // $('input[name=checkboxname1]').trigger("click");
        $('input[name=checkboxname1]').prop("checked", true);
        $('input[name=checkboxname1]').trigger("click");
        // $('input[name=checkboxname1]').parents('tr').toggleClass('color__green');
      }

    });

    $('input[name=checkboxname1]').change(function() {
      // var windowWidth = $(window).width();
      if ($(this).prop("checked") == true) {
        $(this).parents('tr').addClass('color__green');
      } else {
        $(this).parents('tr').removeClass('color__green');

      }
    });

  });

  $(function() {

  })
</script>

<script>
  // $('.table__box4 tr').on('click', function() {
  // 	$(this).toggleClass('color__green');
  // });
</script>
<?php get_footer();
