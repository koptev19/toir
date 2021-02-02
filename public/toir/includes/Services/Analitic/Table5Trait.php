<?php

trait Table5Trait
{

    public function table5DoneHistories(Service $service): object
    {
        $filter = [
            '>=PLANNED_DATE' => $this->dateFrom,
            '<=PLANNED_DATE' => $this->dateTo,
            'SERVICE_ID' => $service->ID,
        ];

        $histories = History::filter($filter)->get();

        $done = 0;
        $notDone = 0;
        foreach($histories as $history) {
            if($history->RESULT == 'Y') {
                $done++;
            } else {
                $notDone++;
            }
        }

        return (object)compact('done', 'notDone');
    }

    public function table5DoneHistoriesAll(): object
    {
        $done = 0;
        $notDone = 0;
        foreach($this->services() as $service) {
            $object = $this->table5DoneHistories($service);
            $done += $object->done;
            $notDone += $object->notDone;
        }

        return (object)compact('done', 'notDone');
    }

	
	public function table5PlanedHistories(?Service $service = null): object
    {
        $filter = [
            '>=DATE' => $this->dateFrom,
            '<=DATE' => $this->dateTo,
        ];
        
        if($service) {
            $filter['SERVICE_ID'] = $service->ID;
        }

        $dateProcesses = dateProcess::filter($filter)->get();

        $done = 0;
        $notDone = 0;
        $workshopsId = [];

        foreach($dateProcesses as $dateProcess) {
		  	   
		   if ($dateProcess->PLAN_DONE){ 
			   if ((strtotime($dateProcess->DATE." 14:59:59") - strtotime($dateProcess->PLAN_DONE))/60/60/24 > 1){
				$done++;
			  }else{
				$notDone++;
				$workshopsId[] = $dateProcess->WORKSHOP_ID;
				$expiredComment[] = ["workshop"=>$dateProcess->WORKSHOP_ID, "date"=>$dateProcess->DATE, "comment" => $dateProcess->COMMENT_EXPIRED];
			  }	
		  }					
		}

		$workshops =[];
		if (count($workshopsId)){
			$workshops = Workshop::filter(["ID"=>$workshopsId])->get();
		}
		return (object)compact('done', 'notDone', 'expiredComment', 'workshops');
    }

	public function table5ReportedHistories(?Service $service = null): object
    {
        $filter = [
            '>=DATE' => $this->dateFrom,
            '<=DATE' => $this->dateTo,
		];

        if($service) {
            $filter['SERVICE_ID'] = $service->ID;
        }

        $dateProcesses = dateProcess::filter($filter)->get();
	

        $done = 0;
        $notDone = 0;
        $workshopsId = [];
		foreach($dateProcesses as $dateProcess) {
            $isNotDone =false;
			if($dateProcess->REPORT_DONE) {
                $daysBetween=(strtotime($dateProcess->REPORT_DONE)-strtotime($dateProcess->DATE))/60/60/24; 
                if ($daysBetween < 2){
                    $done++;
                }elseif($daysBetween>2 && $daysBetween<4){
                    $dayNumber=date("N", strtotime($dateProcess->DATE));
                    if($dayNumber==5 && $daysBetween<4){

                        $done++;
                    }elseif($dayNumber==6 && $daysBetween<3){
                        $done++;		
                    }else{
						$isNotDone =true;         
                        $notDone++;	
                    }		
                }else{
					$isNotDone =true;         
                    $notDone++;
                }				
            } else {
				$isNotDone =true;         
                $notDone++;
            }
			
			if($isNotDone){
				$workshopsId[] = $dateProcess->WORKSHOP_ID;
				$expiredComment[] = ["workshop"=>$dateProcess->WORKSHOP_ID, "date"=>$dateProcess->DATE, "comment" => $dateProcess->REPORT_COMMENT_EXPIRED];
			}
		}
		
		$workshops =[];
		if (count($workshopsId)){
			$workshops = Workshop::filter(["ID"=>$workshopsId])->get();
		}
		
		return (object)compact('done', 'notDone','expiredComment', 'workshops');
    }

}