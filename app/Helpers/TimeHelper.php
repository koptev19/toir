<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

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
        return self::getTimeByMinutes($minutes);
    }

    /**
     * @param string $time
     * 
     * @return int
     */
    public static function getDiffMinutes(string $time): int
    {
        [$startTime, $endTime] = explode(' - ', $time);
        [$h1, $m1] = explode(":", $startTime);
        [$h2, $m2] = explode(":", $endTime);

        return (int)$h2 * 60 + (int)$m2 - ((int)$h1 * 60 + (int)$m1);
    }

    /**
     * @param int $minutes
     * 
     * @return string
     */
    public static function getTimeByMinutes(int $minutes): string
    {
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }

    /**
     * @param Collection|array $times
     * 
     * @return array
     */
    public static function overlayTimes($times): Collection
    {
        $newTimes = [];

        while(true) {
            foreach($times as $time) {
                $finded = false;
                [$tBegin1, $tEnd1] = explode(' - ', $time);
                foreach($newTimes as $key => $time1){
                    [$tBegin2, $tEnd2] = explode(' - ', $time1);
                    if ($tBegin1 <= $tEnd2 && $tEnd1 >= $tBegin2 || $tBegin2 <= $tEnd1 && $tEnd2 >= $tBegin1){
                        $newTimes[$key] = min($tBegin1, $tBegin2) . ' - ' . max($tEnd1, $tEnd2);	
                        $finded = true;
                        break;
                    }
                }

                
                if(!$finded) {
                    $newTimes[] = $time;
                }
            }
            if (count($times) == count($newTimes)) {
                break;
            }
            $times = $newTimes;
            $newTimes = [];
        }

        return collect($times);
    }

    public static function sumOverlayTimes($times): string
    {
        return self::sumWorkTime(self::overlayTimes($times));
    }

}