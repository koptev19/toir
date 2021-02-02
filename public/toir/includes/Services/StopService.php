<?php

class StopService
{

    /**
     * @param int $lineId
     * @param int $time
     * @param bool $isCreateTask = true
     * @param bool $isCreatePreTask = true
     * 
     * @return int
     */
    public static function createIfNotExists(int $lineId, int $time, bool $isCreateTask = true, bool $isCreatePreTask = true): ?int 
    {
        $date = date('Y-m-d', $time);

        $stop = Stop::getByLineDate($lineId, $date);
        
        if ($stop) {
            $stopId = $stop->id;
        } else {
            $line = Line::find($lineId);
            $workshop = $line->workshop;

            $stopId = Stop::create([
                'DATE' => $date,
                'WORKSHOP_ID' => $workshop->ID,
                'LINE_ID' => $line->ID,
            ]);
        }

        return $stopId;
    }

    /**
     * @param int $lineId
     * @param string $date
     */
    public static function deleteIfExists(int $lineId, string $date) 
    {
        $stop = Stop::getByLineDate($lineId, $date);

        if($stop) {
            $stop->delete();
        }
    }

    /**
     * @param int $lineId
     * @param string $date
     *
     * @return void
     */
    public static function deleteIfEmpty(int $lineId, string $date)
    {
        $countOperations = Operation::filter([
                'PLANNED_DATE' => date("Y-m-d", strtotime($date)),
                'LINE_ID' => $lineId,
            ])
            ->count();

        if($countOperations == 0) {
            self::deleteIfExists($lineId, $date);
        }
    }

    /**
     * @param int $workshopId
     * @param string $date
     *
     * @return void
     */
    public static function deleteIfEmptyInDay(int $workshopId, string $date)
    {
        $workshop = Workshop::find($workshopId);

        foreach($workshop->lines as $line) {
            self::deleteIfEmpty($line->ID, $date);
        }
    }

    /**
     * @param Workshop $workshop
     * @return object|null
     */
    public static function isStopPlanMonth(Workshop $workshop): ?object
    {
        $result = null;

        $currentMonth = currentMonth();

        $lastStop = $workshop->stops()
            ->setFilter(['>=DATE' => $currentMonth['Y'] . '-' . ($currentMonth['m'] < 10 ? '0' : '') . $currentMonth['m'] . '-01'])
            ->orderBy('DATE', 'desc')
            ->first();

        if($lastStop) {
            $date = date('Y-m', strtotime($lastStop->DATE));
            $nextMonth = nextMonth();
            if($date < date('Y-m', mktime(0, 0, 0, $nextMonth['m'], 1, $nextMonth['Y']))) {
                $result = ['month' => $nextMonth['m'], 'year' => $nextMonth['Y']];
            } else {
                if((int)date('j') >= (int)Settings::getValueByName('plan_month_day')) {
                    $next2Month = next2Month();
                    if($date < date('Y-m', mktime(0, 0, 0, $next2Month['m'], 1, $next2Month['Y']))) {
                        $result = ['month' => $next2Month['m'], 'year' => $next2Month['Y']];
                    }
                }
            }
            
        } else {
            $result = ['month' => $currentMonth['m'], 'year' => $currentMonth['Y']];
        }

        return $result ? (object)$result : null;
    }


}

