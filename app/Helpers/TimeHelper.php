<?php

namespace App\Helpers;

class TimeHelper
{

    /**
     * @param array|Collection
     * 
     * @return string
     */
    public static function sumWorkTime($times): string
    {
        $minutes = 0;
        foreach($times as $time) {
            $minutes += $time ? self::getDiffMinutes($time) : 0;
        }
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }

    /**
     * @param array|Collection
     * 
     * @return string
     */
    public static function sumTime($times): string
    {
        $minutes = 0;
        foreach($times as $time) {
            [$h, $m] = explode(":", $time);
            $minutes += (int)$h * 60 + (int)$m;
        }
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }

    public static function getDiffMinutes(string $time): int
    {
        [$startTime, $endTime] = explode(' - ', $time);
        [$h1, $m1] = explode(":", $startTime);
        [$h2, $m2] = explode(":", $endTime);

        return (int)$h2 * 60 + (int)$m2 - ((int)$h1 * 60 + (int)$m1);
    }
}