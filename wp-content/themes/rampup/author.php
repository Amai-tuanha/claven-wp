<?php

use Carbon\Carbon;
?>
<?php
/**
 * Template Name: ユーザー詳細ページ
 * @package WordPress
 * @Template Post Type: post, page,
 * @subpackage I'LL

 */
 get_header(); ?>


<div class="inner">
  <?php
  if (current_user_can('administrator')) {
    // ---------- ユーザーID ----------
    $user_info_array = get_user_by('slug', get_query_var('author_name'));
    $user_id = $user_info_array->id;

    // ---------- 面談日程ループ ----------
    include(get_theme_file_path() . "/App/Dashboard/model/reservation-loop.php");
  } else {
    echo '管理者権限のユーザーのみこのページを閲覧できます。';
    // wp_safe_redirect('/mypage');
    // exit;
  }
  ?>
</div>
<?php get_footer();