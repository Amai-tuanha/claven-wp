<?php 
// ---------- PHP実行時にWordPress関数を読み込むためのファイル ----------
// require_once '/home/rampup/clane-rampup.com/public_html/demo-carendar/wp-blog-header.php';
// ---------- GoogleCalendarClassの読み込み ----------
// include_once(get_theme_file_path() . "/App/GoogleService/common_function.php");
// include_once(get_theme_file_path() . "/App/GoogleService/GoogleCalendar.php");
// ---------- GoogleAPI用のvendarファイル読み込み ----------
require_once __DIR__ . '/../vendor/autoload.php';
define('CLIENT_SECRET_PATH', __DIR__ . '/SecretKey.json');

// ---------- 初期設定時入力必須項目 メインとするGoogleカレンダーから抽出する。 ----------
$calendarId = '~~~@group.calendar.google.com';


function getClient()
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
    $tokenPath =  "token.json";
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

// $class = new GoogleCalendar();
// $client = $class->getClient();
$service = new Google_Service_Calendar(getClient());

// Print the next 10 events on the user's calendar.
// $calendarId = 'primary';
$optParams = array(
    'maxResults' => 15,
    'orderBy' => 'startTime',
    'singleEvents' => true,
    'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);

$events = $results->getItems();

if (empty($events)) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($events as $event) {
        $query_string = (parse_url($event->htmlLink, PHP_URL_QUERY)); //URLを解析してクエリパラメータを抽出
        parse_str($query_string, $eid); //文字列を処理し、変数に代入
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
        print(base64_decode($eid['eid']) . "\n");
    }
}

