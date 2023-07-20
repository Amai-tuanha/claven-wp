<?php

use Carbon\Carbon;
?>
<?php
/**
 * Template Name: 勉強会提出課題一覧ページ
 *
 * @Template Post Type: post, page,
 *
 * @since I'LL 1.0
 */
get_header(); ?>
<link rel="stylesheet"
      href="<?php echo get_template_directory_uri(); ?>/assets/css/page-submissions.css">
<?php

// // $end_date = new carbon("2021-11-15 10:00:00");
// $now = new carbon("now");
// $stop = new carbon("2020-01-01 00:00:00");
// $target = new carbon("today");
// // ---------- 後ろ向き ----------
// $end_date = $target->copy()->next('Saturday')->addHours(19);
// $end_date_carbon = new Carbon($end_date);
// $end_day = $week[$end_date->copy()->dayOfWeek];
// $start_date = $end_date->copy()->subDays('5')->subHours('9');
// $start_date_carbon = new Carbon($start_date);
// $start_day = $week[$start_date->copy()->dayOfWeek];




// ---------- 後ろ向き ----------
$target = new carbon('today');
$end_date = $target->copy()->next('Saturday')->subDays('7')->addHours(19);
$end_date_carbon = new Carbon($end_date);
$start_date = $end_date->copy()->subDays('5')->subHours('9');
$start_date_carbon = new Carbon($start_date);

function end_date($subWeeks = 1)
{
  $target = new carbon('now');
  $end_date = $target->copy()->next('Saturday')->subWeeks($subWeeks)->addHours(19);
  $end_date_carbon = new Carbon($end_date);
  return $end_date_carbon;
}

function start_date($end_date)
{
  $start_date = $end_date->copy()->subDays('5')->subHours('9');
  $start_date_carbon = new Carbon($start_date);
  return $start_date_carbon;
}

?>

