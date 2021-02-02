<?php

trait Table4_2Trait
{

    /**
     * @param Equipment $equipment
     * @return int
     */
    public function table4_2Operations(Equipment $equipment): int
    {
        $filter = $this->table4_2GetFilter($equipment, false);

        return $equipment->histories()
            ->setFilter($filter)
            ->count();
    }

    /**
     * @param Equipment $equipment
     * @return int
     */
    public function table4_2Plans(Equipment $equipment): int
    {
        $filter = $this->table4_2GetFilter($equipment, true);

        return $equipment->histories()
            ->setFilter($filter)
            ->count();
    }

    public function table4_2EquipmentTimeOperationPlanPlan(Equipment $equipment): string
    {
        $filter = $this->table4_2GetFilterPlan($equipment, true);
        $times = $this->table4_2OperationsTimesObject($filter);		
        return $this->table2_1GetStringTimeByObject($times);
    }

    public function table4_2EquipmentTimeOperationPlanFact(Equipment $equipment): string
    {
        $filter = $this->table4_2GetFilter($equipment, true);
        $historyTimes = $this->table2_1HistoryTimesObject($filter);		
        return $this->table2_1GetStringTimeByObject($historyTimes);
    }

    public function table4_2EquipmentTimeOperationPlan(Equipment $equipment): string
    {
        $filter = $this->table4_2GetFilterPlan($equipment, false);
        $historyTimes = $this->table4_2OperationsTimesObject($filter);		
        return $this->table2_1GetStringTimeByObject($historyTimes);
    }

    public function table4_2EquipmentTimeOperationFact(Equipment $equipment): string
    {
        $filter = $this->table4_2GetFilter($equipment, false);
        $historyTimes = $this->table2_1HistoryTimesObject($filter);		
        return $this->table2_1GetStringTimeByObject($historyTimes);
    }

  
	private function table4_2GetFilterPlan(Equipment $equipment, bool $isPlan): array
    {
        $equipmentsIds = [$equipment->ID];
        foreach($equipment->allChildren() as $child) {
            $equipmentsIds[] = $child->ID;
        }

		$filterDateProcess = [
                '>=DATE' => $this->dateFrom,
				'<=DATE' => $this->dateTo,
                'STAGE' => DateProcess::STAGE_REPORT_DONE
         ];
        
        $dateProcesses = DateProcess::filter($filterStops)->get();
		if (!count($dateProcesses)) return ["ID" => FALSE];

		$dateProcessIds = [];
        foreach($dateProcesses as $dateProcess){
			$dateProcessIds[] = $dateProcess->ID;
        }
				
	   $filter = [
            'EQUIPMENT_ID' => $equipmentsIds,
            'RESULT' => 'Y',
		    'DATE_PROCESS_ID' => $dateProcessIds,
        ];

        if($isPlan) {
            $filter['!PLAN_ID'] = false;
        } else {
            $filter['PLAN_ID'] = false;
        }

  		return $filter;
    }
	
	
	/**
     * @param string $reason
     * @param Service|null $service
     * @return array
     */
    private function table4_2GetFilter(Equipment $equipment, bool $isPlan): array
    {
        $equipmentsIds = [$equipment->ID];
        foreach($equipment->allChildren() as $child) {
            $equipmentsIds[] = $child->ID;
        }
        $filter = [
            'EQUIPMENT_ID' => $equipmentsIds,
            '>=PLANNED_DATE' => $this->dateFrom,
            '<=PLANNED_DATE' => $this->dateTo,
            'RESULT' => 'Y',
        ];

        if($isPlan) {
            $filter['!PLAN_ID'] = false;
        } else {
            $filter['PLAN_ID'] = false;
        }

        return $filter;
    }

    /**
     * @param array $filter
     * @return object
     */
    private function table4_2OperationsTimesObject(array $filter): object
    {
        unset($filter['RESULT']);
		
		$operations = Operation::filter($filter)->get();
	
        $minutesCount = 0;
        foreach($operations as $operation) {
            if(!$operation->WORK_TIME) {
                continue;
            }
            [$time1, $time2] = explode(' - ', $operation->WORK_TIME);
            [$hour1, $minute1] = explode(':', $time1);
            [$hour2, $minute2] = explode(':', $time2);
            $m1 = $hour1 * 60 + $minute1;
            $m2 = $hour2 * 60 + $minute2;
            $minutesCount += max($m2 - $m1, 0);
        }

        return $this->getObjectByMinutes($minutesCount);
    }
}