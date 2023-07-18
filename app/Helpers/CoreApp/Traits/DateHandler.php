<?php

namespace App\Helpers\CoreApp\Traits;

use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Integer;

trait DateHandler
{

    function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400) + 1;
    }

    function getMonthDateStringWithTime($date): string
    {
        $new = Carbon::parse($date);
        return $new->format('M d g:i A');
    }

    function getMonthDate($date): string
    {
        $new = Carbon::parse($date);
        return $new->format('M d');
    }

    public static function getCurrentMonthDays(): array
    {
        $date = Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth()->subDay();
        $endOfMonth = $date->copy()->endOfMonth()->format('d');
        $monthDays = [];

        for ($i = 0; $i < $endOfMonth; $i++) {
            $monthDays[] = $startOfMonth->addDay()->startOfDay()->copy();
        }

        return $monthDays;
    }

    public static function getSelectedMonthDays($month): array
    {
        $date = Carbon::parse($month);
        $startOfMonth = $date->copy()->startOfMonth()->subDay();
        $endOfMonth = $date->copy()->endOfMonth()->format('d');
        $monthDays = [];

        for ($i = 0; $i < $endOfMonth; $i++) {
            $monthDays[] = $startOfMonth->addDay()->startOfDay()->copy();
        }

        return $monthDays;
    }

    public function dateFormatInPlainText($date): string
    {
        return Carbon::parse($date)->format("F j, Y, g:i a");
    }

    public function timeFormatInPlainText($time): string
    {
        return Carbon::parse($time)->format("g:i a");
    }
    public function dateFormatWithoutTime($date): string
    {
        return Carbon::parse($date)->format("F j, Y");
    }

    public function databaseFormat($date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function timeFormatFromTimestamp($time): string
    {
        return Carbon::parse($time)->format('H:i');
    }

    public function onlyMonth($date): string
    {
        return Carbon::parse($date)->format('m');
    }

    public function onlyMonthString($date): string
    {
        return Carbon::parse($date)->format('F');
    }

    public function onlyMonthYearString($date): string
    {
        return Carbon::parse($date)->format('M, Y');
    }

    public function dateWithFullTime(): Carbon
    {
        return Carbon::now();
    }
}