<div class="submissions">
  <div class="inner">

    <h1>勉強会課題提出型のユーザー提出ファイル一覧 <?php echo ($_GET['type'] == 'download') ? "(ダウンロード可能モード)" : '(ダウンロード不可モード)'; ?></h1>
    <?php if ($_GET['type'] == 'download') { ?>
    <a href="<?php echo get_permalink(); ?>">元に戻す</a>
    <?php } else { ?>
    <a href="<?php echo get_permalink() . '?type=download'; ?>">ダウンロード可能にする</a>
    <?php } ?>
    <div class="submissionsLists">

      <p class="submissionsList__period">
        <?php echo carbon_formatting($start_date)  ?>
        ~
        <?php echo carbon_formatting($end_date)  ?>
        <br>
      </p>
      <?php
      $i = 0;
      $a = 0;
      $subWeeks = 1;
      $post_ID_array = [];
      $post_lists = [];
      $post_array = [$post_ID_array, $post_lists];

      $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_author' => $user_IDs,
        'order_by' => 'date',
        'order' => 'DESC',
        'meta_value' => 'seminar_file_submittion',
        'posts_per_page' => -1,
      );
      $WP_post = new WP_Query($args);
      if ($WP_post->have_posts()) {
        while ($WP_post->have_posts()) {
          $WP_post->the_post();
          $url = $post->guid;
          $pieces = explode("/", $url);
          $fileName = $pieces[count($pieces) - 1];
          $post_id = get_the_ID();

          // ---------- ユーザー関連 ----------
          $user_id = get_post_meta($post_id, 'attachment_user_id', true);
          $user_info_array = get_user_by('id', $user_id);
          $user_displayName = $user_info_array->display_name;

          $post_date = new carbon($post->post_date);
          $post_date_0 = $post_date->copy()->startOfDay();
          $post_day = $week[$post_date->copy()->dayOfWeek];
          $i++;

          // // ---------- 一つ前の投稿取得 ----------
          array_push($post_ID_array, $post_id);
          $previous_post_id = $post_ID_array[$i - 2];
          $previous_post = get_post($previous_post_id);
          $previous_post_date = new carbon($previous_post->post_date);
          // $previous_post_weekNumberInMonth = $previous_post_date->weekNumberInMonth;
          // $previous_post_month = $previous_post_date->month;
          // $post_date_weekNumberInMonth = $post_date->weekNumberInMonth;
          // $post_date_month = $post_date->month;
          // $diff_in_post_dates = $post_date->diffInDays($previous_post_date);

          $end_date_carbon = new Carbon(end_date($subWeeks));
          $start_date_carbon = new Carbon(start_date(end_date($subWeeks)));
          if (
            $post_date > start_date(end_date($subWeeks))
            // &&
            // $post_date < end_date($subWeeks)
          ) {
      ?>
      <div>
        <a href="<?= $url ?>"
           target="_blank"
           user-id="<?= $user_id ?>"
           post-date="<?= $post_date ?>"
           rel="noopener"
           <?php echo ($_GET['type'] == 'download') ? "download=\"$fileName\"" : ''; ?>
           class="submissionsList__item"
           post-date="<?= $post_date ?>">
          <?php echo ($user_displayName) ? $user_displayName . 'さん ' : ''; ?><?= carbon_formatting($post_date) ?> : <?= $fileName ?>
        </a>
      </div>
      <?php
          } else {
            $subWeeks++;
          ?>
      <p class="submissionsList__period">
              <?php echo carbon_formatting(start_date(end_date($subWeeks)))  ?>
              ~
              <?php echo carbon_formatting(end_date($subWeeks))  ?>
              <br>
            </p>
      <?php
            if (
              $previous_post_date > start_date(end_date($subWeeks)) &&
              $previous_post_date < end_date($subWeeks)
            ) {
            ?>
      <div>
        <a href="<?= $url ?>"
           target="_blank"
           user-id="<?= $user_id ?>"
           post-date="<?= $post_date ?>"
           rel="noopener"
           <?php echo ($_GET['type'] == 'download') ? "download=\"$fileName\"" : ''; ?>
           class="submissionsList__item"
           post-date="<?= $post_date ?>">
          <?php echo ($user_displayName) ? $user_displayName . 'さん ' : ''; ?><?= carbon_formatting($post_date) ?> : <?= $fileName ?>
        </a>
      </div>
      <?php
            }
          }
          // // $target = new carbon('now');
          // // $end_date = $target->copy()->next('Saturday')->subDays('7')->addHours(19);
          // // $end_date_carbon = new Carbon($end_date);
          // // $start_date = $end_date->copy()->subDays('5')->subDays('7')->subHours('9');
          // // $start_date_carbon = new Carbon($start_date);

          // // echo " $start_date <br> ";
          // // ---------- 投稿の日付が範囲内だったら ----------
          // if ($post_date > $start_date && $post_date < $end_date) {
          //   $a++;
          //   if ($a == '1') {
          //     $target = new carbon($post_date_0);

          //     if (!$target->isSaturday()) {
          //       $end_date = $target->copy()->next('Saturday')->addHours(19);
          //     } else {
          //       $end_date = $target->copy()->addHours(19);
          //     }
          //     $end_day = $week[$end_date->copy()->dayOfWeek];
          //     $start_date = $end_date->copy()->subDays('5')->subHours('9');
          //     $start_day = $week[$start_date->copy()->dayOfWeek];
          ?>
      <!-- <p class="submissionsList__period js__download__trigger"
         start-date="<?= $start_date ?>"
         end-date="<?= $end_date ?>">
                <?php echo carbon_formatting($start_date)  ?>
                ~
                <?php echo carbon_formatting($end_date)  ?>
                <br>
              </p> -->
      <?php
          //   }
          // }
          // // ---------- 投稿の日付がスタートの日付を超えていなかったら ----------
          // elseif ($post_date < $start_date) {

          //   // echo " $post_date 投稿の日付がスタートの日付を超えていなかったら <br> ";
          //   $target = new carbon($post_date_0);

          //   if ($target->isSaturday()) {
          //     $end_date = $target->copy()->next('Saturday')->addHours(19);
          //   } else {
          //     $end_date = $target->copy()->addHours(19);
          //   }
          //   $end_day = $week[$end_date->copy()->dayOfWeek];
          //   $start_date = $end_date->copy()->subDays('5')->subHours('9');
          //   $start_day = $week[$start_date->copy()->dayOfWeek];


          //   if ($previous_post_weekNumberInMonth !== $post_date_weekNumberInMonth) {
          //     // echo " $start_date <br> ";
          //     if ($post_date < $start_date) {
          //       $end_date = $end_date->copy()->subDays('7');
          //       $end_day = $week[$end_date->copy()->dayOfWeek];
          //       $start_date = $end_date->copy()->subDays('5')->subHours('9');
          //       $start_day = $week[$start_date->copy()->dayOfWeek];
          //     }
          ?>
      <!-- <p class="submissionsList__period js__download__trigger"
         start-date="<?= $start_date ?>"
         end-date="<?= $end_date ?>">
                <?php echo carbon_formatting($start_date)  ?>
                ~
                <?php echo carbon_formatting($end_date)  ?>
                <br>
              </p> -->
      <?php
          //   }
          // }

          // // ---------- 指定した期間以内だったら ----------
          // if (
          //   $post_date > $start_date &&
          //   $post_date < $end_date
          // ) {
          ?>
      <!-- <a href="<?= $url ?>"
         target="_blank"
         user-id="<?= $user_id ?>"
         post-date="<?= $post_date ?>"
         rel="noopener"
         <?php echo ($_GET['type'] == 'download') ? "download=\"$fileName\"" : ''; ?>
         class="submissionsList__item"
         post-date="<?= $post_date ?>">
        <?php echo ($user_displayName) ? $user_displayName . 'さん ' : ''; ?><?= carbon_formatting($post_date) ?> : <?= $fileName ?>
      </a> -->




      <?php } // endwhile
      } // endif
      wp_reset_postdata(); ?>

      </d>
    </div>
  </div>
