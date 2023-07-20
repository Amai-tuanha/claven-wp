<?php
// require __DIR__ . '/../vendor/autoload.php';
include(get_theme_file_path() . "/App/GoogleService/common_function.php");
include(get_theme_file_path() . "/App/vendor/autoload.php");
define('CLIENT_SECRET_PATH', __DIR__ . '/SecretKey.json');


use Carbon\Carbon;

class GoogleCalendar
{
    // private $client;
    private $service;
    private $admin1, $admin2, $meetTimeArray, $googleStartTime, $googleEndTime;
    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        $client->addScope("https://www.googleapis.com/auth/calendar");
        $client->addScope("https://www.googleapis.com/auth/calendar.events");
        $client->setAuthConfig(CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath =  get_theme_file_path() . "/App/GoogleService/token.json";
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }


    public function __construct()
    {
        // ---------- グローバル変数 ----------
        global
            $site_gmail,
            $site_calendar_id,
            $site_numberOfCalendarCell,
            $site_google_calendar_email_array;

        // ---------- インスタンス ----------　// 旧設定の名残8/31以降に残っている場合削除する。
        // $instance = new Google_Client();
        // $instance->setApplicationName('Google Calendar API PHP Quickstart');
        // $instance->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        // $instance->setAuthConfig(CLIENT_SECRET_PATH);
        // $instance->setSubject($site_gmail);
        // $this->client = $instance;

        $this->CalendarId = $site_calendar_id;
        $this->service = new Google_Service_Calendar($this->getClient());
        $this->MeetAllowSetting = ['conferenceDataVersion' => 1];
        $this->GoogleMeetSetting = [
            "createRequest" => [
                "conferenceSolutionKey" => [
                    "type" => "hangoutsMeet"
                ],
                "requestId" => "123"
            ]
        ];

        $this->admin1 = new Google_Service_Calendar_EventAttendee();
        // $this->admin2 = new Google_Service_Calendar_EventAttendee();
        $this->admin1->setEmail($site_gmail);
        // $this->admin2->setEmail($administrator_emails_array);
        $this->admin_attendees = [];
        if (strpos($site_google_calendar_email_array[0], '@') !== false) {
            foreach ($site_google_calendar_email_array as $site_google_calendar_email) {
                $admin_attendee = new Google_Service_Calendar_EventAttendee();
                $admin_attendee->setEmail($site_google_calendar_email);
                array_push($this->admin_attendees, $admin_attendee);
            }
        }

        $this->site_numberOfCalendarCell = $site_numberOfCalendarCell;

        // $this->$meetTimeArray = [
        //   $googleStartTime = new Google_Service_Calendar_EventDateTime(),
        //   $googleEndTime = new Google_Service_Calendar_EventDateTime()
        // ];
        // インスタンス作成
        // $this->$event = new Google_Service_Calendar_Event();
    }

    public function addCalendar($fullname, $email, $tel, $question = null, $reservation_date, $post_id)
    {

        if (!isset($fullname, $email, $tel, $reservation_date, $post_id)) {
            error_alert("送信エラーが発生しました。\n必須項目が正しく入力されているかご確認ください。");
            return;
        }

        $endTime = new Carbon($reservation_date);

        $meetTimeArray = [
            $googleStartTime = new Google_Service_Calendar_EventDateTime(),
            $googleEndTime = new Google_Service_Calendar_EventDateTime()
        ];

        $event_title = get_post_field('post_title', $post_id);

        // ---------- 開始時刻 ----------
        $googleStartTime->setDateTime($reservation_date);

        // ---------- 終了時刻 ----------
        $googleEndTime->setDateTime($endTime->addMinutes($this->site_numberOfCalendarCell));

        // ---------- デスクリプション ----------
        $description = "・メールアドレス:{$email}\n\n・氏名:{$fullname}\n\n・電話番号:{$tel}\n\n・面談内容{$question}\n\n・予約日時{$reservation_date}";

        // $admin2 = new Google_Service_Calendar_EventAttendee();
        // $admin2->setEmail($site_gmail);

        // ---------- infoアカウントを招待 ----------
        array_push($this->admin_attendees, $this->admin1);

        // ---------- お客さんを招待 ----------
        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($email);
        array_push($this->admin_attendees, $attendee1);

        // ---------- 面談担当者を招待 ----------
        $class_post = new Rampup_Post($post_id);
        $personInChange_user_id = $class_post->reservation_personInCharge_user_id;
        $class_user = new Rampup_User($personInChange_user_id);
        $personInChange_email = $class_user->user_email;
        $personInChange = new Google_Service_Calendar_EventAttendee();
        $personInChange->setEmail($personInChange_email);
        array_push($this->admin_attendees, $personInChange);

        // ---------- インスタンス作成 ----------
        $event = new Google_Service_Calendar_Event();
        // ---------- インスタンスに値をセット ----------
        $event->setDescription($description);
        $event->attendees = $this->admin_attendees;
        $event->setStart($meetTimeArray[0]);
        $event->setEnd($meetTimeArray[1]);
        $event->setSummary($event_title);
        $event->conferenceData = $this->GoogleMeetSetting;

        // ---------- イベント発行 ----------
        $event = $this->service->events->insert($this->CalendarId, $event, $this->MeetAllowSetting);
        return [$event->conferenceData->entryPoints[0]->uri, $event->id];
    }

