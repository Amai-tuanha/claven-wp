<?php
global
  $user_status_translation_array;
$all_user_status_array = [];
foreach ($user_status_translation_array as $key => $value) {
  array_push($all_user_status_array, $key);
};
?>

<div class="sideBar sidebarOpen" style="top:68px;" id="sidebar">
  <div class="sideBar__menu">
    <div class="sideBar__box">
      <a href="<?= home_url('/controlpanel-dashboard/'); ?>" class="sideBar__title"><span class="sidebar__icon"><i class="fas fa-th-large fa-fw"></i></span><span class="sideBar__titleCharacter">ダッシュボード</span></a>
      <ul class="sideBar__block open">
        <li class="sideBar__list">
          <a href="<?= home_url('/controlpanel-dashboard/'); ?>">
            学習進捗
          </a>
        </li>
        <li class="sideBar__list">
          <a href="<?= home_url('/controlpanel-seminar-attendance/'); ?>">
            勉強会出席
          </a>
        </li>
      </ul>
    </div>
    <div class="sideBar__box">
      <a href="<?= home_url('/controlpanel-user-list/'); ?>" class="sideBar__title"><span class="sidebar__icon"><i class="fas fa-user-cog fa-fw"></i></span><span class="sideBar__titleCharacter">顧客管理</span></a>
      <ul class="sideBar__block open">
        <li class="sideBar__list">
          <a href="<?= home_url('/controlpanel-user-list/?serchFormTrigger=&user_status=before_contract'); ?>">
            カウンセリング
            <?= '（' . count(rampup_get_user_by_meta('user_status', 'before_contract')) . '件）' ?>
          </a>
        </li>
        <li class="sideBar__list">
          <a href="<?= home_url('/controlpanel-user-list/?serchFormTrigger=&user_status=cancelled'); ?>">
            キャンセル
            <?= '（' . count(rampup_get_user_by_meta('user_status', 'cancelled')) . '件）' ?>
          </a>
        </li>
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-user-list/?serchFormTrigger=&user_status=application'); ?>">
            仮契約
            <?= '（' . count(rampup_get_user_by_meta('user_status', 'application')) . '件）' ?></a></li>
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-user-list/?serchFormTrigger=&user_status=paid'); ?>">
            決済済み
            <?= '（' . count(rampup_get_user_by_meta('user_status', 'paid')) . '件）' ?></a></li>
        <li class="sideBar__list"><a href="<?= home_url('/wp-admin/user-new.php'); ?>">顧客の新規作成</a></li>
      </ul>
    </div>

    <?php $rampup_reservation = new Rampup_reservation(); ?>
    <div class="sideBar__box">
      <a href="<?= home_url('/controlpanel-meeting-list/?post_status=scheduled'); ?>" class="sideBar__title"><span class="sidebar__icon"><i class="fas fa-users fa-fw"></i></span><span class="sideBar__titleCharacter">ミーティング</span></a>
      <!-- <ul class="sideBar__block open">
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-meeting-list/?post_status=scheduled'); ?>">
            ミーティング実施予定一覧
            (<?= $rampup_reservation->post_count(['post_status' => 'scheduled']) ?>件)</a></li>
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-meeting-list/?post_status=cancelled'); ?>">
            ミーティングキャンセル一覧
            (<?= $rampup_reservation->post_count(['post_status' => 'cancelled']) ?>件)</a></li>
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-meeting-list/?post_status=done'); ?>">
            ミーティング実施済み一覧
            (<?= $rampup_reservation->post_count(['post_status' => 'done']) ?>件)
          </a></li>
      </ul> -->
    </div>

    <div class="sideBar__box">
      <a href="<?= home_url('/controlpanel-spot-non-reservable/'); ?>" class="sideBar__title"><span class="sidebar__icon"><i class="fas fa-slash fa-fw"></i></span><span class="sideBar__titleCharacter">予約不可設定</span></a>
    </div>
    <div class="sideBar__box">
      <a class="sideBar__title" href="<?= home_url('/controlpanel-stepmail-list/'); ?>"><span class="sidebar__icon"><i class="fas fa-envelope fa-fw"></i></span><span class="sideBar__titleCharacter">メール</span></a>
    </div>

    <div class="sideBar__box">
      <a href="<?= home_url('/controlpanel-setting/'); ?>" class="sideBar__title"><span class="sidebar__icon"><i class="fas fa-cog fa-fw"></i></span><span class="sideBar__titleCharacter">設定</span></a>
      <ul class="sideBar__block open">
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-setting/'); ?>">セットアップ</a></li>
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-setting/?set_p=calendar'); ?>">営業時間設定</a></li>
        <li class="sideBar__list"><a href="<?= home_url('/controlpanel-default-schedule/'); ?>">デフォルトの面談日程</a></li>
        <li class="sideBar__list"><a href="<?= home_url('/manual/'); ?>">マニュアル</a></li>
        <li class="sideBar__list"><a href="<?= home_url('/manual-contact/'); ?>">システムのお問い合わせ</a></li>
      </ul>
    </div>
  </div>
  <div class="sideBar__underSpace"><i class="fas fa-angle-double-left fa-fw" id="toggleButton"></i></div>
</div>

<script>
  $('.sideBar').find('.sideBar__list').each(function() {
    var fullUrl = location.href;
    var htmlHref = $(this).find('a').attr('href');

    if (fullUrl == htmlHref) {
      $(this).addClass('-current')
    }
  });

</script>