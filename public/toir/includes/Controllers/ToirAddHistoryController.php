<?php

class ToirAddHistoryController extends ToirController
{
    /**
     * @var array
     */
    public $errors = [];

    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return void
     */
    public function step1()
    {
        $serviceRequest = null;
        $equipment = null;
        $date = null;
        $services = UserToir::current()->availableServices;
        if($_REQUEST['service_request']) {
            $_SESSION['add_history_service_request'] = (int)$_REQUEST['service_request'];
            $serviceRequest = ServiceRequest::findAvailabled($_SESSION['add_history_service_request']);
            $equipment = $serviceRequest->equipment;
            $services = [$serviceRequest->service];
            $date = $serviceRequest->DATE_CREATE;
        }


        $this->showHeader();
        $this->view('add_history/step1', compact('equipment', 'service', 'date', 'services', 'serviceRequest'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function step1_save()
    {
        if ($this->validate($_REQUEST)) {
            $operationId = $this->store();

            if($_SESSION['add_history_service_request']){
                $this->updateServiceRequest($operationId);
            }

            header("Location: add_history.php?step=2&operation=" . $operationId);
        } else {
            $_SESSION['add_history_errors'] = $this->errors;
            header("Location: add_history.php");
        }
    }

    /**
     * @return void
     */
    public function step2()
    {
        $operation = History::find((int)$_REQUEST['operation']);
        $line = $operation->line();

        $date1 = currentMonth();
        $date2 = nextMonth();
        $date3 = next2Month();

        $stops = array_merge(
            Stop::getByLineInMonth($operation->LINE_ID, $date1['Y'], $date1['m']),
            Stop::getByLineInMonth($operation->LINE_ID, $date2['Y'], $date2['m']),
            Stop::getByLineInMonth($operation->LINE_ID, $date3['Y'], $date3['m'])
        );

        $this->showHeader();
        $this->view('add_history/step2', compact('operation', 'line', 'stops'));
        $this->showFooter();
    }


    /**
     * @return void
     */
    public function delete()
    {
        $operation = History::findAvailabled((int)$_REQUEST['delete']);
        $operation->delete();
        header("Location: " . $this->getUrlStep1());
    }

    /**
     * @return void
     */
    private function showHeader()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Добавление операции в журнал учета работ");
        require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
        $this->view('add_history/header');
    }
		
    /**
     * @return void
     */
	public function showFooter()
    {
        unset($_SESSION['add_history_errors']);
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
			$date = date('Y-m-d', strtotime($request["PLANNED_DATE"]));
            if ($date > date('Y-m-d')) {
                $this->errors[] = "Дата должна быть не позже сегодняшнего дня";
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
        $equipment = Equipment::findAvailabled((int)$_REQUEST['equipment']);
        $typesOperation = Operation::getEnumList('TYPE_OPERATION');

        UserToir::current()->checkServiceOrFail((int)$_REQUEST["SERVICE"]);

        $create = [];
        $create["NAME"] = $_REQUEST["NAME"];
        $create["SERVICE_ID"] = $_REQUEST["SERVICE"];
        $create["OWNER"] = $_REQUEST["OWNER"];
        $create["COMMENT_NO_RESULT"] = $_REQUEST["COMMENT"];
        $create["TYPE_OPERATION"] = $typesOperation[$_REQUEST["TYPE_OPERATION"]];      
        $create["WORKSHOP_ID"] = $equipment->WORKSHOP_ID;
        $create["LINE_ID"] = $equipment->LINE_ID;
        $create["EQUIPMENT_ID"] = $equipment->ID;
        $create["PLANNED_DATE"] = $_REQUEST["PLANNED_DATE"];
        $create["START_DATE"] = $create["PLANNED_DATE"];
        $create['RESULT'] = 'Y';

        if($_SESSION['add_history_service_request']) {
			$serviceRequest = ServiceRequest::findAvailabled($_SESSION['add_history_service_request']);
            if($serviceRequest->CRASH_ID){
				$crash = Crash::find($serviceRequest->CRASH_ID);
				$create["WORK_TIME"] = $crash->TIME_FROM . " - " . $crash->TIME_TO;
                $create['REASON'] = Operation::REASON_CRASH;
			}
			$source = $serviceRequest->CRASH_ID
                ? History::SOURCE_CRASH . ': ' . $serviceRequest->CRASH_ID
                : History::SOURCE_SERVICE . ': ' . $_SESSION['add_history_service_request'];
            
        } else {
            $source = History::SOURCE_ADD_OPERATION;
        }        

        return HistoryService::create($create, $source);
    }
    
    /**
     * @return void
     */
    public function getUrlStep1()
    {
        $url = "add_history.php";
        if($_SESSION['add_history_service_request']) {
            $url .= "?service_request=" . $_SESSION['add_history_service_request'];
        }
        return $url;
    }

    /**
     * @param int $operationId
     * @return void
     */
	private function updateServiceRequest(int $operationId)
    {
        $serviceRequest = ServiceRequest::findAvailabled($_SESSION['add_history_service_request']);
        if($serviceRequest) {
            ServiceRequestService::addOperation($serviceRequest, $operationId);

            if($serviceRequest->CRASH_ID) {
                $crash = $serviceRequest->crash;
                $crash->STATUS = max($crash->STATUS, Crash::STATUS_OPERATIONS);
                $crash->save();
            }
        }
    }

}