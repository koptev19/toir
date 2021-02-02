<?php

class ToirAddOperationGroupController extends ToirController
{

    /**
     * @var Service
     */
    public $service;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $source;

    /**
     * @var string
     */
    public $sessionKey;

    /**
     * @var ToirModel
     */
    public $sourceModel;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->service = Service::find((int)$_REQUEST['service']);
        if(!$this->service) {
            die('Не задана служба');
        }
        UserToir::current()->checkServiceOrFail($this->service->ID);
        $this->setPropsBySource();
    }

    /**
     * @return void
     */
    public function index()
    {
        $operations = $_SESSION[$this->sessionKey] ?? null;

        $this->showHeader();
        $this->view('add_operation_group/index', compact('operations'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function save()
    {
        // Источник запуска - Главная страница График ТОиР, Планирование, Отчет, Заявка на ремонт
        if(in_array($this->source, [Operation::SOURCE_GROUP_INDEX, 
									Operation::SOURCE_GROUP_PLAN_DATE, 
									Operation::SOURCE_GROUP_REPORT_DATE, Operation::SOURCE_GROUP_SERVICE_REQUIEST
									])) {
            $reason = Operation::REASON_VIEW;
        }

        // Источник запуска - Простой
		if(in_array($this->source, [Operation::SOURCE_GROUP_DOWNTIME])) {
            $reason = Operation::REASON_DOWNTIME;
        }
		
        // Источник запуска - Авария
        if(in_array($this->source, [Operation::SOURCE_GROUP_CRASH])) {
            $reason = Operation::REASON_CRASH;
        }

        $_SESSION[$this->sessionKey] = [];
        foreach($_REQUEST['NAME'] as $operationId => $name) {
            if(empty($name)) {
                continue;
            }
            
            $_SESSION[$this->sessionKey][$operationId] = [
                'ID' => $operationId,
                'SERVICE_ID' => $this->service->ID,
                'EQUIPMENT_ID' => $_REQUEST['equipment'][$operationId],
                'WORK_ID' => $_REQUEST['WORK_ID'][$operationId],
                'NAME' => $_REQUEST['NAME'][$operationId],
                'RECOMMENDATION' => $_REQUEST['RECOMMENDATION'][$operationId],
                'TYPE_OPERATION_ENUM' => $_REQUEST['TYPE_OPERATION'][$operationId],
                'PLANNED_DATE' => $_REQUEST['PLANNED_DATE'][$operationId] ?? $this->date,
                'REASON' => $reason,
            ];
        }

        // Источник запуска - Главная страница График ТОиР, Заявка на ремонт, Авария
        // Сразу создаем операции
        if(in_array($this->source, [Operation::SOURCE_GROUP_INDEX,																	Operation::SOURCE_GROUP_SERVICE_REQUIEST,														Operation::SOURCE_GROUP_CRASH,
									Operation::SOURCE_GROUP_DOWNTIME])) {
            $addedFields = [];
            if($this->source === Operation::SOURCE_GROUP_CRASH) {
                $addedFields['CRASH_ID'] = $this->sourceModel->ID;
            }

            $addedOperationsIds = OperationService::createGroup($this->sessionKey, $addedFields);

            if($this->source === Operation::SOURCE_GROUP_SERVICE_REQUIEST) {
                foreach($addedOperationsIds as $oldId => $newId) {
                    ServiceRequestService::addOperation($this->sourceModel, $newId);
                }
            }
        }

        $this->openerReloadAndSelfClose();
    }

    /**
     * @return void
     */
    public function newRow()
    {
        $values = $_REQUEST['values'] ?? [];
        $date = $_REQUEST['date'] ?? null;
        
        $this->view('add_operation_group/new_row', compact('values', 'date'));
    }

    /**
     * @return void
     */
    public function getWorks()
    {
        $equipmentId = $_REQUEST['equipment'] ?? 0;
        $id = $_REQUEST['id'] ?? 0;
        $workId = $_REQUEST['workId'] ?? 0;

        if($equipmentId) {
            $works = Work::filter([
                'SERVICE_ID' => $this->service->ID,
                'EQUIPMENT_ID' => $equipmentId,
            ])->get();

            $equipment = Equipment::find((int)$equipmentId);
        } else {
            $works = [];
            $equipment = null;
        }        

        $this->view('add_operation_group/works', compact('works', 'id', 'equipment', 'workId'));
    }

    /**
     * @return void
     */
    private function setPropsBySource()
    {
        $this->source = $_REQUEST['source'] ?? null;

        // Источник запуска - планирование или отчет
        if($this->source == Operation::SOURCE_GROUP_PLAN_DATE || $this->source == Operation::SOURCE_GROUP_REPORT_DATE) {
            if(empty($_REQUEST['date'])) {
                die('Не задана дата');
            }
            $this->date = $_REQUEST['date'];
            $this->sessionKey = 'add_operation_group_' . $this->source . '_' . $this->service->ID . '_' . date('dmY', strtotime($this->date));
        }

        // Источник запуска - Главная страница График ТОиР
        if($this->source == Operation::SOURCE_GROUP_INDEX) {
            $this->sessionKey = 'add_operation_group_' . $this->source;
        }

		// Источник запуска - Журнал простоев
        if($this->source == Operation::SOURCE_GROUP_DOWNTIME) {
            $this->sessionKey = 'add_operation_group_' . $this->source;
			 $this->sourceModel = Downtime::find((int) $_REQUEST['downtime_id']);
        }

        // Источник запуска - Заявка на ремонт
        if($this->source == Operation::SOURCE_GROUP_SERVICE_REQUIEST) {
            if(empty($_REQUEST['service_request'])) {
                die('Не задана заявка на ремонт');
            }
            $this->sourceModel = ServiceRequest::find((int) $_REQUEST['service_request']);
            $this->sessionKey = 'add_operation_group_' . $this->source . '_' . $this->sourceModel->ID;
        }

        // Источник запуска - Авария
        if($this->source == Operation::SOURCE_GROUP_CRASH) {
            if(empty($_REQUEST['crash'])) {
                die('Не задана авария');
            }
            $this->sourceModel = Crash::find((int) $_REQUEST['crash']);
            $this->sessionKey = 'add_operation_group_' . $this->source . '_' . $this->crash->ID;
        }
    }
    
    /**
     * @return void
     */
    private function showHeader()
    {
        $this->view('_header', ['title' => 'Добавление операций']);
    }

}