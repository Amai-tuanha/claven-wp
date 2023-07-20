<?php
// $emailPost_id = slug_to_post_id("email-payment-card-request");

// use Carbon\Carbon;



/**
 * Template Name: 顧客情報
 *
 * @Template Post Type: post, page,
 *
 */
get_header(); ?>


<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-common.css') ?> ">
<link rel="stylesheet" href="<?= rampup_css_path('page-controlpanel-stepmail-list.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-header.css') ?>">




<div class="project__wrap">
  <?php include(get_theme_file_path() . "/App/Controlpanel/lib/view/components/component-controlpanel-sidebar-users.php") ?>
  <div class="project__contents">

    <div class="contents__width">
      <div class="page__title">
        <h1>メール一覧</h1>
      </div>
      <div class="stepMail">

        <?php
        $i = 0;
        $terms = get_terms('email_taxonomy', 'hide_empty=0');
        $last = count($terms);
        // foreach ($terms as $term) {
          // $i++
        ?>
          <div class="stepMail__box <?php echo ($i == $last) ? '-circle2' : ''; ?>">

            <!-- <h2 class="stepMail__step <?php echo ($i !== 1) ? '-circle' : ''; ?>"><?php echo $term->name ?></h2> -->
<!-- 
            <div class="step__line"></div> -->
            <?php
            $args = array(
              'post_type' => 'email',
              // 'tax_query' => [
              //   [
              //     'taxonomy' => 'email_taxonomy',
              //     'field' => 'slug',
              //     'terms' => $term->slug,
              //   ],
              // ],
              'order' => 'ASC',  //昇順 or 降順の指定
              'orderby' => 'ID',  //何順で並べるかの指定
              'posts_per_page' => -1,
            );
            $WP_post = new WP_Query($args);
            if ($WP_post->have_posts()) {
              while ($WP_post->have_posts()) {
                $WP_post->the_post();
                $post_id = $post->ID;
                $post_terms = get_the_terms($post_id, 'email_taxonomy');
                $this_slug = str_replace(home_url(), "", get_permalink()); ?>

                <?php
                //if ($term->name == $post_terms[0]->name) {
                ?>
                <div class="stepMail__list">
                  <div class="stepMail__listBox">
                    <p class="stepMail__listText2">
                      <?= the_title(); ?>
                    </p>
                  </div>


                  <div class="stepMail__listBox2">
                    <a href="<?php echo home_url() . '/controlpanel-stepmail-edit/?email_post_id=' . $post_id ?>" class="projectDetail__edit">
                      <p>編集</p>
                    </a>
                  </div>
                </div>
                <?php //}
                ?>
            <?php
              }
            }
            wp_reset_postdata(); ?>

            <!-- <h2 class="stepMail__step"></h2> -->
          </div>
        <!-- <?php //} ?> -->
      </div>



    </div>
  </div>
</div>
<script src="<?= get_template_directory_uri() ?>/App/Controlpanel/lib/js/sidebar.js"></script>

<?php get_footer();
