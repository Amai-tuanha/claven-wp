<?php

/**
 * Template Name: ヘッダー
 *
 * @Template Post Type: post, page,
 *
 */
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
$current_slug = get_queried_object()->post_name;
$user_id = get_current_user_id();
$class_user = new Rampup_User($user_id);
// ---------- 送信された情報をユーザーメタで更新 ----------
$url = get_current_link();
$parsed_query = parse_url($url, PHP_URL_PATH);




// ---------- ユーザーが契約切れの時はステータスを休止中にする ----------
if ($class_user->user_contractEnd <= 0 && $class_user->user_contractDate) {
  update_user_meta($user_id, 'user_status', 'rest');
}


$menu_args = [
  'menu_class' => 'mypageHeader__menu',
  'menu_id' => '',
  'container' => false,
];
// // ---------- 決済前 ----------
// if (
//   $class_user->user_status == 'before_contract' ||
//   $class_user->user_status == 'application' ||
//   $class_user->user_status == 'rest'
// ) {
//   $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
// }
// // ---------- （管理者） ----------
// elseif (
//   current_user_can('administrator')
// ) {
//   $menu_args = $menu_args + ['theme_location' => 'controlpanel-menu-header'];
// }
// // ---------- 決済後 ----------
// elseif (
//   strpos($parsed_query, 'controlpanel') === false &&
//   $class_user->user_status == 'paid'
// ) {
//   $menu_args = $menu_args + ['theme_location' => 'mypage-menu-after-contract'];
// }
// // ---------- 初期値 ----------
// else {
//   $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
// }




// if(
//   ($class_user->user_status==="paid"||
//   current_user_can("administrator"))&&
//   strpos($parsed_query,"mypage")!==false
// ){
//   $menu_args = $menu_args + ['theme_location' => 'mypage-menu-after-contract'];
// }elseif(
//   current_user_can("administrator")&&
//   strpos($parsed_query,"controlpanel")!==false
// ){
//   $menu_args = $menu_args + ['theme_location' => 'controlpanel-menu-header'];
// }else{
//   $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
// }
global $post;
$slug = $post->post_name;

//カウンセリングか仮契約か休止中のユーザーがmypageに来たらmy-page-change-reservationにリダイレクトさせる。
if (
  $slug === "mypage" &&
  ($class_user->user_status == 'before_contract' ||
    $class_user->user_status == 'application' ||
    $class_user->user_status == 'rest')
) {
  wp_safe_redirect("/mypage-change-reservation/");
  exit;
}

//決済済みか管理者がmypageに来たらmypage-timerにリダイレクトさせる。
if ($slug === "mypage" && ($class_user->user_status === "paid" || current_user_can("administrator"))) {
  wp_safe_redirect("/mypage-timer/");
  exit;
}

//決済済みかつ管理者でないユーザーがcontrolpanelに来たらリダイレクトさせる。
if ((strpos($parsed_query, "controlpanel") !== false && $class_user->user_status === "paid") && !current_user_can("administrator")) {
  wp_safe_redirect(home_url());
  exit;
}


if (
  ($class_user->user_status == 'before_contract' ||
    $class_user->user_status == 'application' ||
    $class_user->user_status == 'rest') &&
  strpos($parsed_query, 'mypage') !== false
) {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
} elseif (
  ($class_user->user_status === "paid" ||
    current_user_can("administrator")) &&
  strpos($parsed_query, "mypage") !== false
) {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-after-contract'];
} elseif (
  $class_user->user_status === "paid" &&
  is_page('terms-of-service')
) {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-after-contract'];
} elseif (
  current_user_can("administrator") &&
  strpos($parsed_query, "controlpanel") !== false
) {
  $menu_args = $menu_args + ['theme_location' => 'controlpanel-menu-header'];
} elseif (
  current_user_can("administrator") &&
  is_page('terms-of-service')
) {
  $menu_args = $menu_args + ['theme_location' => 'controlpanel-menu-header'];
} elseif (
  current_user_can("administrator") &&
  is_page('spot-noreserve')
) {
  $menu_args = $menu_args + ['theme_location' => 'controlpanel-menu-header'];
} elseif (
  current_user_can("administrator") &&
  strpos($parsed_query, "manual") !== false
) {
  $menu_args = $menu_args + ['theme_location' => 'controlpanel-menu-header'];
} elseif (
  ($class_user->user_status == 'before_contract' ||
    $class_user->user_status == 'application' ||
    $class_user->user_status == 'rest') &&
  strpos($parsed_query, 'controlpanel') !== false
) {
  wp_safe_redirect(home_url());
  exit;
} elseif (
  $class_user->user_status === "paid" &&
  strpos($parsed_query, "controlpanel") !== false
) {
  wp_safe_redirect(home_url());
  exit;
} else {
  $menu_args = $menu_args + ['theme_location' => 'mypage-menu-before-contract'];
}




