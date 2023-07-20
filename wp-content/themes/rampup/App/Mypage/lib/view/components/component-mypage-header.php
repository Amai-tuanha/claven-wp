<?php
$current_slug = get_queried_object()->post_name;
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);


// ---------- ユーザーが契約切れの時はステータスを休止中にする ----------
if ($class_user->user_contractEnd <= 0 && $class_user->user_contractDate) {
  update_user_meta($user_id, 'user_status', 'rest');
}


$menu_args = [
  'menu_class' => 'mypageHeader__menu',
  'menu_id' => '',
  'container' => false,
];
// ---------- 決済前 ----------
if (
  $class_user->user_status == 'before_contract' ||
  $class_user->user_status == 'application' ||
  $class_user->user_status == 'rest'
) {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
}
// ---------- 決済後 ----------
elseif (
  $class_user->user_status == 'paid'
) {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-after-contract'];
}
// ---------- 初期値 ----------
else {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
}
?>

<div class="mypageHeaderWrap">
  <header class="mypageHeader">

    <div class="mypageHeader__flex">
      <div class="mypageHeader__flexLeft">
        <a href="/mypage"
           class="mypageHeader__logo"><img src="<?php echo get_template_directory_uri() ?>/assets/img/logo-black.svg"
               alt="<?= $site_title ?>"></a>
        <?php wp_nav_menu($menu_args); ?>
      </div>


      <div class="mypageHeader__flexRight">
        <figure class="mypageHeader__avatar">
          <img src="<?= $class_user->user_avatarURL ?>"
               alt="アバター">
        </figure>
        <div class="mypageHeader__flexRightText">
          <p class="mypageHeader__name"><?= $class_user->user_displayName; ?></p>

          <figure class="mypageHeader__arrow">
            <img src="<?php echo get_template_directory_uri() ?>/assets/img/mypage/icon_down_triangle_green_1.svg"
                 alt="下矢印">
          </figure>
        </div>


        <ul class="mypageHeader__accordion">
          <?php if (
            $class_user->user_status == "paid" || $class_user->user_status == "contracted"
          ) { ?>
          <!-- <li class="mypageHeader__accordionChild js__modal__trigger"
              modal-trigger="-js-mypageSetting">
            <p>プロフィール設定</p>
          </li> -->
          <li class="mypageHeader__accordionChild">
            <a href="<?= home_url('/mypage-change-reservation/') ?>">基本情報・面談日程</a>
          </li>
          <li class="mypageHeader__accordionChild">
            <a href="<?= home_url('/mypage-add-reservation/') ?>">面談日程追加</a>
          </li>
          <?php } ?>
          <?php if(
            current_user_can('administrator') 
            ) {?>
              <li class="mypageHeader__accordionChild">
                <a href="<?= home_url('/mypage-default-reservation/') ?>">デフォルト面談設定</a>
              </li>
          <?php } ?>
          <li class="mypageHeader__accordionChild">
            <a href="<?= home_url('/terms-of-service/') ?>">利用規約</a>
          </li>
          <li class="mypageHeader__accordionChild">
            <a href="<?= wp_logout_url() ?>">ログアウト</a>
          </li>
        </ul>
      </div>

      <div class="mypageHeader__hamburger">
        <span class="mypageHeader__hamburgerLine -top"></span>
        <span class="mypageHeader__hamburgerLine -middle"></span>
        <span class="mypageHeader__hamburgerLine -bottom"></span>
      </div>

      <div class="mypageHeader__gnav">
        <div class="mypageHeader__gnavContents">
          <div class="mypageHeader__gnavProfile js__modal__trigger"
               modal-trigger="-js-mypageSetting">
            <figure class="mypageHeader__gnavAvatar">
              <img src="<?= $class_user->user_avatarURL ?>"
                   alt="アバター">
            </figure>
            <p class="mypageHeader__gnavName"><?= $class_user->user_displayName; ?></p>
          </div>
          <?php
          $menu_args['menu_class'] = 'mypageHeader__menuSP';
          wp_nav_menu($menu_args); ?>

          <a class="mypageHeader__gnavLogout"
             href="<?= wp_logout_url() ?>">ログアウト</a>
        </div>
      </div>

    </div>
</div>
</header>
</div>
