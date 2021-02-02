<?php

class DateHelper
{
    /**
     * @param int|string $day
     * @param int|null $month = null
     * @param int|null $year = null
     */
    public static function isWeekend($day, ?int $month = null, ?int $year = null): bool
    {
        $time = $month ? strtotime($year . '-' . $month . '-' . $day) : strtotime($day);
        return date('N', $time) >= 6;
    }
}