//サイドバーの各々のメニューの左に○を現在ページに合わせて付与する。
if ($slug === "controlpanel-seminar-attendance" || ($slug === "controlpanel-dashboard" && $_GET["dashboard"] === "dashboard")) {
  $target = "dashboard";
}

if (($_GET["serchFormTrigger"] !== null) && ($_GET["user_status"][0] === "before_contract" || $_GET["user_status"][0] === "cancelled" || $_GET["user_status"][0] === "application" || $_GET["user_status"][0] === "paid")) {
  $target = "controlpanel-user-list";
}

if ($slug === "controlpanel-meeting-list" && ($_GET["post_status"] === "scheduled" || $_GET["post_status"] === "cancelled" || $_GET["post_status"] === "done")) {
  $target = "controlpanel-meeting-list";
}

if ($slug === "spot-noreserve") {
  $target = "spot-noreserve";
}

if ($slug === "controlpanel-stepmail-list") {
  $target = "controlpanel-stepmail-list";
}

if (($slug === "controlpanel-setting") || ($slug === "mypage-default-reservation")) {
  $target = "controlpanel-setting";
}




?>




<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('controlpanel-sidebar.css') ?>">

<div class="mypageHeaderWrap">
  <header class="mypageHeader">

    <div class="mypageHeader__flex">
      <div class="mypageHeader__flexLeft">
        <a href="<?= (strpos($parsed_query, 'controlpanel') !== false) ? '/controlpanel-dashboard/' : '/mypage-timer/'; ?>" class="mypageHeader__logo"><img src="<?php echo get_template_directory_uri() ?>/assets/img/logo-black.svg" alt="<?= $site_title ?>"></a>
        <?php wp_nav_menu($menu_args); ?>
      </div>


      <div class="mypageHeader__flexRight">
        <figure class="mypageHeader__avatar">
          <img class="mypageHeader__img -bdrs50%" src="<?= $class_user->user_avatarURL ?>" alt="アバター">
        </figure>
        <div class="mypageHeader__flexRightText">
          <p class="mypageHeader__name"><?= $class_user->user_displayName; ?></p>

          <figure class="mypageHeader__arrow">
            <i class="fas fa-angle-down -colorPrimary"></i>
          </figure>
        </div>


        <ul class="mypageHeader__accordion">
          <?php if (
            $class_user->user_status == "paid" || $class_user->user_status == "contracted"
          ) { ?>
            <li class="mypageHeader__accordionChild">
              <a href="<?= home_url('/mypage-change-user-edit/') ?>">基本情報</a>
            </li>
            <li class="mypageHeader__accordionChild">
            <?php } ?>
            <?php if (
              !current_user_can('administrator')
            ) { ?>
              <a href="<?= home_url('/mypage-add-reservation/') ?>">面談日程追加</a>
            </li>
            <li class="mypageHeader__accordionChild">
              <a href="<?= home_url('/mypage-change-reservation/') ?>">面談日程一覧</a>
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

      <div class="mypageHeader__hamburger" id="mypageHeader__hamburger">
        <span class="mypageHeader__hamburgerLine -top"></span>
        <span class="mypageHeader__hamburgerLine -middle"></span>
        <span class="mypageHeader__hamburgerLine -bottom"></span>
      </div>

      <div class="mypageHeader__gnav">
        <div class="mypageHeader__gnavContents" style="overflow-y:scroll;">
          <div class="mypageHeader__gnavProfile js__modal__trigger" modal-trigger="-js-mypageSetting">
            <figure class="mypageHeader__gnavAvatar">
              <img src="<?= $class_user->user_avatarURL ?>" alt="アバター">
            </figure>
            <p class="mypageHeader__gnavName"><?= $class_user->user_displayName; ?></p>
          </div>


          <?php
          $menu_args['menu_class'] = 'mypageHeader__menuSP';
          wp_nav_menu($menu_args);
          ?>
          <div class="sideBar__requiem" style="top:68px;">
            <div class="sideBar__menu" style="margin-bottom :1.6rem !important;">
              <div class="sideBar__box">
                <div class="sideBar__titleAndButtonWrapper">
                  <p class="sideBar__title -rightMenu -clickTarget <?php echo $target === "dashboard" ? "-currentPage" : "" ?>" id="dashboard">ダッシュボード</p>
                  <span class="sideBar__button"></span>
                </div>
                <ul class="sideBar__block -accordionPart">
                  <li class="sideBar__list -childLi">
                    <a class="sideBar__title -childAnchor" href="<?= home_url('/controlpanel-dashboard/') . '?dashboard=dashboard'; ?>">
                      学習進捗
                    </a>
                  </li>
                  <li class="sideBar__list -childLi">
                    <a class="sideBar__title -childAnchor" href="<?= home_url('/controlpanel-seminar-attendance/'); ?>">
                      勉強会出席
                    </a>
                  </li>
                </ul>
              </div>
              <div class="sideBar__box">
                <div class="sideBar__titleAndButtonWrapper">
                  <a href="<?= home_url('/controlpanel-user-list/'); ?>" class="sideBar__title -rightMenu -clickTarget <?php echo $target === "controlpanel-user-list" ? "-currentPage" : "" ?> ">顧客管理</a>
                  <span class="sideBar__button"></span>
                </div>
                <ul class="sideBar__block -accordionPart">
                  <li class="sideBar__list -childLi">
                    <a class="sideBar__title  -childAnchor" href="<?= home_url(); ?>/controlpanel-user-list/?serchFormTrigger=&user_status=before_contract">
                      カウンセリング
                      <?= '（' . count(rampup_get_user_by_meta('user_status', 'before_contract')) . '件）' ?>
                    </a>
                  </li>
                  <li class="sideBar__list -childLi">
                    <a class="sideBar__title -childAnchor" href="<?= home_url(); ?>/controlpanel-user-list/?serchFormTrigger=&user_status=cancelled">
                      キャンセル
                      <?= '（' . count(rampup_get_user_by_meta('user_status', 'cancelled')) . '件）' ?>
                    </a>
                  </li>
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url('/controlpanel-user-list/?serchFormTrigger=&user_status=application'); ?>">
                      仮契約
                      <?= '（' . count(rampup_get_user_by_meta('user_status', 'application')) . '件）' ?></a></li>
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url('/controlpanel-user-list/?serchFormTrigger=&user_status=paid'); ?>">
                      決済済み
                      <?= '（' . count(rampup_get_user_by_meta('user_status', 'paid')) . '件）' ?></a></li>
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url(); ?>/wp-admin/user-new.php">顧客の新規作成</a></li>
                </ul>
              </div>

              <?php $rampup_reservation = new Rampup_reservation(); ?>
              <div class="sideBar__box">
                <div class="sideBar__titleAndButtonWrapper">
                  <p class="sideBar__title -rightMenu -clickTarget <?php echo $target === "controlpanel-meeting-list" ? "-currentPage" : "" ?>">ミーティング</p>
                  <span class="sideBar__button"></span>
                </div>
                <ul class="sideBar__block -accordionPart">
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url(); ?>/controlpanel-meeting-list/?post_status=scheduled">
                      ミーティング実施予定一覧
                      (<?= $rampup_reservation->post_count(['post_status' => 'scheduled']) ?>件)</a></li>
                  <!-- <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url(); ?>/controlpanel-meeting-list/?post_status=cancelled">
                      ミーティングキャンセル一覧
                      (<?= $rampup_reservation->post_count(['post_status' => 'cancelled']) ?>件)</a></li>
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url(); ?>/controlpanel-meeting-list/?post_status=done">
                      ミーティング実施済み一覧
                      (<?= $rampup_reservation->post_count(['post_status' => 'done']) ?>件)
                    </a></li> -->
                </ul>
              </div>

              <div class="sideBar__box">
                <div class="sideBar__titleAndButtonWrapper">
                  <a href="<?= home_url('/spot-noreserve/'); ?>" class="sideBar__title -rightMenu -clickTarget <?php echo $target === "spot-noreserve" ? "-currentPage" : "" ?>">予約不可設定</a>
                </div>
                <?php /*
                <ul class="sideBar__block -accordionPart">
                  <li class="sideBar__list"><a href="<?= home_url(); ?>/controlpanel-stepmail-list/">メール一覧</a></li>
                </ul>
                */
                ?>
              </div>
              <div class="sideBar__box">
                <div class="sideBar__titleAndButtonWrapper">
                  <a class="sideBar__title -rightMenu -clickTarget <?php echo $target === "controlpanel-stepmail-list" ? "-currentPage" : "" ?>" href="<?= home_url(); ?>/controlpanel-stepmail-list/">メール</a>
                  <!-- <span class="sideBar__button"></span> -->
                </div>
              </div>

              <div class="sideBar__box">
                <div class="sideBar__titleAndButtonWrapper">
                  <p class="sideBar__title -rightMenu -clickTarget <?php echo $target === "controlpanel-setting" ? "-currentPage" : "" ?>">設定</p>
                  <span class="sideBar__button"></span>
                </div>
                <ul class="sideBar__block -accordionPart">
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url('/controlpanel-setting/'); ?>">セットアップ</a></li>
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url('/controlpanel-setting/?set_p=calendar'); ?>">営業時間設定</a></li>
                  <li class="sideBar__list -childLi"><a class="sideBar__title -childAnchor" href="<?= home_url('/mypage-default-reservation/'); ?>">デフォルトの面談日程</a></li>
                </ul>
              </div>
            </div>
          </div>



          <a class="mypageHeader__gnavLogout" href="<?= wp_logout_url() ?>">ログアウト</a>

        </div>
      </div>

    </div>
</div>
</header>
</div>
<script>
  // ---------- ヘッダー アコーディオン ----------
  $(".mypageHeader__flexRight").click(function() {
    $(this).find(".mypageHeader__accordion").toggleClass("-active");
  });



  // $(function() {
  //   // ---------- gnavグローバルメニュー ----------
  //   $(".mypageHeader__hamburger").on("click", function() {
  //     $(this)
  //       .find(".mypageHeader__hamburgerLine")
  //       .toggleClass("-hamburger-active");
  //     $(".mypageHeader__gnav").toggleClass("-gnav-active");
  //   });

  //   $(".mypageHeader__gnav").on("click", function(e) {
  //     if (!$(e.target).closest(".mypageHeader__gnavContents").length) {
  //       // ターゲット要素の外側をクリックした時の操作
  //       $(".mypageHeader__hamburgerLine").removeClass("-hamburger-active");
  //       $(".mypageHeader__gnav").removeClass("-gnav-active");
  //     } else {
  //       // ターゲット要素をクリックした時の操作
  //     }
  //   });
  //   $(".mypageHeader__menuSP input").each(function() {
  //     $(this).change(function() {
  //       location.reload();
  //     });
  //   });
  // });



  // const targets = document.querySelectorAll(".-clickTarget");
  // targets.forEach(target => {
  //   target.addEventListener("click", () => {
  //     target.nextElementSibling.classList.toggle("appearAccordion");

  //     targets.forEach(el => {
  //       if (target !== el) {
  //         el.nextElementSibling.classList.remove("appearAccordion");
  //       }
  //     })
  //   })
  // })







  $(function() {
    $(".sideBar__button").click(function() {
      $(this).parent().next().slideToggle();
      $(this).toggleClass("open");
      $(".sideBar__button").not($(this)).parent().next().slideUp();
      $(".sideBar__button").not($(this)).removeClass("open");
    });
  });
  const ul = document.querySelector(".mypageHeader__menu");
  const liQuantity = ul.childElementCount;
  if (liQuantity === 2) {
    ul.style.width = "240px";
  }
</script>
<script src="<?php echo get_template_directory_uri() ?>/App/Mypage/lib/js/mypage.js"></script>