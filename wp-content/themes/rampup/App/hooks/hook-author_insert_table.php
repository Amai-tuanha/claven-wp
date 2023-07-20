<?php
add_filter('author_insert_table', function ($user_id) {
  $class_user = new Rampup_User_child($user_id);
?>
<?php if (is_author()) { ?>
<tr class="singelReservation__tr">
  <th class="singelReservation__tHead"
      nowrap>勉強会出席方法</th>
  <td class="singelReservation__tDesc">
    <select name="user_seminar_attendance_type">

      <option class=""
              <?php echo ($class_user->user_seminar_attendance_type === "normal") ? 'selected' : ''; ?>
              value="normal">
        ノーマル(土曜日に勉強会に参加するタイプ)
      </option>
      <option class=""
              <?php echo ($class_user->user_seminar_attendance_type === "archive") ? 'selected' : ''; ?>
              value="archive">
        アーカイブ(土曜日に参加できないために動画で学習するタイプ)
      </option>

    </select>
  </td>
</tr>
<?php } ?>
<?php
});