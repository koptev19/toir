<?php

class ToirHistoryController extends ToirController
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
        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        if(!$this->workshop) {
            header("Location: main.php");
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
    }

    /**
     * @return void
     */
    public function index()
    {
        $limit = (int)$_REQUEST['limit'] > 0 ? (int)$_REQUEST['limit'] : 50;
        $page = (int)$_REQUEST['page'] > 0 ? (int)$_REQUEST['page'] : 1;
        $filter = $_REQUEST['filter'] ?? [];
        $filter['EQUIPMENT_ID'] = $filter['EQUIPMENT_ID'] ?? $this->workshop->ID;

        $historyFilter = [
            'WORKSHOP_ID' => $this->workshop->ID,
            'SERVICE_ID' => UserToir::current()->availableServicesIds(),
        ];

        $historyFilter = $this->getOperationsFilter($historyFilter, $filter);
        $operations = History::filter($historyFilter)
            ->orderBy('PLANNED_DATE', 'DESC')
            ->orderBy('ID', 'DESC')
            ->offset($limit * ($page - 1))
            ->limit($limit)
            ->get();

        $equipmentsId = [];
        foreach($operations as $key => $operation) {
            $equipmentsId[] = $operation->EQUIPMENT_ID;
            $operation->creator = UserService::getById($operation->CREATED_BY);
            $operations[$key] = $operation;
        }

        $this->view('_header', ['title' => 'Журнал учета работ']);
        $this->view('history/index', [
            'operations' => $operations,
            'maxPage' => History::maxPage(),
            'equipments' => Equipment::filter(['ID' => $equipmentsId])->get(),
            'services' => UserToir::current()->availableServices,
            'filter' => $filter
        ]);
        $this->view('_footer');
    }

    /**
     * @param mixed $historyFilter
     * @param mixed $filter
     * 
     * @return mixed
     */
    private function getOperationsFilter(array $historyFilter, array $filter): array
    {
        if($filter['PLANNED_DATE_FROM']) {
            $historyFilter['>PLANNED_DATE'] = date("Y-m-d", strtotime($filter['PLANNED_DATE_FROM']) - 1);
        }
        if($filter['PLANNED_DATE_TO']) {
            $historyFilter['<PLANNED_DATE'] = date("Y-m-d", strtotime($filter['PLANNED_DATE_TO']) + 60 * 60 * 24);
        }
        foreach(['PERIODICITY', 'SERVICE_ID', 'TYPE_OPERATION', 'RESULT', '%NAME', '%COMMENT', '%OWNER'] as $prop) {
            if($filter[$prop]) {
                $historyFilter[$prop] = $filter[$prop];
            }
        }
        if($filter['line']) {
            $historyFilter['LINE_ID'] = $filter['line'];
        }
        if($filter['EQUIPMENT_ID'] && $filter['EQUIPMENT_ID'] != $this->workshop->ID && $filter['EQUIPMENT_ID'] != $filter['line']) {
            $historyFilter['EQUIPMENT_ID'] = [$filter['EQUIPMENT_ID']];
            $equipment = Equipment::find($filter['EQUIPMENT_ID']);
            foreach($equipment->allChildren as $child) {
                $historyFilter['EQUIPMENT_ID'][] = $child->ID;
            }
        }

        if($filter['service_request_id']) {
            $serviceRequest = ServiceRequest::findAvailabled(intval($filter['service_request_id']));

            if($serviceRequest->OPERATIONS) {
                foreach($serviceRequest->OPERATIONS as $id) {
                    $plan = Plan::find($id);
                    if($plan) {
                        $operation = Operation::filter(['PLAN_ID' => $plan->id])
                            ->orderBy('PLANNED_DATE', 'ASC')
                            ->first();
                    } else {
                        $operation = Operation::find($id);
                    }

                    if($operation) {
                        if(empty($historyFilter['OPERATION_ID'])) {
                            $historyFilter['OPERATION_ID'] = [];
                        }
                        $historyFilter['OPERATION_ID'][] = $operation->ID;
                    } else {
                        if(empty($historyFilter['ID'])) {
                            $historyFilter['ID'] = [];
                        }
                        $historyFilter['ID'][] = $id;
                    }
                }
            }
        }

        return $historyFilter;
    }
}