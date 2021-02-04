<?php

class ToirWorkPlannedController extends ToirController
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
        $operationFilter = $this->getOperationsFilter($filter);
        $this->view('_header', ['title' => 'Журнал плановых операций']);
        $this->view('work_planned_log/index', [
           'filter' => $filter
        ]);
    }

	public function getChildren(){
		
		if($_REQUEST['id']){
			$childrenRes = Equipment::filter(['PARENT_ID'=>$_REQUEST['id']])->get();
		}else{
			$childrenRes = Equipment::filter(['ID'=>UserToir::current()->availableWorkshopsIds()])->get();
		}

		$childId = $children = [];
		
		$class=[Equipment::TYPE_WORKSHOP =>"text-body link-dark",
				Equipment::TYPE_LINE =>"text-danger link-danger",
				Equipment::TYPE_MECHANISM =>"text-primary link-primary",
				Equipment::TYPE_NODE=>"text-success link-success",
				Equipment::TYPE_DETAIL=>"text-info link-info"];

		foreach ($childrenRes as $child){
			$childId[] = $child->ID;
			$children[] =['NAME'=>$child->NAME,
						  'ID'=>$child->ID,	
						  'LEVEL'=>$child->LEVEL,		
						  'HASCHILDREN'=>count(Equipment::filter(['PARENT_ID'=>$child->ID])->get()),
						  'CLASS' =>	$class[$child->TYPE_ENUM]
						  ];
						
		}

		$filter = $_REQUEST['filter'] ?? [];
		$filter['SERVICE_ID'] = UserToir::current()->availableServicesIds();
		if (!$filter['EQUIPMENT_ID']) $filter["EQUIPMENT_ID"] = $childId;
		$operationFilter = $this->getOperationsFilter($filter);

	  	$planned = $works = [];
		
		if (!$filter['PERIODICITY']){
            unset($operationFilter['PERIODICITY']);
            unset($operationFilter['TYPE_OPERATION']);
			$works = Work::filter($operationFilter)
		        ->orderBy('WORKSHOP_ID', 'asc')
		        ->orderBy('LINE_ID', 'asc')
		        ->orderBy('EQUIPMENT_ID', 'asc')
				->get();
		}
		
		
		if (!$filter['NO_PERIODICITY']){
		$planned = Plan::filter($operationFilter)
		        ->orderBy('WORKSHOP_ID', 'asc')
		        ->orderBy('LINE_ID', 'asc')
		        ->orderBy('EQUIPMENT_ID', 'asc')
            ->get();
		}
		
		
		$operationsAr = array_merge($planned,$works);
		$type = Operation::getTypes();

		foreach($operationsAr as $op){
			$operations[$op->EQUIPMENT_ID][] = ['ID'=>$op->ID,
												'NAME'=>$op->NAME,
												'PERIODICITY'=>$op->PERIODICITY,
												'RECOMMENDATION'=>$op->RECOMMENDATION,
												'TYPE' => Operation::getVerbalType($op->TYPE_OPERATION)
												];
			$workshopid[] = $op->WORKSHOP_ID;

		}
		echo json_encode(['operations'=>$operations,'children'=>$children]);
	}
    /**
     * @param mixed $historyFilter
     * @param mixed $filter
     * 
     * @return mixed
     */
    private function getOperationsFilter($filter)
    {
        $historyFilter = [];
        if($filter['PLANNED_DATE_FROM']) {
            $historyFilter['>PLANNED_DATE'] = date("Y-m-d", strtotime($filter['PLANNED_DATE_FROM']) - 1);
        }
        if($filter['PLANNED_DATE_TO']) {
            $historyFilter['<PLANNED_DATE'] = date("Y-m-d", strtotime($filter['PLANNED_DATE_TO']) + 60 * 60 * 24);
        }
        foreach(['PERIODICITY','%NAME' ] as $prop) {
            if($filter[$prop]) {
                $historyFilter[$prop] = $filter[$prop];
            }
        }

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