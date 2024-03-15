<?php

namespace App\Services\Fcm;


use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;

class Fcm
{

    /**
     * @var array|string
     */
    private static $to;

    /**
     * @var string
     */
    private static string $title;
    /**
     * @var string
     */
    private static string $message;

    /**
     * @var string
     */
    private static string $msg;
    /**
     * @var array
     */
    private static array $data = [];
    /**
     * @var array
     */
    private static array $headers = [];

    /**
     * @var array
     */
    private static array $fieldsOfTopicsData;
    /**
     * @var array
     */
    private static array $fieldsOfTokensData;

    /**
     * @return array|string
     */
    private static function getTo()
    {
        return self::$to;
    }

    /**
     * @param mixed $to
     */
    private static function setTo($to): void
    {
        self::$to = $to;
    }

    /**
     * @return mixed
     */
    private static function getTitle()
    {
        return self::$title;
    }

    /**
     * @param mixed $title
     */
    private static function setTitle($title): void
    {
        self::$title = $title;
    }

    /**
     * @return mixed
     */
    private static function getMessage()
    {
        return self::$message;
    }

    /**
     * @param mixed $message
     */
    private static function setMessage($message): void
    {
        self::$message = $message;
    }


    /**
     * @return string
     */
    private static function getMsg(): string
    {
        return self::$msg = urlencode(self::getMessage());
    }

    /**
     * @return array
     */
    private static function getData(): array
    {
        return self::$data = array(
            'title' => self::getTitle(),
            'sound' => "default",
            'msg' => self::getMsg(),
//            'data' => [""],
            'body' => self::getMessage(),
            'color' => "#79bc64");
    }


    /**
     * @return array
     */
    private static function getFieldsOfTopicsData(): array
    {
        return self::$fieldsOfTopicsData = [
            'to' => "/topics/" . self::getTo(),
            'notification' => self::getData(),
//            'data' => "",
            "priority" => "high",
        ];

    }// end of fieldsToTopics function


    /**
     * @return array
     */
    private static function getFieldsOfTokensData(): array
    {
        return self::$fieldsOfTokensData = [
            'registration_ids' => self::getTo(),
            'notification' => self::getData(),
//            'data' => "",
            "priority" => "high",
        ];

    }// end of fieldsToTopics function

    /**
     * @return string[]
     */
    private static function getHeaders(): array
    {
        return self::$headers = [
            'Authorization: key=' . config('fcm.server_key'),
            'Content-Type: application/json'
        ];
    }


    public static function sendToTopic($topic, $title, $message): string
    {
        self::setNotificationData($topic, $title, $message);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::getHeaders());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(self::getFieldsOfTopicsData()));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    public static function sendToTokens($tokens, $title, $message): string
    {
        self::setNotificationData($tokens, $title, $message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::getHeaders());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(self::getFieldsOfTokensData()));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }// end of sendViaTokens function


    /**
     * @param array|string $to
     * @param string $title
     * @param string $message
     * @return void
     */
    private static function setNotificationData($to, $title, $message): void
    {
        self::setTo($to);
        self::setTitle($title);
        self::setMessage($message);
    }

    public function sendFcmMessage(array $tokens, string $title, string $body, array $data = [])
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
//        $server_key = Config('fcm.server_key');
        $headers = [
            'Authorization: key=' . config('fcm.server_key'),
            'Content-Type: application/json',
        ];

        $message = [
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
            'registration_ids' => $tokens,
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            // Handle cURL error
            return ['error' => 'cURL Error: ' . curl_error($ch)];
        }

        curl_close($ch);

//        return json_decode($result, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            // Log cURL error
            \Log::error('cURL Error: ' . curl_error($ch));
            return ['error' => 'cURL Error: ' . curl_error($ch)];
        }


    }


    public function validateTokenForProject($fcmToken)
    {

        $factory = (new Factory())->withServiceAccount(public_path('refine-firebase.json'));
        $messaging = $factory->createMessaging();
        $result = $messaging->validateRegistrationTokens($fcmToken);


        if (empty($result['valid'])) {

            return ['valid' => false];

        }
        return ['valid' => true];
    }


}
