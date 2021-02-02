<?php

trait Table2_2Trait
{

    /**
     * @param Service $service
     * @param bool $isPlan
     * @return object
     */
    private function table2_2PlanHistoryTimes(Service $service, bool $isPlan): object
    {
        $filter = [
            '>=PLANNED_DATE' => $this->dateFrom,
            '<=PLANNED_DATE' => $this->dateTo,
            'SERVICE_ID' => $service->ID,
            '!REASON' => Operation::REASON_CRASH,
        ];

        if($isPlan) {
            $filter['!PLAN_ID'] = false;
        } else {
            $filter['PLAN_ID'] = false;
        }

        return $this->table2_1HistoryTimesObject($filter);
    }    

    /**
     * @param Service $service
     * @param bool $isPlan
     * @return object
     */
    private function table2_2CrashHistoryTimes(Service $service, bool $isCrash): object
    {
        $filter = [
            '>=PLANNED_DATE' => $this->dateFrom,
            '<=PLANNED_DATE' => $this->dateTo,
            'SERVICE_ID' => $service->ID,
        ];

        if($isCrash) {
            $filter['REASON'] = Operation::REASON_CRASH;
        } else {
            $filter['!REASON'] = Operation::REASON_CRASH;
        }

        return $this->table2_1HistoryTimesObject($filter);
    }    

    /**
     * @param Service|null $service
     * @param bool $isPlan
     * @return string
     */
    public function table2_2HistoryTimesString(?Service $service, bool $isPlan): string
    {
        if($service) {
            $historyTimes = $this->table2_2PlanHistoryTimes($service, $isPlan);
        } else {
            $historyTimes = (object)[
                'hour' => 0,
                'minute' => 0,
            ];
            foreach($this->services() as $service) {
                $historyTimes1 = $this->table2_2PlanHistoryTimes($service, $isPlan);

                $historyTimes = $this->table2_1SumTimeObject($historyTimes, $historyTimes1);
            }
        }
        return $this->table2_1GetStringTimeByObject($historyTimes);
    }

    public function table2_2CrashHistoryTimesString(?Service $service, bool $isCrash): string
    {
        if($service) {
            $historyTimes = $this->table2_2CrashHistoryTimes($service, $isCrash);
        } else {
            $historyTimes = (object)[
                'hour' => 0,
                'minute' => 0,
            ];
            foreach($this->services() as $service) {
                $historyTimes1 = $this->table2_2CrashHistoryTimes($service, $isCrash);

                $historyTimes = $this->table2_1SumTimeObject($historyTimes, $historyTimes1);
            }
        }
        return $this->table2_1GetStringTimeByObject($historyTimes);
    }

	public function table2_2WorkersHoursString(Workshop $workshop, Line $line = null): string
    {
		$filter = [
			'>=DATE' => $this->dateFrom,
            '<=DATE' => $this->dateTo,
			'STAGE' => DateProcess::STAGE_REPORT_DONE
        ];

        $dateProcesses = DateProcess::filter($filter)
            ->get(); 
        
		if (!count($dateProcesses)) return "";
		
        $dateProcessIds = [];
		foreach($dateProcesses as $dateProcess){
            $dateProcessIds[] = $dateProcess->ID;
        }

		$filterOperations = [
			 'DATE_PROCESS_ID' => $dateProcessIds,
             'WORKSHOP_ID' => $workshop->ID,
        ];

		if($line){
			$filterOperations['LINE_ID'] = $line->ID;
		}

        $operations = Operation::filter($filterOperations)->get();
		
		if (!count($operations)) return "0 час., 0 мин.";
		
		foreach($operations as $operation){
            $operationsId[] = $operation->ID ;
        }
		
		$resArr = HighloadBlockService::getList(HIGHLOAD_REPORT_BLOCK_ID, ["UF_OPERATIONID"=>$operationsId], ['ID' => 'ASC']); 
			
        $sumWorker = 0;
        $sumTime = 0;
		foreach($resArr as $k=>$time){
				$sumTime+= self::sumTime($time["UF_BEGINTIME"], $time["UF_ENDTIME"]);
				$sumWorker++;
		}
	
		$WorkerHour= $sumTime;
		return floor($WorkerHour / 60)." час., ".($WorkerHour % 60)." мин.";
		
	}	

	private static function sumTime($begintime,$endtime){
		$endArr=explode(":",$endtime);
		$beginArr=explode(":",$begintime);
		return ((int)$endArr[0]*60+(int)$endArr[1])-((int)$beginArr[0]*60+(int)$beginArr[1]);
	}

}