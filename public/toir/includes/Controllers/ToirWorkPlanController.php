<?php

class ToirWorkPlanController extends ToirController
{

    /**
     * @var string
     */
    public $date;

    /*
     * @return void
     */
    public function __construct()
    {
        $this->date = $_REQUEST['date'];
        if(!$this->date) {
            die('Не задана дата');
        }
    }

    /**
     * @return void
     */
    public function index()
    {
        
		$group = $_REQUEST['groupBy'] ? $_REQUEST['groupBy'] : "WORKSHOP_ID";
		$order = $group == "WORKSHOP_ID" ? "SERVICE_ID" : "WORKSHOP_ID";
		
		
		$workshops =  UserToir::current()->availableWorkshops;
		$services =  UserToir::current()->availableServices;
		 
		$groupBy = $group == "WORKSHOP_ID" ? $workshops : $services;
		$filter= [
            "PLANNED_DATE" => date("Y-m-d",strtotime($this->date)),
            'SERVICE_ID' => UserToir::current()->availableServicesIds,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
        ];
		
		if($_REQUEST['workshop'])
		{
			$filter["WORKSHOP_ID"] = $_REQUEST['workshop'];
			if ($group == "WORKSHOP_ID") {
				$groupBy =[];
				$groupBy[$_REQUEST['workshop']] = $workshops[$_REQUEST['workshop']];
			}

		}
		
		if($_REQUEST['service'])
		{
			$filter["SERVICE_ID"] = $_REQUEST['service'];
			if ($group == "SERVICE_ID") {
				$groupBy =[];
				$groupBy[$_REQUEST['service']] = $services[$_REQUEST['service']];
			}
		}

	
		$operations = Operation::filter($filter)
			->orderBy($order)
			->get();
		
		$operations = $this->modifyOperations($operations);

		foreach($operations as $operation){
				$resOperations[$operation->$group][] = $operation;
		}
		
			
		$this->showHeader();
		
		$this->view('work_plan/filter',[
		'allWorkshops' => $workshops,
        'services' => $services,
		]);	

		$this->view('work_plan/index',['operations'=>$resOperations,'groupBy'=>$groupBy,"order"=>$order]);


    }


	public function printTable()
    {
        
		$group = $_REQUEST['groupBy'] ? $_REQUEST['groupBy'] : "WORKSHOP_ID";
		$order = $group == "WORKSHOP_ID" ? "SERVICE_ID" : "WORKSHOP_ID";
		
		
		$workshops =  UserToir::current()->availableWorkshops;
		$services =  UserToir::current()->availableServices;
		 
		$groupBy = $group == "WORKSHOP_ID" ? $workshops : $services;
		$filter= [
            "PLANNED_DATE" => date("Y-m-d",strtotime($this->date)),
            'SERVICE_ID' => UserToir::current()->availableServicesIds,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
        ];
		
		if($_REQUEST['workshop'])
		{
			$filter["WORKSHOP_ID"] = $_REQUEST['workshop'];
			if ($group == "WORKSHOP_ID") {
				$groupBy =[];
				$groupBy[$_REQUEST['workshop']] = $workshops[$_REQUEST['workshop']];
			}

		}
		
		if($_REQUEST['service'])
		{
			$filter["SERVICE_ID"] = $_REQUEST['service'];
			if ($group == "SERVICE_ID") {
				$groupBy =[];
				$groupBy[$_REQUEST['service']] = $services[$_REQUEST['service']];
			}
		}

	
		$operations = Operation::Filter($filter)
			->orderBy($order)
			->get();
		
		$operations = $this->modifyOperations($operations);
		
		foreach($operations as $operation){
				$resOperations[$operation->$group][] = $operation;
		}
		
			
		$this->view('work_plan/print',['operations'=>$resOperations,'groupBy'=>$groupBy,"order"=>$order]);


    }


	public function WorkersRoutePrint() 
	{
        $operations = [];
		$filter= [
			"PLANNED_DATE" => date("Y-m-d",strtotime($this->date)),
            'SERVICE_ID' => UserToir::current()->availableServicesIds,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
		];
		
		if($_REQUEST['workshop'] && $_REQUEST['filtred'])
		{
			$filter["WORKSHOP_ID"] = $_REQUEST['workshop'];
		}
		
		if($_REQUEST['service'] && $_REQUEST['filtred'])
		{
			$filter["SERVICE_ID"] = $_REQUEST['service'];
		}

        $allOperations = Operation::filter($filter)->get();
		
		foreach($allOperations as $operation) {
			$operations[$operation->ID] = $operation;
			$operationsId[]=$operation->ID;
		}

		$times = Worktime::filter([
				'operation_id' => array_keys($allOperations),
				'action' => Worktime::ACTION_PLAN,
			])
			->orderBy('id', 'asc')
			->get();
		
		$workerIds = [];
		$workersInGroup =[];
		$groups = [];
		foreach($times as $k=>$time){
			if(!$workersInGroup[$time->group][$time->worker_id]){
				$groups[$time->group][] = $time->worker_id;
				$workersInGroup[$time->group][$time->worker_id] = 1;
			}
			$workerIds[]=$time->worker_id;
			$operationsTime[$time->group][$time->operation_id]=$time;
		}

		$workers = Worker::filter(["ID"=>$workerIds])->get();
		$workshop = Workshop::find((int)$_GET['workshop']);
		
		$this->view('work_plan/route_print', [
			'groups' => $groups,
			'workshop'=> $workshop,
			'date' => date("d.m.Y", strtotime($this->date)), 
			'operations' => $operations,
			'operationsTime'=>$operationsTime,   
			'workers'=>$workers,
		]);
	}	

	private function modifyOperations(array $operations): array
    {
        foreach ($operations as $key => $operation)
        {
            $time2 = strtotime($operation->PLANNED_DATE);
            $time1 = mktime(0, 0, 0);
            $operation->difference = ceil(($time2 - $time1) / (60 * 60 * 24));
            if ($operation->LAST_DATE_FROM_CHECKLIST) {
                $operation->difference = 0;
            }

            $operations[$key] = $operation;
        }

        return $operations;
    }	

	private function showHeader()
    {
        
        $this->view('work_plan/header');
    }	


}