    public function updateCalendar($meet_id, $fullname, $email, $tel, $question = null, $reservation_date, $post_id)
    {
        if (!isset($meet_id, $fullname, $email, $tel, $reservation_date, $post_id)) {
            error_alert("送信エラーが発生しました。\n必須項目が正しく入力されているかご確認ください。");
            return;
        }

        // ---------- イベントタイトル ----------
        $event_title = get_post_field('post_title', $post_id);

        $endTime = new Carbon($reservation_date);
        $meetTimeArray = [
            $googleStartTime = new Google_Service_Calendar_EventDateTime(),
            $googleEndTime = new Google_Service_Calendar_EventDateTime()
        ];

        // ---------- 開始時刻 ----------
        $googleStartTime->setDateTime($reservation_date);

        // ---------- 終了時刻 ----------
        $googleEndTime->setDateTime($endTime->addMinutes($this->site_numberOfCalendarCell));

        // ---------- デスクリプション ----------
        $description = "・メールアドレス:{$email}\n\n・氏名:{$fullname}\n\n・電話番号:{$tel}\n\n・面談内容{$question}\n\n・予約日時{$reservation_date}";

        // ---------- infoアカウントを招待 ----------
        array_push($this->admin_attendees, $this->admin1);

        // ---------- お客さんを招待 ----------
        $attendee1 = new Google_Service_Calendar_EventAttendee();
        $attendee1->setEmail($email);
        array_push($this->admin_attendees, $attendee1);

        // ---------- 面談担当者を招待 ----------
        $class_post = new Rampup_Post($post_id);
        $personInChange_user_id = $class_post->reservation_personInCharge_user_id;
        $class_user = new Rampup_User($personInChange_user_id);
        $personInChange_email = $class_user->user_email;
        $personInChange = new Google_Service_Calendar_EventAttendee();
        $personInChange->setEmail($personInChange_email);
        array_push($this->admin_attendees, $personInChange);

        // ---------- インスタンス作成 ----------
        $event = new Google_Service_Calendar_Event();
        // ---------- インスタンスに値をセット ----------
        $event->setDescription($description);
        $event->attendees = $this->admin_attendees;
        $event->setStart($meetTimeArray[0]);
        $event->setEnd($meetTimeArray[1]);
        $event->setSummary($event_title);
        $event->conferenceData = $this->GoogleMeetSetting;

        // ---------- カレンダー更新 ----------
        $this->service->events->update($this->CalendarId, $meet_id, $event);
        return [$event->conferenceData->entryPoints[0]->uri, $event->id];
    }

    public function deleteCalendar($meet_id)
    {
        if (!isset($meet_id)) {
            error_alert("送信エラーが発生しました。\n必須項目が正しく入力されているかご確認ください。");
            return;
        }
        //カレンダー削除
        $this->service->events->delete($this->CalendarId, $meet_id);
    }

    public function getCalendar($meet_id)
    {
        if (!isset($meet_id)) {
            error_alert("送信エラーが発生しました。\n必須項目が正しく入力されているかご確認ください。");
            return;
        }
        //カレンダー取得
        $event = $this->service->events->get('primary', $meet_id);
        return $event->getSummary();
    }
};