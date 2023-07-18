<?php

namespace App\Helpers\CoreApp\Traits;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Jenssegers\Agent\Facades\Agent;

trait GeoLocationTrait
{
    public function getLocation()
    {
        $agent = new Agent;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if ($ip == "127.0.0.1") {
            $ip = "103.150.254.67";
        }
        $dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        return $dataArray;
    }

    function converToTimeZone($time = "", $toTimeZone = '', $fromTimeZone = '')
    {
        $date = new DateTime($time, new DateTimeZone($fromTimeZone));
        $date->setTimezone(new DateTimeZone($toTimeZone));
        $time = $date->createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $time . ':00');
        return $time;
    }

    public function getDateTime($time)
    {
        $date = \request()->get('date') . ' ' . $time . ':00';
        $datetime = new \DateTime($date);
        $la_time = new DateTimeZone(@auth()->user()->time_zone??'Asia/Dhaka');
        $datetime->setTimezone($la_time);
        return $datetime->format('Y-m-d H:i:s');
    }
}
