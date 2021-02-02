<?php

trait Table2_1Trait
{

    /**
     * @param Workshop $workshop
     * @param Line|null $line = null
     * @return string
     */
    public function table2_1HistoryTimesString(Workshop $workshop, ?Line $line = null): string
    {
        $historyTimes = $this->table2_1HistoryTimes($workshop, $line);
        return $this->table2_1GetStringTimeByObject($historyTimes);
    }
    
    /**
     * @param Workshop $workshop
     * @param Line|null $line = null
     * @return string
     */
    public function table2_1AllTimesString(Workshop $workshop, ?Line $line = null): string
    {
        $historyTimes1 = $this->table2_1HistoryTimes($workshop, $line);
        $historyTimes2 = $this->table2_1CrashesTimes($workshop, $line);

        $historyTimes = $this->table2_1SumTimeObject($historyTimes1, $historyTimes2);

        return $this->table2_1GetStringTimeByObject($historyTimes);
    }

    /**
     * @param Workshop $workshop
     * @param Line|null $line
     * @return object
     */
    private function table2_1HistoryTimes(Workshop $workshop, ?Line $line): object
    {
        $filter = [
            '>=PLANNED_DATE' => $this->dateFrom,
            '<=PLANNED_DATE' => $this->dateTo,
            'WORKSHOP_ID' => $workshop->ID,
            '!REASON' => Operation::REASON_CRASH,
        ];

        if($line) {
            $filter['LINE_ID'] = $line->ID;
        }

        return $this->table2_1HistoryTimesObject($filter);
    }   
    
    /**
     * @param array $filter
     * @return object
     */
    private function table2_1HistoryTimesObject(array $filter): object
    {
        $filter['RESULT'] = 'Y';
        $histories = History::filter($filter)->get();

        $minutesCount = 0;
        foreach($histories as $history) {
            if(!$history->WORK_TIME) {
                continue;
            }
            [$time1, $time2] = explode(' - ', $history->WORK_TIME);
            [$hour1, $minute1] = explode(':', $time1);
            [$hour2, $minute2] = explode(':', $time2);
            $m1 = $hour1 * 60 + $minute1;
            $m2 = $hour2 * 60 + $minute2;
            $minutesCount += max($m2 - $m1, 0);
        }

        return $this->getObjectByMinutes($minutesCount);
    }
    
    /**
     * @param object $object
     * @return string
     */
    private function table2_1GetStringTimeByObject(object $object): string
    {
        return $object->hour . ' час., ' . $object->minute . ' мин.';
    }
    
    /**
     * @param object $object1
     * @param object $object2
     * @return object
     */
    private function table2_1SumTimeObject(object $object1, object $object2): object
    {
        $minutes = intval($object1->hour * 60 + $object1->minute + $object2->hour * 60 + $object2->minute);

        return $this->getObjectByMinutes($minutes);
    }

    private function getObjectByMinutes(int $minutes): object
    {
        return (object)[
            'hour' => floor($minutes / 60),
            'minute' => $minutes % 60,
        ];
    }
    
    /**
     * @param Workshop|null $workshop
     * @param Line|null $line = null
     * @param Service|null $service = null
     * @return object
     */
    public function table2_1CrashesTimes(?Workshop $workshop = null, ?Line $line = null, ?Service $service = null): object
    {
        $filter = [
            '>=DATE' => $this->dateFrom,
            '<=DATE' => $this->dateTo,
        ];

        if($workshop) {
            $filter['WORKSHOP_ID'] = $workshop->ID;
        }

        if($line) {
            $filter['LINE_ID'] = $line->ID;
        }

        $crashes = Crash::filter($filter)
            ->get();
        
        $minutesCount = 0;
        foreach($crashes as $crash) {
            if($service) {
                $serviceRequest = $crash->serviceRequests()
                    ->setFilter(['SERVICE_ID' => $service->ID])
                    ->first();
                if(!$serviceRequest) {
                    continue;
                }
            }
            [$hour1, $minute1] = explode(':', $crash->TIME_FROM);
            [$hour2, $minute2] = explode(':', $crash->TIME_TO);
            $m1 = $hour1 * 60 + $minute1;
            $m2 = $hour2 * 60 + $minute2;
            $minutesCount += max($m2 - $m1, 0);
        }

        return $this->getObjectByMinutes($minutesCount);
    }
    
    /**
     * @param Workshop|null $workshop = null
     * @param Line|null $line = null
     * @param Service|null $service = null
     * @return string
     */
    public function table2_1CrashesTimesString(?Workshop $workshop = null, ?Line $line = null, ?Service $service = null): string
    {
        return $this->table2_1GetStringTimeByObject($this->table2_1CrashesTimes($workshop, $line, $service));
    }
}