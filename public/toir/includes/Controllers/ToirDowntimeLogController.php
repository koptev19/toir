<?php

class ToirDowntimeLogController extends ToirController
{

    /**
     * @var Workshop
     */
    public $workshop;

    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return void
     */
    public function index()
    {
        $filter = $_REQUEST['filter'] ?? [];
	
		$downFilter = $this->getDowntimesFilter($filter);
        
		$downtimes = Downtime::filter($downFilter)
		        ->orderBy('WORKSHOP_ID', 'LINE_ID','EQUIPMENT_ID')
				->get();
		
		$this->view('log_downtime/header');
        $this->view('log_downtime/index', [
           'filter' => $downFilter,'downtimes' => $downtimes
        ]);

    }


	public function confirmService()
    {
        $downtime = Downtime::find($_REQUEST['id']);
		$downtime -> STAGE = Downtime::STAGE_SERVICE;
		$downtime->save();

		header("location:log_downtime.php?");
	}	
	
	public function changeService()
    {
       	dump($_REQUEST);
		$downtime = Downtime::find($_REQUEST['id']);
		$downtime -> SERVICE_ID = $_REQUEST['service_id'];
		$downtime -> STAGE = Downtime::STAGE_SERVICE;
		$downtime->save();

		header("location:log_downtime.php?");
	}	

	public function changeEquipment()
    {
        $downtime = Downtime::find($_REQUEST['id']);
		$equipment = Equipment ::find($_REQUEST['EQUIPMENT_ID']);
		
		$downtime -> EQUIPMENT_ID = $_REQUEST['EQUIPMENT_ID'];
		$downtime -> WORKSHOP_ID = $equipment -> WORKSHOP_ID;
		$downtime -> LINE_ID = $equipment -> LINE_ID;
		$downtime -> STAGE = Downtime::STAGE_EQUIPMENT;
		
		$downtime->save();

		header("location:log_downtime.php?");
	}	

	public function changeComment()
    {
        $downtime = Downtime::find($_REQUEST['id']);
		$downtime -> COMMENT_SERVICE = $_REQUEST['COMMENT_SERVICE'];
		$downtime -> STAGE = Downtime::STAGE_COMMENT;
		$downtime->save();

		header("location:log_downtime.php?");
	}	

	public function done()
    {
		$downtime = Downtime::find($_REQUEST['id']);
		$downtime -> STAGE = Downtime::STAGE_DONE;
		$downtime->save();
		header("location:log_downtime.php?");
	}	

	public function stageOperations()
    {
		$downtime = Downtime::find($_REQUEST['id']);
		$downtime -> STAGE = Downtime::STAGE_OPERATIONS;
		$downtime->save();
		header("location:log_downtime.php?");
	}	


    /**
     * @param mixed $historyFilter
     * @param mixed $filter
     * 
     * @return mixed
     */
    private function getDowntimesFilter($filter)
    {
        //$filter['WORKSHOP_ID'] =UserToir::current()->availableWorkshopsIds();

		$historyFilter['<STAGE'] = Downtime::STAGE_DONE;

		if($filter['SHOW_ALL']){
			unset($historyFilter['<STAGE']); 
		}
		
		if($filter['WORKSHOP_ID']) {
            $historyFilter['WORKSHOP_ID'] = $filter['WORKSHOP_ID'];
        }
		
		if($filter['DATE_FROM']) {
            $historyFilter['>DATE'] = date("Y-m-d", strtotime($filter['DATE_FROM']) - 1);
        }
        
		if($filter['DATE_TO']) {
            $historyFilter['<DATE'] = date("Y-m-d", strtotime($filter['DATE_TO']) + 60 * 60 * 24);
        }
        
		foreach(['PERIODICITY','%MACHINE','%MASTER' ] as $prop) {
            if($filter[$prop]) {
                $historyFilter[$prop] = $filter[$prop];
            }
        }

		/*if($filter['SERVICE_ID']){
			$historyFilter['SERVICE_ID'] = 	$filter['SERVICE_ID'];
		}else{
			$historyFilter['SERVICE_ID'] = UserToir::current()->availableServicesIds();
		}*/

		if($filter['TYPE_OPERATION']){
			$type = Operation::getEnumList('TYPE_OPERATION');
			$historyFilter['TYPE_OPERATION'] = $type[$filter['TYPE_OPERATION']];
			$historyFilter['TYPE'] = $filter['TYPE_OPERATION'];
		}

		
		if($filter['line']) {
            $historyFilter['LINE_ID'] = $filter['line'];
        }
        if($filter['EQUIPMENT_ID']) {
            $historyFilter['EQUIPMENT_ID'] = $filter['EQUIPMENT_ID'];
        }

        

        return array_merge($filter,$historyFilter);
    }




}