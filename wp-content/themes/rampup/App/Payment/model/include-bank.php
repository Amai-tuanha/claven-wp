<?php


use Carbon\Carbon;

global 
$site_course_name;
$site_bank_data;

session_start();
$user_id = $_SESSION['user_id'];
$class_user = new Rampup_User($user_id);


if ($class_user->cancellationCount == 1) {
  $first_payment = '一括振込: ' . num($class_user->total_price) . '円';
  $other_payment = '';
} else {
  $first_payment = '初回振込: ' . num($class_user->first_payment) . '円';
  $other_payment = '2回目以降: ' . num($class_user->splitAmmount) . '円';
} 


$reservation_date = find_closest_reservation_date($user_id);
$reservation_date_carbon = new Carbon($reservation_date);
$today = new Carbon('today');
$diffs = $reservation_date_carbon->diffInDays($today);

if ($diffs > 3) {
  $response_deadline = $reservation_date_carbon->subDays(3);
}
// ---------- 差が3日未満だったら ----------
else {
  $response_deadline = $reservation_date_carbon->subDays(1);
}
$response_deadline_formatted = carbon_formatting($response_deadline, "19:00");
// session_unset();