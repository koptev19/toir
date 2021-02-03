<?php

class ToirAddPlanController extends ToirController
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
            if($_REQUEST['work_id']){
				$work = Work::find($_REQUEST['work_id']);
				$this->workshop = $work -> WORKSHOP_ID; 		
			}else{		
            header("Location: /main");
        }
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
        $this->date = $_REQUEST['date'] ?? null;
    }

    /**
     * @return void
     */
    public function copyFromWork(){

		unset($_SESSION['add_plan_data']);
        unset($_SESSION['add_plan_dates']);
		unset($_SESSION['copy_work_id']);
		unset($_SESSION['add_plan_equipment']);
		
		$_SESSION['copy_work_id'] = $_REQUEST['work_id'];
		$_SESSION['add_plan_date'] = $this->date;
        $_SESSION['add_plan_equipment'] = $_REQUEST['equipment'];
		 
		$work = Work::find($_REQUEST['work_id']);
		
		$services = UserToir::current()->availableServices()
			        ->setFilter(['ID' => $work->SERVICE_ID])
				    ->get();

        $equipment = Equipment::findAvailabled($work->EQUIPMENT_ID);
        		
		$this->showHeader();
		$this->view('add_plan/step1', compact('services', 'equipment', 'reason','work'));
        $this->showFooter();
	}
	
	
	/**
     * @return void
     */
    public function step1()
    {
        unset($_SESSION['add_plan_data']);
        unset($_SESSION['add_plan_dates']);
		unset($_SESSION['add_plan_equipment']);

        $_SESSION['add_plan_date'] = $this->date;
        $_SESSION['add_plan_equipment'] = $_REQUEST['equipment'];

        $equipment = null;
        $reason = null;

        if($_REQUEST['service_request']) {
            $_SESSION['add_plan_service_request'] = (int)$_REQUEST['service_request'];
            $serviceRequest = ServiceRequest::findAvailabled($_SESSION['add_plan_service_request']);
            $equipment = $serviceRequest->equipment();
        }

        if($_REQUEST['crash']) {
            $_SESSION['add_plan_crash'] = (int)$_REQUEST['crash'];
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
        $this->view('add_plan/step1', compact('services', 'equipment', 'reason'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function step1_save()
    {
        if ($this->validate($_REQUEST)) {
            $this->saveIntoSession($_REQUEST);
            header("Location: add_plan.php?workshop=" . $this->workshop->ID."&step=2");
        } else {
            $_SESSION['add_plan_errors'] = $this->errors;
            header("Location: add_plan.php?workshop=" . $this->workshop->ID);
        }
    }

    /**
     * @return void
     */
    public function step2()
    {
        $plan = (object)$_SESSION['add_plan_data'];
        $dates = $_SESSION['add_plan_dates'];
        $equipment = Equipment::findAvailabled((int)$plan->EQUIPMENT_ID);
        $line = $equipment->line();

        $date1 = prevMonth();
        $date2 = nextMonth($date1);
        $date3 = next2Month($date1);
        $date4 = nextMonth($date3);

        $stops = array_merge(
            Stop::getByLineInMonth($equipment->LINE_ID, $date1['Y'], $date1['m']),
            Stop::getByLineInMonth($equipment->LINE_ID, $date2['Y'], $date2['m']),
            Stop::getByLineInMonth($equipment->LINE_ID, $date3['Y'], $date3['m']),
            Stop::getByLineInMonth($equipment->LINE_ID, $date4['Y'], $date4['m']),
        );

        $this->showHeader();
        $this->view('add_plan/step2', compact('plan', 'dates', 'line', 'stops'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function step2_save()
    {
        $_SESSION['add_plan_errors'] = [];
        
        if(!empty($_SESSION['add_plan_dates'])) {
            $this->pushAll();

            $this->tryCreateNext();
        }

        $step = count($_SESSION['add_plan_errors']) > 0 ? 2 : 3;
        header("Location: add_plan.php?workshop=" . $this->workshop->ID . '&step=' . $step);
    }

    /**
     * @return void
     */
    public function step3()
    {
        $plan = (object)$_SESSION['add_plan_data'];
        $dates = $_SESSION['add_plan_dates'];
        $equipment = Equipment::findAvailabled((int)$plan->EQUIPMENT_ID);
        $line = $equipment->line();

        $date1 = prevMonth();
        $date2 = nextMonth($date1);
        $date3 = next2Month($date1);
        $date4 = nextMonth($date3);

        $stops = array_merge(
            Stop::getByLineInMonth($equipment->LINE_ID, $date1['Y'], $date1['m']),
            Stop::getByLineInMonth($equipment->LINE_ID, $date2['Y'], $date2['m']),
            Stop::getByLineInMonth($equipment->LINE_ID, $date3['Y'], $date3['m']),
            Stop::getByLineInMonth($equipment->LINE_ID, $date4['Y'], $date4['m']),
        );

        $this->showHeader();
        $this->view('add_plan/step3', compact('plan', 'dates', 'line', 'stops'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function step3_save()
    {
        $plan = $this->storePlan();

        if($plan && !empty($_SESSION['add_plan_dates'])) {
            $this->createOperations($plan);
        }

        if($_SESSION['add_plan_service_request']){
            $this->updateServiceRequest($plan->ID);
        }

        if(empty($_REQUEST['next'])) {
            $this->openerReloadAndSelfClose();            
        } else {
            header("Location: " . $this->getUrlStep1());
        }
    }

    /**
     * @return void
     */
    public function pushToLeft()
    {
        $_SESSION['add_plan_errors'] = [];
        $this->push($_REQUEST['pushToLeft'], 'left');
        header("Location: add_plan.php?workshop=" . $this->workshop->ID . '&step=2');
    }

    /**
     * @return void
     */
    public function pushToRight()
    {
        $_SESSION['add_plan_errors'] = [];
        $this->push($_REQUEST['pushToRight'], 'right');
        header("Location: add_plan.php?workshop=" . $this->workshop->ID . '&step=2');
    }

    /**
     * @return void
     */
    private function showHeader()
    {
        $this->view('_header', ['title' => "Добавление плановой операци"]);
    }
        
    /**
     * @return void
     */
    public function showFooter()
    {
        unset($_SESSION['add_plan_errors']);
        $this->view('_footer');
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
        
        if(empty($request["SERVICE_ID"])){
            $this->errors[] = "Выберите службу";
        } else {
            UserToir::current()->checkServiceOrFail($request["SERVICE_ID"]);
        }

        if(empty($request["TYPE_TO"])){
            $this->errors[] = "Выберите вид ТО";
        }
        
        if ((int)$request["PERIODICITY"] < 1){
            $this->errors[] = "Укажите периодичность";
        }
        
        if(empty($request["NAME"])){
            $this->errors[] = "Укажите название операции";
        }

        if(empty($request["TYPE_OPERATION"])){
            $this->errors[] = "Выберите тип операции";
        }

        if(empty($request["TYPE_OPERATION"])){
            $this->errors[] = "Выберите тип операции";
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
            if ($date < $previousWorkDay && $request["SERVICE_ID"] && $request["equipment"]) {
                $stop = Stop::getByLineDate($request["line"], $date);
	            if(!$stop) {
                    $this->errors[] = "Если Вы указываете дату в прошлом, то она должна попадать на дату остановки линии";
                } else {
                    $service = Service::find((int)$request["SERVICE_ID"]);
                    $equipment = Equipment::find($request["equipment"]);
                    $dateProcess = DateProcessService::getByServiceAndDate($service, $equipment->workshop, $date);
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
     * @param array $request
     *
     * @return void
     */
    private function saveIntoSession(array $request)
    {
        $_SESSION['add_plan_data'] = [
            'EQUIPMENT_ID' => $request['equipment'],
            'TYPE_TO' => $request['TYPE_TO'],
            'PERIODICITY' => $request['PERIODICITY'],
            'NAME' => $request['NAME'],
            'TYPE_OPERATION' => $request['TYPE_OPERATION'],
            'RECOMMENDATION' => $request['RECOMMENDATION'],
            'PLANNED_DATE' => $request['PLANNED_DATE'],
            'SERVICE_ID' => $request['SERVICE_ID'],
            'REASON' => $request['REASON'],
        ];

        $_SESSION['add_plan_dates'] = [];

        $lastStop = Stop::filter(['LINE_ID' => $request['line']])
            ->orderBy('DATE', 'DESC')
            ->first();
        $lastTime = $lastStop 
            ? strtotime(date("Y-m-t", strtotime($lastStop->DATE))) + 60*60*24 
            : strtotime(date('Y-m-t', time() + 60*60*24*30));

        $time = strtotime($request['PLANNED_DATE']);
        while($time < $lastTime) {
            $_SESSION['add_plan_dates'][] = date('Y-m-d', $time);
            $time += $request['PERIODICITY'] * 60 * 60 * 24;
        }
    }

    /**
     * @param string $date
     * @param string $direction
     * @return void
     */
    public function push(string $date, string $direction)
    {
        if($date) {
            $plan = (object)$_SESSION['add_plan_data'];
            $equipment = Equipment::findAvailabled($plan->EQUIPMENT_ID);
            $date = date('Y-m-d', strtotime($date));

            if($direction === 'left') {
                $dateKey = '<DATE';
                $orderBy = 'desc';
            } else {
                $dateKey = '>DATE';
                $orderBy = 'asc';
            }

            $stop = Stop::filter([
                    'LINE_ID' => $equipment->LINE_ID,
                    $dateKey => $date
                ])
                ->orderBy('DATE', $orderBy)
                ->first();

            if($stop) {
                $stopDate = date("Y-m-d", strtotime($stop->DATE));
                if (!in_array($stopDate, $_SESSION['add_plan_dates'])) {
                    foreach($_SESSION['add_plan_dates'] as $key => $sessionDate) {
                        if($sessionDate == $date) {
                            $_SESSION['add_plan_dates'][$key] = $stopDate;
                            break;
                        }
                    }
                } else {
                    $_SESSION['add_plan_errors'][] = 'На дату ' . $stop->DATE . ' не может быть две операции';
                }
            } else {
                $_SESSION['add_plan_errors'][] = 'Нет остановки линии, к которой можно было бы прижать';
            }

        } else {
            $_SESSION['add_plan_errors'][] = 'Не задана дата';
        }
    }

    /**
     * @return void
     */
    private function pushAll()
    {
        $plan = (object)$_SESSION['add_plan_data'];
        $equipment = Equipment::findAvailabled((int)$plan->EQUIPMENT_ID);

        foreach ($_SESSION['add_plan_dates'] as $date) {
            $stop = Stop::getByLineDate($equipment->LINE_ID, $date);
            if (!$stop) {
                $this->push($date, 'left');
            }
        }
    }

    /**
     * @return Plan|null
     */
    private function storePlan(): ?Plan
    {
        $equipment = Equipment::findAvailabled((int)$_SESSION['add_plan_data']['EQUIPMENT_ID']);

        UserToir::current()->checkServiceOrFail($_SESSION['add_plan_data']["SERVICE_ID"]);

        $create = [];
        $create["NAME"] = $_SESSION['add_plan_data']["NAME"];
        $create["TYPE_TO"] = $_SESSION['add_plan_data']["TYPE_TO"];
        $create["START_DATE"] = reset($_SESSION['add_plan_dates']);
        $create["PERIODICITY"] = $_SESSION['add_plan_data']["PERIODICITY"];        
        $create["RECOMMENDATION"] = $_SESSION['add_plan_data']["RECOMMENDATION"];
        $create["TYPE_OPERATION"] = $_SESSION['add_plan_data']["TYPE_OPERATION"];
        $create["SERVICE_ID"] = $_SESSION['add_plan_data']["SERVICE_ID"];
        $create["WORKSHOP_ID"] = $equipment->WORKSHOP_ID;
        $create["LINE_ID"] = $equipment->LINE_ID;
        $create["EQUIPMENT_ID"] = $equipment->ID;
        $create["NEXT_EXECUTION_DATE"] = $create["START_DATE"];
        $create["REASON"] = $_SESSION['add_plan_data']["REASON"];

        if ($_SESSION['add_plan_crash']) {
            $create['CRASH_ID'] = $_SESSION['add_plan_crash'];
        }

        $id = Plan::create($create);
        if ($id) {
			if($_SESSION['copy_work_id']){
				$work = Work::find($_SESSION['copy_work_id']);
				foreach($work->operations as $operation) {
					$operation->WORK_ID = false;
					$operation->save();
				}
				$work->delete();
				unset ($_SESSION['copy_work_id']);
			}	
            return Plan::find($id);
        } else {
            return null;
        }
    }

    /**
     * @param Plan $plan
     * @return void
     */
    private function createOperations(Plan $plan)
    {
        foreach($_SESSION['add_plan_dates'] as $date)
        {
            $dateProcess = DateProcessService::createIfNotExists($plan->service, $plan->workshop, $date);

            $time = strtotime($date);
            OperationService::createByPlan($plan, $time, ['DATE_PROCESS_ID' => $dateProcess->ID]);
        }        
    }

    /**
     * @return void
     */
    public function getUrlStep1()
    {
        $url = "?workshop=" . $this->workshop->ID;
        if($_SESSION['add_plan_date']) {
            $url .= "&date=" . $_SESSION['add_plan_date'];
        }
        if($_SESSION['add_plan_equipment']) {
            $url .= "&equipment=" . $_SESSION['add_plan_equipment'];
        }
        if($_SESSION['add_plan_service_request']) {
            $url .= "&service_request=" . $_SESSION['add_plan_service_request'];
        }
        if($_SESSION['add_plan_crash']) {
            $url .= "&crash=" . $_SESSION['add_plan_crash'];
        }
		
		if($_SESSION['copy_work_id']) {
            $url .= "&action=copyFromWork&work_id=" . $_SESSION['copy_work_id'];
        }

        return $url;
    }

    /**
     * @return int
     */
    public function maxMonth(): int
    {
        return date('n') < 20 ? 2 : 3;;
    }

    /**
     * @param int $operationId
     * @return void
     */
    private function updateServiceRequest(int $operationId)
    {
        $serviceRequest = ServiceRequest::findAvailabled((int)$_SESSION['add_plan_service_request']);
        if($serviceRequest) {
            ServiceRequestService::addOperation($serviceRequest, $operationId);
        }
    }

    /**
     * @return void
     */
    private function tryCreateNext()
    {
        if(!empty($_SESSION['add_plan_dates'])) {
            $plan = (object)$_SESSION['add_plan_data'];

            $lastDate = end($_SESSION['add_plan_dates']);

            $nextTime = strtotime($lastDate) + $plan->PERIODICITY * 60 * 60 * 24;
            if(date('Y-m', $nextTime) == date("Y-m", strtotime($lastDate))) {
                $_SESSION['add_plan_dates'][] = date('Y-m-d', $nextTime);
            }
        }
    }

}