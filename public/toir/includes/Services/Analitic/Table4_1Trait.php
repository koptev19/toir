<?php

trait Table4_1Trait
{

    /**
     * @param string $reason
     * @param Workshop|null $workshop = null
     * @param Service|null $service = null
     * @return int
     */
    public function table4_1CountWorkshopPeriod(string $reason, ?Workshop $workshop = null, ?Service $service = null): int
    {
        return $this->table4_1Count($reason, $workshop, $service, true);
    }

    /**
     * @param string $reason
     * @param Workshop $workshop = null
     * @param Service|null $service = null
     * @return int
     */
    public function table4_1CountWorkshopAll(string $reason, ?Workshop $workshop = null, ?Service $service = null): int
    {
        return $this->table4_1Count($reason, $workshop, $service, false);
    }

    /**
     * @param string $reason
     * @param Service|null $service = null
     * @return int
     */
    public function table4_1CountPeriod(string $reason, ?Service $service = null): int
    {
        $count = 0;
        foreach($this->workshops() as $workshop) {
            $count += $this->table4_1CountWorkshopPeriod($reason, $workshop, $service);
        }

        return $count;
    }

    /**
     * @param string $reason
     * @param Service|null $service = null
     * @return int
     */
    public function table4_1CountAll(string $reason, ?Service $service = null): int
    {
        $count = 0;
        foreach($this->workshops() as $workshop) {
            $count += $this->table4_1CountWorkshopAll($reason, $workshop, $service);
        }
        
        return $count;
    }

    /**
     * @param string $reason
     * @param Service|null $service
     * @return array
     */
    private function table4_1GetFilter(string $reason, ?Service $service, bool $is_dates): array
    {
        $filter = [
            'REASON' => $reason,
        ];

        if($service) {
            $filter['SERVICE_ID'] = $service->id;
        }

        if($is_dates) {
            $filter['>=START_DATE'] = $this->dateFrom;
            $filter['<=START_DATE'] = $this->dateTo;
        }

        return $filter;
    }


    /**
     * @param string $reason
     * @param Workshop $workshop = null
     * @param Service|null $service = null
     * @return int
     */
    private function table4_1Count(string $reason, ?Workshop $workshop = null, ?Service $service = null, bool $isDates = false): int
    {
        $filter = $this->table4_1GetFilter($reason, $service, $isDates);

        $plansCount = $workshop->plans()
            ->setFilter($filter)
            ->count();

        $notPlansCount = $workshop->notPlans()
            ->setFilter($filter)
            ->count();

        return $plansCount + $notPlansCount;
    }

    public function table4_1CountCrashPeriod(?Workshop $workshop, bool $isPeriod): int
    {
        $filter = [
            'RESULT' => 'Y',
            'REASON' => Operation::REASON_CRASH,
        ];

        if($workshop) {
            $filter['WORKSHOP_ID'] = $workshop->ID;
        }
        if($isPeriod) {
            $filter['>=PLANNED_DATE'] = $this->dateFrom;
            $filter['<=PLANNED_DATE'] = $this->dateTo;
        }
        $count = History::filter($filter)
            ->count();

        return $count;
    }

}