</div>


<script>
/** URLから自動ダウンロードする関数 */
function downloadFromUrlAutomatically(url, fileName) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.responseType = 'blob';
  xhr.onload = function(e) {
    if (this.status == 200) {
      var urlUtil = window.URL || window.webkitURL;
      var imgUrl = urlUtil.createObjectURL(this.response);
      var link = $('<a>', {
        href: imgUrl,
        download: fileName,
      });
      $('body').append(link);
      link[0].click();
      link.remove();
    }
  };
  xhr.send();
}
$(function() {

  $('.js__download__trigger').click(function() {
    var startDate = $(this).attr('start-date');
    var endDate = $(this).attr('end-date');
    $('.submissionsList__item').each(function() {
      var postDate = $(this).attr('post-date');
      if (
        postDate > startDate &&
        postDate < endDate
      ) {
        path = $(this).attr('href');
        fileName = $(this).attr('download');
        // $(this).click()
        console.log($(this))
        downloadFromUrlAutomatically(path, fileName)
        // myfunc(fileName, path);
      }
    })
  });


  // ---------- 同じユーザーの最新のものだけを太字にする ----------
  userID_array = [];
  var i = 1;
  $('.submissionsList__item').each(function() {
    userID = $(this).attr('user-id');
    var postDate = $(this).attr('post-date');
    if ($.inArray(userID, userID_array) !== -1) {
      i++;
    } else {
      i = 1
    }
    userID_array.push(userID);
    $(this).addClass('-user-submission' + i);
    if ($(this).hasClass('-user-submission1')) {
      $(this).css({
        'font-weight': 'bold',
      })
    } else {
      $(this).css({
        'opacity': '.2',
      })
    }
  })
});
</script>


<?php get_footer(2);