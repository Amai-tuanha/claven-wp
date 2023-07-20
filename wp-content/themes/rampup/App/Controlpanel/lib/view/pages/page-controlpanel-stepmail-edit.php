<?php

// use Carbon\Carbon;
// do_action('page_controlpanel_stepmail_edit__send_form');
include(get_theme_file_path() . "/App/Controlpanel/model/include-controlpanel-stepmail-edit.php");

// ---------- 送信された情報をユーザーメタで更新 ----------
$email_post_id = $_GET['email_post_id'];
if (isset($_POST['posteditFormTrigger'])) {
  // ---------- 変数定義 ----------
  // $post_id = $post->ID;
  // $user_id = $_GET['user_id'];
  // $class_post = new Rampup_Post($post_id);
  // $class_user = new Rampup_User($user_id);
  // $now = new Carbon('now');
  // $now_formatted = $now->format('Y-m-d H:i:s');
  // ---------- ユーザーメタアップデート ----------
  $my_post = array(
    'ID'           => $_POST['post_id'],
    'post_content'   => $_POST['post_content'],
  );
  // データベースにある投稿を更新する
  wp_update_post($my_post);
  update_post_meta($_POST['post_id'], "email_subject", $_POST['post_subject']);



  // do_action('single_reservation_user_component_posts', $user_id);

  wp_safe_redirect(get_current_link());
  exit;
}


?>

<?php


/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-stepmail-edit.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">



<?php
//if (isset($_GET['user_id'])) {
//$user_id = $_GET['user_id'];
//$class_user = new Rampup_User($user_id);
$args = array(
  'post_type' => 'email',
  'posts_per_page' => -1,
);
$postslist = get_posts($args);
$email_post_id = $_GET['email_post_id'];
$email_subject = get_post_meta($email_post_id, "email_subject", true);
$email_post_content = get_post_field('post_content', $email_post_id);
$email_post_title = get_post_field('post_title', $email_post_id);
?>

<div class="project__wrap">

  <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>

  <div class="project__contents">

    <div class="contents__width">
      <section class="projectDetail__title">

        <div class="projectDetail__box">
          <h2 class="projectDetail__name">メール文章の編集</h2>
          <div class="projectDetail__edit">
            <a href="<?= home_url('/controlpanel-stepmail-list/') ?>">編集キャンセル</a>
          </div>
        </div>

      </section>


      <!-- <section class="list__wrap2">
        <div class="list__tableInformation">
          <h2 class="list__titleBox">テンプレート名</h2>
          <div class="list__textBox">
            <div class="choice2">
              <form class="-tri"
                    action="<?= home_url('/controlpanel-stepmail-edit/') ?>"
                    method="GET">
                <select required
                        class="select__placeholder"
                        name="email_post_id"
                        onchange="onChengeSendForm(event)">
                  <option value=""
                          selected
                          disabled>テンプレートを選んでください</option>
                  <?php
                  if ($postslist) : foreach ($postslist as $post) : setup_postdata($post); ?>
                  <option value="<?= $post->ID ?>"
                          <?php echo ($post->ID == $email_post_id) ? 'selected' : ''; ?>><?= $post->post_title ?></option>
                  <?php endforeach;
                  endif;
                  wp_reset_postdata(); ?>
                </select>
              </form>
            </div>
          </div>
        </div>
      </section> -->


      <?php if (isset($email_post_id)) { ?>
        <section class="list__wrap2">
          <form action="<?= get_current_link() ?>" method="POST">
            <input type="hidden" name="posteditFormTrigger">
            <input type="hidden" name="post_id" value="<?= $email_post_id ?>">
            <div class="list__tableInformation">
              <h2 class="list__titleBox"><?= $email_post_title ?></h2>
              <div class="list__textBox2">
                <label for="" class="list__textItem2">件名</label>
                <input type="text" id="" name="post_subject" size="10" value="<?= $email_subject ?>">
              </div>

              <div class="list__textBox2">

                <label for="" class="list__textItem2">メール本文</label>
                <!-- <div class="shortCode">
                <p class="shortCode__title">ショートコード</p>
                <div class="shortCode__box">
                  <p class="shortCode__item">[startMeeting_date]</p>
                  <p class="shortCode__item">[startMeeting_date]</p>
                  <p class="shortCode__item">[startMeeting_date]</p>
                </div>
              </div> -->

                <div style="position:relative">
                  <?php
                  $editor_settings = [
                    'textarea_rows' => 20,
                  ];
                  wp_editor($email_post_content, "post_content", $editor_settings);

                  ?>
                </div>
              </div>


              <button type="submit" class="send">更新</button>

            </div>
          </form>
        </section>
      <?php } ?>


    </div>

  </div>

</div>
<?php
//} else {
// wp_safe_redirect('/controlpanel-user-list');
// exit;
?>

<?php //}
?>


<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>
<?php get_footer();
