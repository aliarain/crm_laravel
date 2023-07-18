<?php

namespace App\Helpers\CoreApp\Traits;

use GuzzleHttp\Client;

trait SmsHandler
{
    function sendSingleSms($to, $text)
    {
        try {
            $client = new Client();
            $body = array(
                "Username" => env('SMS_USERNAME'),
                "Password" => env('SMS_PASSWORD'),
                "From" => env('SMS_FROM'),
                "To" => '88' . $to,
                "Message" => $text,
            );

            $response = $client->request('POST', env("SMS_API_URL"), [
                'form_params' => $body,
            ]);
        } catch (\Throwable$th) {
            return true;
        }
        return true;
    }

    function sendMultipleSms($numbers, $text)
    {
        try {
            foreach ($numbers as $number) {
                $client = new Client();
                $body = array(
                    "Username" => env('SMS_USERNAME'),
                    "Password" => env('SMS_PASSWORD'),
                    "From" => env('SMS_FROM'),
                    "To" => '88' . $to,
                    "Message" => $text,
                );
                $response = $client->request('POST', env("SMS_API_URL"), [
                    'form_params' => $body,
                ]);
            }

        } catch (\Throwable$th) {
            return true;
        }
        return true;
    }

}
