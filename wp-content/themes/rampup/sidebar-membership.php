<link rel="stylesheet"
      href="<?= rampup_css_path('component-sidebar.css') ?>">

<div class="sidebar__wrap">
  <aside class="sidebar -sticky">

    <div class="sidebar__contents -noScrollBar">

      <div class="sidebar__search">
        <?php get_search_form() ?>
      </div>

      <ul class="sidebar__list">

        <li class="sidebar__listCat">
          <p class="sidebar__catTitle -marker">
            <a href="/mypage">マイページ</a>
          </p>
        </li>

        <?php
        $terms = get_terms('membership_taxonomy', 'hide_empty=0');
        foreach ($terms as $term) {
        ?>
        <li class="sidebar__listCat <?php echo '-' . $term->slug; ?>">
          <p class="sidebar__catTitle -marker">
              <?php echo $term->slug ?>
            </p>


          <ul class="sidebar__listPosts">
            <?php
              $args = array(
                'post_type' => 'membership',
                'taxonomy' => 'membership_taxonomy',
                'term' => $term->slug,
                'posts_per_page' => -1,
              );
              $WP_post = new WP_Query($args);
              if ($WP_post->have_posts()) {
                while ($WP_post->have_posts()) {
                  $WP_post->the_post();

                  $this_slug = str_replace(home_url(), "", get_permalink()); ?>

            <li class="sidebar__listPost
              -marker
              <?php echo ($this_slug === $_SERVER['REQUEST_URI']) ? '-current' : ''; ?>">
              <a class="sidebar__listLink"
                 href="<?php echo get_permalink(); ?>">
                <?php
                      // the_title();
                      $get_title = get_the_title();
                      if (false !== strpos($get_title, 'CHAPTER')) {

                        $titles = double_explode(" ", "　", $get_title);
                        foreach ($titles as $title) {
                          echo $title . '<br>';
                        }
                        // echo $titles[0] . '<br>' . '(' . $titles[1] . ')';
                      } else {
                        the_title();
                      } ?>
              </a>
            </li>
            <?php
                }
              }
              wp_reset_postdata(); ?>
          </ul>

        </li>
        <?php } ?>
      </ul>

    </div>

    <!-- <div class="sidebar__hamburger">
      <span class="sidebar__hamburgerLine -top"></span>
      <span class="sidebar__hamburgerLine -middle"></span>
      <span class="sidebar__hamburgerLine -bottom"></span>
    </div> -->

  </aside>
</div>

<script>
$('.sidebar__hamburger').click(function() {
  $(this).toggleClass('-active');
  $('.sidebar__contents').fadeToggle()
})
</script>