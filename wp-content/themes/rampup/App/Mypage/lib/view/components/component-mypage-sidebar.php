<?php if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
} else {
  $user_id = get_current_user_id();
} ?>
<div class="mypageSidebar__progress -sticky">
  <h3 class="mypageSidebar__progressTitle">課題の進捗</h3>

  <ul class="mypageSidebar__circles">
    <?php
    $taxonomies = "membership_taxonomy";
    $args = array(
      'parent'        => '0',
      'childless'     => false,
    );
    $terms = get_terms($taxonomies, $args);
    foreach ($terms as $term) {

      $term_order = $term->term_order;
      $stepColor = $stepColorArray[$term->slug];

      include(get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-progressCircle-sidebar-calc.php");
    } ?>
  </ul>
</div>