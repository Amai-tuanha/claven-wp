<?php
/*--------------------------------------------------
/* このクラスは現在(2022/01/31)使っていません
/* 使う可能性があるので残して置いてます
/*------------------------------------------------*/

// require __DIR__ .'/../vendor/autoload.php';
require_once(get_theme_file_path() . "/App/vendor/autoload.php");
define('CLIENT_SECRET_PATH', __DIR__ . '/SecretKey.json');

class GoogleSheet
{
    private $client;
    private $service;

    public function __construct()
    {
        $instance = new Google_Client();
        // //アプリケーション名
        $instance->setApplicationName('GoogleCalendarAPIのテスト');
        //権限の指定
        $instance->setScopes(Google_Service_Sheets::SPREADSHEETS);
        //JSONファイルの指定
        $instance->setAuthConfig(CLIENT_SECRET_PATH);
        $this->client = $instance;
        $this->sheetId = $_ENV["SHEET_ID"];
        $this->service = new Google_Service_Sheets($this->client);
    }

    protected function idSearch($paramId)
    {
        $range = 'シート1!J1:J'; // 取得する範囲
        $response = $spreadsheet_service->spreadsheets_values->get($this->sheetId, $range);
        $values = $response->getValues();
        foreach ($values[0] as $v) {
            if ($v === $paramId) {
                return $v;
            }
        }
    }

    public function addRecord($id, $fullname, $email, $tel, $question, $reserve_dt, $subject, $message, $meet_link, $meet_date)
    {
        $values = new Google_Service_Sheets_ValueRange();
        $list = array(
            $fullname,
            $email,
            $tel,
            $question,
            $reserve_dt,
            $id,
            $subject,
            $message,
            $meet_link,
            $meet_date,
        );
        $values->setValues([
            'values' => $list //request->all()を突っ込む
        ]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        $this->service->spreadsheets_values->append(
            $this->sheetId,
            "A1:J1",
            $values,
            $params
        );

        return;
    }
    public function updateRecord($id, $fullname, $email, $tel, $question, $reserve_dt, $subject, $message, $meet_link, $meet_date)
    {
        $values = new Google_Service_Sheets_ValueRange();
        $list = array(
            $fullname,
            $email,
            $tel,
            $question,
            $reserve_dt,
            $id,
            $subject,
            $message,
            $meet_link,
            $meet_date,

        );
        $values->setValues([
            'values' => $list //request->all()を突っ込む
        ]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        // $resultId = $this->searchId($id);
        $this->service->spreadsheets_values->update(
            $this->sheetId,
            "A$id:J$id",
            $values,
            $params
        );

        return;
    }

    public function deleteRecord($id)
    {
        $values = new Google_Service_Sheets_ValueRange();
        $list = array(
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
        );
        $values->setValues([
            'values' => $list //request->all()を突っ込む
        ]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        // $resultId = $this->searchId($id);
        $this->service->spreadsheets_values->update(
            $this->sheetId,
            "A$id:J$id",
            $values,
            $params
        );

        return;
    }
}