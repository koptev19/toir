<?php

class ToirPlanDateCheckingController extends ToirController
{

    /**
     * @var string
     */
    public $date;

    /**
     * @var array
     */
    public $operations;

    /**
     * @var array
     */
    public $dateProcesses;

    /*
     * @return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /");
        }

        $this->date = $_REQUEST['date'];
		if(!$this->date) {
			die('Не задана дата остановки линии');
		}
    }

    /*
     * @return void
     */
    public function index()
    {
        $workshops = Workshop::all();
        $services = Service::all();

        $allOperations = Operation::filter([
            'PLANNED_DATE' => date("Y-m-d", strtotime($this->date)),
        ])->get();

        $operations = [];
        foreach($allOperations as $operation) {
            if($operation->dateProcess->STAGE == DateProcess::STAGE_NEW) {
                continue;
            }
            if(!isset($operations[$operation->LINE_ID])) {
                $operations[$operation->LINE_ID] = [];
            }
            $operations[$operation->LINE_ID][$operation->ID] = $operation;
        }

        $this->view('_header', ['title' => 'Проверка планирования на ' . $this->date]);
        $this->view('plan_date_checking/index', compact('workshops', 'services', 'operations'));
        $this->showFooter();
    }

    /*
     * @return void
     */
    public function approve()
    {
        global $USER;

        $dateProcessId = (int)$_REQUEST['approve'];
        $dateProcess = DateProcess::find($dateProcessId);
        if($dateProcess) {
            $dateProcess->PLAN_APPROVE_ADMIN_ID = UserToir::current()->id;
            $dateProcess->PLAN_APPROVE_DATE = date('Y-m-d H:i:s');
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_APPROVED;
            $dateProcess->save();
        }

        header("Location: ?date=" . $this->date);
    }

    /*
     * @return void
     */
    public function reject()
    {
        $dateProcessId = (int)$_REQUEST['reject'];
        $dateProcess = DateProcess::find($dateProcessId);
        if($dateProcess) {
            $dateProcess->PLAN_REJECT_ADMIN_ID = UserToir::current()->id;
            $dateProcess->PLAN_REJECT_DATE = date('Y-m-d H:i:s');
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_REJECTED;
            $dateProcess->COMMENT_REJECT = $_REQUEST['COMMENT'];
            $dateProcess->save();
        }

        header("Location: ?date=" . $this->date);
    }

    /*
     * @return void
     */
    public function cancel_stage()
    {
        $dateProcessId = (int)$_REQUEST['cancel_stage'];
        $dateProcess = DateProcess::find($dateProcessId);
        if($dateProcess) {
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_DONE;
            $dateProcess->COMMENT_REJECT = '';
            $dateProcess->save();
        }

        header("Location: ?date=" . $this->date);
    }

    /**
     * @param DateProcess $dateProcess
     * @param Line|null $line = null
     * @return string
     */
    public function timeBeginEnd(DateProcess $dateProcess, ?Line $line = null): string
    {
        $timeMinutes = [60 * 24, 0];

        foreach($dateProcess->operations as $operation) {
            if($line && $operation->LINE_ID != $line->ID) {
                continue;
            }
            if(!$operation->WORK_TIME) {
                continue;
            }

            [$begin, $end] = explode('-', $operation->WORK_TIME);
            [$beginH, $beginM] = explode(':', trim($begin));
            [$endH, $endM] = explode(':', trim($end));

            $timeMinutes[0] = min($timeMinutes[0], $beginH * 60 + $beginM);
            $timeMinutes[1] = max($timeMinutes[1], $endH * 60 + $endM);
        }

        return $timeMinutes[1] > 0 
            ? ($timeMinutes[0] < 600 ? '0' : '') . floor($timeMinutes[0] / 60) . ':' . ($timeMinutes[0] % 60 < 10 ? '0' : '') . ($timeMinutes[0] % 60)
                . ' - '
                . ($timeMinutes[1] < 600 ? '0' : '') . floor($timeMinutes[1] / 60) . ':' . ($timeMinutes[1] % 60 < 10 ? '0' : '') . ($timeMinutes[1] % 60)
            : '';
    }

    /**
     * @param DateProcess $dateProcess
     * @param Line|null $line = null
     * @return void
     */
    public function timeDuration(DateProcess $dateProcess, ?Line $line = null): string
    {
        $sumTime =0;
		foreach($dateProcess->operations as $operation) {
            if($line && $operation->LINE_ID != $line->ID) {
                continue;
            }
			
			$operationIds[] = $operation->ID;
	
		}
		
		if(count($operationIds)){
		    $operationTime = $this->getTimesGroup($operationIds);
			return $this->TimeFromArray($operationTime);
		}else{
			return "";	
		}
    }

    public function operationDuration(int $operationId): string
    {
        $operationTime = $this->getTimesGroup([$operationId]);
    
        return $this->TimeFromArray($operationTime);
    }

    /**
     * @param Workshop $workshop
     * @param Line|null $line = null
     * @return void
     */
    public function timeDurationInWorkshop(Workshop $workshop, ?Line $line = null): string
    {
        $dateProcesses = DateProcess::filter([
            'WORKSHOP_ID' => $workshop->ID,
            'DATE' => $this->date,
        ])->get();

        $timeMinutes = [60 * 24, 0];

        foreach ($dateProcesses as $dateProcess) {
           foreach($dateProcess->operations as $operation) {
				if($line && $operation->LINE_ID != $line->ID) {
					continue;
				}
				$operationIds[] = $operation->ID;
		   }	
        }

		if(count($operationIds)){
			$operationTime = $this->getTimesGroup($operationIds);
			return $this->TimeFromArray($operationTime);
		}else{
			return "";	
		}

    }

	private function TimeFromArray(array $times): string 
	{
		$arr=[];
		
		while(true){
    		foreach($times as $time){
    		    $find=0;
    		    foreach($arr as $key=>$time1){
   			    	if( $time[0] < $time1[1] && $time[1] > $time1[0]){
    	 	
						$begin1 = min($time[0], $time1[0]);
    			    	$end1 = max($time[1], $time1[1]);
    			    
    			    	$arr[$key] =[$begin1,$end1];	
    			    	$find=1;
    			    	break;
    			    }   
    		    }	
    		    if(!$find) $arr[]= $time;
    	    }  
			if (count($times) == count($arr)) break;
			$times=$arr;
			$arr=[];
		}
	    
        $sumTime = 0;
		foreach($times as $time){	
				  [$endH, $endM] = explode(':', trim($time[1]));
				  [$beginH, $beginM] = explode(':', trim($time[0]));
				  $sumTime += $endH * 60 + $endM - ($beginH * 60 + $beginM);	 
			}
			
		return floor($sumTime / 60) . ' ч. ' . ($sumTime % 60) . ' м.';	 
	    
	}

    private function getTimesGroup(array $operationsId)
    {
        $allTimes = Worktime::filter(['operation_id' => $operationsId])
            ->orderBy('time_from', 'asc')
            ->get();

        $times = [];
		$return =[];

        foreach($allTimes as $time) {
            if(!isset($times[$time->operatiopn_id][$time->group])) {
                $times[$time->operatiopn_id][$time->group] = 1;
				$return[] = [
                    $time->time_from, 
                    $time->time_to
				];
			}

	      }

        return $return;
    }

   

}