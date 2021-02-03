<?php

use Bitrix\Main\UI\Extension;

class ToirTable3Controller extends ToirController
{
    public $dateRequest;
    public $date;
    public $workshop;
    public $registry;
    public $registries;

    public function __construct()
    {
        $this->dateRequest = $_REQUEST['date'];
        $this->date = date("Y-m-d", strtotime($this->dateRequest));
        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        if(!$this->workshop) {
            header("Location: /main");
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
        $this->registries = Registry::all();
        $this->registry = $_REQUEST['registry'] ? Registry::find(intval($_REQUEST['registry'])) : reset($this->registries);
        $this->getRequestFilter();
   }

    public function index()
    {
        if($_REQUEST['header']) {
            $this->showHeader($this->dateRequest);
        }

        $operations = $this->workshop->operations()
            ->setFilter(["=PLANNED_DATE" => $this->date])
            ->get();
        
        $operations = $this->modifyOperations($operations);

        $this->view('index/table3', [
            'date' => $this->dateRequest, 
            'operations' => $operations,
            'canPrint' => true,
            'title' => "План работ на день профилактики " . $this->dateRequest,
        ]);

        if($_REQUEST['header']) {
            $this->showFooter();
        }
    }

	public function WorkersRoutePrint() 
	{
        $operations = [];
        $allOperations = Operation::filter([
            "WORKSHOP_ID" => $this->workshop->ID,
            "=PLANNED_DATE" => $this->date,
        ])->get();
		
		foreach($allOperations as $operation) {
			$operations[$operation->ID] = $operation;
			$operationsId[]=$operation->ID;
		}
				
		$resArr=HighloadBlockService::getList(HIGHLOAD_TIME_BLOCK_ID, ["UF_OPERATIONID"=>$operationsId], ['ID' => 'ASC']); 
		foreach($resArr as $k=>$time){
			$workerId[]=$time["UF_WORKERID"];
			$operationsTime[$time["UF_WORKERID"]][]=$time;
		}
		
		$workers=HighloadBlockService::getList(HIGHLOAD_WORKER_BLOCK_ID, ["ID"=>$workerId], ['ID' => 'ASC']); 
		$workshop = Workshop::find((int)$_GET['workshop']);
			
	
		$this->view('index/table3_route_print', [
			'workshop'=> $workshop,
			'date' => date("d.m.Y", strtotime($this->date)), 
			'operations' => $operations,
			'operationsTime'=>$operationsTime,   
			'workers'=>$workers,
		]);
	}
	
	
	public function tableToPdf()
    {
        $allOperations = $this->workshop->operations()
            ->setFilter(["=PLANNED_DATE" => $this->date])
            ->get();

        $operations = [];
        foreach($allOperations as $operation) {
            $keyIsPlan = $operation->PLAN_ID ? 1 : 0;
            $operations[$operation->SERVICE_ID][$keyIsPlan][] = $operation;
        }

        $this->view('index/table3_print', [
            'date' => date("d.m.Y", strtotime($this->date)), 
            'operations' => $operations,
            'services' => UserToir::current()->availableServices,
        ]);
        
	}

    public function notPush()
    {
        $year = (int)$_REQUEST['year'];
        $month = (int)$_REQUEST['month'];
        $nextMonth = $month < 12 ? $month + 1 : 1;
        $nextYear = $month < 12 ? $year : $year + 1;

        $operations = [];

        foreach($this->workshop->lines as $line) {
            $stoppedDates = array_merge(
                Stop::getByLineInMonth($line->ID, $year, $month),
                Stop::getByLineInMonth($line->ID, $nextYear, $nextMonth)
            );

            $filter = [
                ">=PLANNED_DATE" => date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)),
                "<=PLANNED_DATE" => date("Y-m-t", mktime(0, 0, 0, $nextMonth, 1, $nextYear)),
            ];

            $operationsTemp = $line->operations()
                ->setFilter($filter)
                ->get();
                
            foreach($operationsTemp as $operation) {
                if(!isset($stoppedDates[$operation->PLANNED_DATE])) {
                    $operations[] = $operation;
                }
            }
        }

        $operations = $this->modifyOperations($operations);

        $this->view('index/table3', [
            'operations' => $operations,
            'date' => date('Y-m-d', mktime(0,0,0, $month, 1, $year)),
            'title' => 'Реестр неприжатых операций',
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

    private function getRequestFilter()
    {
        $this->filter = [
            'EQUIPMENT_ID' => (int)$_REQUEST['filter_mechanism_id'],
            '%NAME' => $_REQUEST['filter_name'],
        ];
    }

     private function showHeader()
    {
        global $APPLICATION;
        Extension::load('ui.bootstrap4');
        $APPLICATION->SetTitle("План работ на день профилактики " . $this->dateRequest);
        require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
        $APPLICATION->AddHeadScript(TOIR_PATH . "scripts/index.js");
        $this->view('index/table3_header');
    }
}