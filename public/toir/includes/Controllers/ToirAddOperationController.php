<?php

class ToirAddOperationController extends ToirController
{
    /**
     * @var Workshop
     */
    public $workshop;

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var string
     */
    public $date;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        if(!$this->workshop) {
            header("Location: /main");
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
        $this->date = $_REQUEST['date'] ?? null;
   }

    /**
     * @return void
     */
    public function step1()
    {
        $reason = null;
        $equipment = null;

        $_SESSION['add_operation_date'] = $this->date;
        if($_REQUEST['service_request']) {
            $_SESSION['add_operation_service_request'] = (int)$_REQUEST['service_request'];
            $serviceRequest = ServiceRequest::findAvailabled($_SESSION['add_operation_service_request']);
            $equipment = $serviceRequest->equipment();
        }
        
        if($_REQUEST['crash']) {
            $_SESSION['add_operation_crash'] = (int)$_REQUEST['crash'];
            $reason = Operation::REASON_CRASH;
        }

        if($_REQUEST['service']) {
            $services = UserToir::current()->availableServices()
                ->setFilter(['ID' => $_REQUEST['service']])
                ->get();
        } else {
            $services = UserToir::current()->availableServices;
        }


        if($_REQUEST['equipment']) {
            $equipment = Equipment::findAvailabled((int)$_REQUEST['equipment']);
        }

        $this->showHeader();
        $this->view('add_operation/step1', compact('services', 'equipment', 'reason'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function step1_save()
    {
        if ($this->validate($_REQUEST)) {
            $operationId = $this->store();

            if($_SESSION['add_operation_service_request']){
                $this->updateServiceRequest($operationId);
            }
            
            header("Location: ?workshop=" . $this->workshop->ID."&step=2&operation=" . $operationId);
        } else {
            $_SESSION['add_operation_errors'] = $this->errors;
            header("Location: ?workshop=" . $this->workshop->ID);
        }
    }

    /**
     * @return void
     */
    public function step2()
    {
        $operation = Operation::findAvailabled((int)$_REQUEST['operation']);
        $line = $operation->line;

        $date1 = currentMonth();
        $date2 = nextMonth();
        $date3 = next2Month();

        $stops = array_merge(
            Stop::getByLineInMonth($operation->LINE_ID, $date1['Y'], $date1['m']),
            Stop::getByLineInMonth($operation->LINE_ID, $date2['Y'], $date2['m']),
            Stop::getByLineInMonth($operation->LINE_ID, $date3['Y'], $date3['m'])
        );

        $this->showHeader();
        $this->view('add_operation/step2', compact('operation', 'line', 'stops'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function delete()
    {
        OperationService::deleteAndDeleteStop((int)$_REQUEST['delete']);
        header("Location: " . $this->getUrlStep1());
    }



    /**
     * @return void
     */
    private function showHeader()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Добавление внеплановой операци");
        require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
        $this->view('add_operation/header');
    }
		
    /**
     * @return void
     */
	public function showFooter()
    {
        unset($_SESSION['add_operation_errors']);
        parent::showFooter();
    }

    /**
     * @param array $request
     *
     * @return bool
     */
	private function validate(array $request): bool
    {
		if(empty($request["equipment"])){
			$this->errors[] = "Выберите Оборудование";
		}
		
		if(empty($request["line"])){
			$this->errors[] = "Выберите Линию";
		}
		
		if(empty($request["NAME"])){
			$this->errors[] = "Укажите название операции";
		}

        if(empty($request["SERVICE"])){
            $this->errors[] = "Выберите службу";
        } else {
            UserToir::current()->checkServiceOrFail($request["SERVICE"]);
        }

		if(empty($request["TYPE_OPERATION"])){
			$this->errors[] = "Выберите тип операции";
		}

		if(empty($request["OWNER"])){
			$this->errors[] = "Введите ответственного";
		}

		if(empty($request["PLANNED_DATE"])){
			$this->errors[] = "Укажите дату выполнения";
		} else {
			for ($i = 1; $i < 4; $i++) {
                if (date("N", mktime(0, 0, 0, date('m'), date('d') - $i, date("Y")))<6){
                    $previousWorkDay = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - $i, date("Y")));
					break;
				}
			}
			$date = date('Y-m-d', strtotime($request["PLANNED_DATE"]));
            if ($date < $previousWorkDay && $request["SERVICE"] && $request["equipment"]) {
                $service = Service::find((int)$request["SERVICE"]);
                $equipment = Equipment::find($request["equipment"]);
                $dateProcess = DateProcessService::getByServiceAndDate($service, $equipment->workshop, $request["PLANNED_DATE"]);
	            if(!$dateProcess) {
                    $this->errors[] = "Если Вы указываете дату в прошлом, то она должна попадать на дату остановки линии";
                } else {
                    if($dateProcess->STAGE == DateProcess::STAGE_REPORT_DONE) {
                        $this->errors[] = "Если Вы указываете дату в прошлом, то она должна попадать на незавершённую остановку линии";
                    }
                }
            }
        }

		if(count($this->errors) > 0) {
			return false;
		} else {
			return true;
		}	
    }

    /**
     * @return int
     */
    private function store(): int
    {
        $equipment = Equipment::find((int)$_REQUEST['equipment']);

        StopService::createIfNotExists($equipment->LINE_ID, strtotime($_REQUEST["PLANNED_DATE"]));

        $service = Service::find($_REQUEST["SERVICE"]);

        $dateProcess = DateProcessService::createIfNotExists($service, $equipment->workshop, $_REQUEST["PLANNED_DATE"]);

        $create = [];
        $create["NAME"] = $_REQUEST["NAME"];
        $create["SERVICE_ID"] = $_REQUEST["SERVICE"];
        $create["TYPE_OPERATION"] = $_REQUEST["TYPE_OPERATION"];
        $create["OWNER"] = $_REQUEST["OWNER"];
        $create["RECOMMENDATION"] = $_REQUEST["RECOMMENDATION"];
        $create["REASON"] = $_REQUEST["REASON"];
        $create["WORKSHOP_ID"] = $equipment->WORKSHOP_ID;
        $create["LINE_ID"] = $equipment->LINE_ID;
        $create["EQUIPMENT_ID"] = $equipment->ID;
        $create["PLANNED_DATE"] = $_REQUEST["PLANNED_DATE"];
        $create["START_DATE"] = $_REQUEST["PLANNED_DATE"];
        $create["DATE_PROCESS_ID"] = $dateProcess->ID;

        if ($_SESSION['add_operation_crash']) {
            $create['CRASH_ID'] = $_SESSION['add_operation_crash'];
        }

        $id = Operation::create($create);
        if($id) {
            TaskService::updateChecklistItems($create["PLANNED_DATE"], intval($create["LINE_ID"]));
        } else {
            die(Operation::lastError());
        }

        return $id;
    }

    /**
     * @return void
     */
    public function getUrlStep1()
    {
        $url = "?workshop=" . $this->workshop->ID;
        if($_SESSION['add_operation_date']) {
            $url .= "&date=" . $_SESSION['add_operation_date'];
        }
        if($_SESSION['add_operation_service_request']) {
            $url .= "&service_request=" . $_SESSION['add_operation_service_request'];
        }
        if($_SESSION['add_operation_crash']) {
            $url .= "&crash=" . $_SESSION['add_operation_crash'];
        }
        return $url;
    }

    /**
     * @param int $operationId
     * @return void
     */
	private function updateServiceRequest(int $operationId)
    {
        $serviceRequest = ServiceRequest::findAvailabled((int)$_SESSION['add_operation_service_request']);
        if($serviceRequest) {
            ServiceRequestService::addOperation($serviceRequest, $operationId);
        }
    }


}