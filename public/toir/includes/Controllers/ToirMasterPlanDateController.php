<?php

class ToirMasterPlanDateController extends ToirController
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
     * @var array
     */
    public $dateProcesses;

    /**
     * @var string
     */
    public $mode;

    /**
     * @var string
     */
    public $cookie_var;

    /**
     * @var string
     */
    public $sessionKey;

    /**
     * 
     */
    public function __construct()
    {
		$this->service = Service::find((int)$_REQUEST['service']);
		if(!$this->service) {
			die("Не выбрана служба!");
		}	

		$this->date = $_REQUEST['date'];
		if(!$this->date) {
			die("Не выбрана дата!");
		}	

        $workshopsIds = UserToir::current()->availableWorkshopsIds();

		$this->dateProcesses = count($workshopsIds) > 0 
            ? $this->service->dateProcesses()
                ->setFilter([
                    'DATE' => date("Y-m-d", strtotime($this->date)),
                    'WORKSHOP_ID' => $workshopsIds,
                    'STAGE' => [DateProcess::STAGE_NEW, DateProcess::STAGE_PLAN_REJECTED],
                ])
                ->get()
            : [];
        if(count($this->dateProcesses) == 0) {
            die('Планирование по данной службе не доступно');
        }

        $this->mode = $_REQUEST['mode'] ?? 'plan';
	       
        $this->cookie_var = 'plan_date_' . '_' . $this->service->ID . '_' . date('dmY', strtotime($this->date));
        $this->sessionKey = 'add_operation_group_' . Operation::SOURCE_GROUP_PLAN_DATE . '_' . $this->service->ID . '_' . date('dmY', strtotime($this->date));
    }



/*********************** mode = plan *************************/

    /**
     * 
     */
    public function plan_step1()
    {
		$this->showHeader();
        $this->view('master_plan_date/plan_step1', [
            'operationsInLine' => $this->getOperationsInLine(),
            'typesOperation' => Operation::getTypes(),
            'cookie' => $this->getCookie(),
        ]);
		$this->showFooter();
    }

    /**
     * 
     */
    public function plan_step1_save()
    {
        $this->saveToCookie(1, ['TYPE_OPERATION', 'COMMENT', 'done','COMMENT_EXPIRED']);
        if($_REQUEST['next'] == 'add_operation_group') {
            header('Location: add_operation_group.php?source=' . Operation::SOURCE_GROUP_PLAN_DATE .'&service=' .$this->service->ID .'&date=' . $this->date);
        } else {
	    // Если все отмечены галочкой Выполнено, то переходим на шаг 3
        $step = (isset($_REQUEST['done']) && is_array($_REQUEST['done']) && count($_REQUEST['TYPE_OPERATION']) == count($_REQUEST['done'])) ? 3 : 2;
        header('Location: master_plan_date.php?mode=' . $this->mode . '&step=' . $step . '&service='. $this->service->ID . "&date=" . $this->date);
    }
    }

    /**
     * 
     */
    public function plan_step2()
    {
        $cookie = $this->getCookie();
        $operations = $this->getOperations();
        $operationsNotDone = [];
        foreach($operations as $operation) {
            if(isset($cookie['done']) && is_array($cookie['done']) && in_array($operation->ID, $cookie['done'])) {
                continue;
            }
            $operationsNotDone[] = $operation;
        }

        $this->showHeader();
        $this->view('master_plan_date/plan_step2', [
            'operationsInLine' => $this->getOperationsInLine($operationsNotDone),
            'cookie' => $cookie,
        ]);
        $this->showFooter();
    }

    /**
     * 
     */
    public function plan_step2_save()
    {
        $this->saveToCookie(2, ['COMMENT_NO_RESULT', 'stoplinedate', 'day', 'month', 'year']);
        header('Location: master_plan_date.php?mode=' . $this->mode . '&step=3&service='. $this->service->ID . "&date=" . $this->date);
    }

    /**
     * 
     */
    public function plan_step3()
    {
		$cookie = $this->getCookie();
        $operations = $this->getOperations($cookie['done'] ?? []);

		if(!$cookie['result']){
            $operationsId = [];
			foreach($operations as $operation) {
				$operationsId[] = $operation->ID;
            }
            
            $worktimes = count($operationsId) > 0 ? WorkTime::filter(['operation_id' => $operationsId])->get() : [];
				
			$operationsTime = [];
			$workers =[];
            $workerUn = [];
            $workersInGroup =[];
            foreach($worktimes as $time){
                if(!$workerUn[$time->group]) {
                    $workerUn[$time->group] = count($workerUn) + 1;
                }
                if(!$workersInGroup[$workerUn[$time->group]][$time->worker_id]){
                    $workersInGroup[$workerUn[$time->group]][$time->worker_id] =1;
                    $workers[$workerUn[$time->group]][] = $time->worker_id;
                }
                $operationsTime[$workerUn[$time->group]][$time->operation_id][0] = $time->time_from;
                $operationsTime[$workerUn[$time->group]][$time->operation_id][1] = $time->time_to;	
            }

			
			$cookie['workers'] = $workers;
			$cookie['result'] = json_encode($operationsTime);
		}

        $this->showHeader();

		$this->view('master_plan_date/plan_step3', [
            'operationsInLine' => $this->getOperationsInLine($operations),
            'cookie' => $cookie,
        ]);
		$this->showFooter();
    }

    /**
     * 
     */
    public function plan_step3_save()
    {
		$this->saveToCookie(3, ['result', 'workers','workersNames']);
		header('Location: master_plan_date.php?mode=' . $this->mode . '&service='. $this->service->ID . "&date=" . $this->date. "&step=".($_REQUEST['stepBack']? "2" : "4"));
    }

    /**
     * 
     */
    public function plan_step4()
    {
		$this->showHeader();
        $this->view('master_plan_date/plan_step4', [
            'operationsInLine' => $this->getOperationsInLine(),
            'cookie' => $this->getCookie(),
            'typesOperation' => Operation::getTypes(),
        ]);
        $this->showFooter();
    }

    /**
     * 
     */
    public function plan_step4_save()
    {
        $this->savePlanData();
        $this->updateDateProcesses();
        $this->deleteCookie();
        $this->openerReloadAndSelfClose();
    }

    /**
     * @return void
     */
    public function deleteInSession()
    {
        unset($_SESSION[$this->sessionKey][$_REQUEST['delete_in_session']]);
        header("Location: ?mode=plan&step=4&service=". $this->service->ID . "&date=" . $this->date);
    }

    /**
     * @return void
     */
    public function updateField()
    {
        $field = $_REQUEST['update_field'];
        $operationId = $_REQUEST['operation'];
        $value = $_REQUEST['value'];
        if($field == 'COMMENT' || $field == 'TYPE_OPERATION') {
            $cookie = json_decode($_SESSION[$this->cookie_var. '1'], true);
            $cookie[$field][$operationId] = $value;
            $_SESSION[$this->cookie_var . '1'] = json_encode($cookie);
        }
        if($field == 'NAME') {
            $_SESSION[$this->sessionKey][$operationId][$field] = $value;
        }
        if($field == 'EQUIPMENT_ID') {
            $_SESSION[$this->sessionKey][$operationId][$field] = $value;
        }
        header("Location: ?mode=plan&step=4&service=" . $this->service->ID . "&date=" . $this->date);
    }


/*********************** mode = dates *************************/

    /**
     * 
     */
    public function dates_step1()
    {
        $operations = $this->getOperations(null, false);
        $operationsInLine = $this->getOperationsInLine($operations);

        $this->showHeader();
        $this->view('master_plan_date/dates_step1', [
            'operationsInLine' => $operationsInLine,
            'typesOperation' => Operation::getTypes(),
            'lines' => $this->getLines($operationsInLine),
        ]);
        $this->showFooter();
    }

    /**
     * 
     */
    public function dates_step1_save()
    {
        if (!empty($_REQUEST['operationId']) && !empty($_REQUEST['new_date'])) {
            foreach($_REQUEST['operationId'] as $lineId => $operations) {
                if(empty($_REQUEST['new_date'][$lineId])) {
                    continue;
                }

                foreach($operations as $id) {
                    $operation = Operation::findAvailabled((int)$id);
                    $operation->updateCommentNoResult($_REQUEST['COMMENT']);

                    $operation = Operation::find((int)$id);

                    $oldDate = $operation->PLANNED_DATE;

                    // Установка новой даты операции
                    // Вносим строку в Журнал учета проф. работ
                    // Если нужно, то создаем дату остановки
                    $newTime = strtotime($_REQUEST['new_date'][$lineId]);
                    $this->updatePlannedDate($operation, $newTime, History::SOURCE_CHANGE_DATE);

                    StopService::deleteIfEmpty($operation->LINE_ID, $oldDate);

                    DateProcessService::deleteIfEmpty($operation->service, $operation->workshop, $oldDate);
                }
            }
        }

        if(count($this->getOperations(null, false))) {
            header('Location: master_plan_date.php?mode=plan&step=1&service='. $this->service->ID . '&date='. $this->date);
        } else {
            $this->showHeader();
            $this->view('master_plan_date/dates_step2_empty');
		    $this->showFooter();
        }

    }

    /**
     * 
     */
    public function dates_step2()
    {
        $operations = $this->getOperations(null, false);

        $this->showHeader();
        $this->view('master_plan_date/dates_step2', [
            'operationsInLine' => $this->getOperationsInLine($operations),
            'typesOperation' => Operation::getTypes(),
        ]);
		$this->showFooter();
    }






/*********************** private functions *************************/


    /**
     * 
     */
    private function showHeader()
    {
        if($this->mode == 'plan') {
            $title = "Планирование \"План работ на день профилактики\" ".d($this->date);
        } else {
            $title = "Групповая смена дат у операций";
        }
        $this->view('_header', ['title' => $title]);
		$this->view('master_plan_date/header');
        $this->view('master_plan_date/menu');
    }

    /**
     * @param array $done = null
     * @param bool $addInSession = true
     *
     * @return array[Operation]
     */
    
	private function getOperations(?array $done = null, bool $addInSession = true): array
    {
        $operations = [];
		$cookie = $this->getCookie();		 

        if(!(is_array($done) && count($done) == 0)) {
            $filter = [
				'DATE_PROCESS_ID' => array_keys($this->dateProcesses),
            ];

            if(is_array($done)) {
                $filter['ID'] = $done;
            }

            $operations = Operation::filter($filter)->get();
        }

        if($addInSession) {
            foreach($_SESSION[$this->sessionKey] ?? [] as $operationArray) {
                $operations[$operationArray['ID']] = (object) $operationArray;
            }
        }

		return $operations;
    }

    /**
     * @param int $step
     * @param array $vars
     *
     * @return void
     */
    private function saveToCookie(int $step, array $vars)
    {
        $cookie = [];
        foreach($vars as $var) {
			$cookie[$var] = $_REQUEST[$var] ?? null;
        }

        $_SESSION[$this->cookie_var . $step] = json_encode($cookie);
    }

    /**
     * @return array
     */
    private function getCookie()
    {
        $cookie0 = json_decode($_SESSION[$this->cookie_var. '0'], true);
		$cookie1 = json_decode($_SESSION[$this->cookie_var. '1'], true);
        $cookie2 = json_decode($_SESSION[$this->cookie_var. '2'], true);
        $cookie3 = json_decode($_SESSION[$this->cookie_var. '3'], true);
		$cookie0 = is_array($cookie0) ? $cookie0 : [];
		$cookie1 = is_array($cookie1) ? $cookie1 : [];
        $cookie2 = is_array($cookie2) ? $cookie2 : [];
        $cookie3 = is_array($cookie3) ? $cookie3 : [];
		return array_merge($cookie0,$cookie1, $cookie2, $cookie3);
    }

    /**
     * @return void
     */
    private function deleteCookie()
    {
        unset($_SESSION[$this->cookie_var. '0']);
		unset($_SESSION[$this->cookie_var. '1']);
        unset($_SESSION[$this->cookie_var. '2']);
        unset($_SESSION[$this->cookie_var. '3']);
    }

    /**
     * @return void
     */
    private function savePlanData()
    {
        $operations = $this->getOperations(null, false);
        $addedOperationsId = OperationService::createGroup($this->sessionKey);
        $cookie = $this->getCookie();

        $operationsStep3 = json_decode($cookie['result']);

		foreach($operations as $operation) {
			foreach($operation->planWorktimes as $worktime) {
                $worktime->delete();
            }
        }

        $operationsTime = [];
        $owners = [];
        foreach($operationsStep3 as $workerKey => $operationObjects){
            if(!$operationObjects) {
                continue;
            }
            $group = uniqid();
            foreach($operationObjects as $operationId => $time){
                foreach($cookie['workers'][$workerKey] as $workersId){
                    Worktime::create([
                        "WORKER_ID" => $workersId,
                        "OPERATION_ID" => $addedOperationsId[$operationId] ?? $operationId,
                        "TIME_FROM" => $time[0],
                        "TIME_TO" => $time[1],
                        "action" => Worktime::ACTION_PLAN,
                        "group" => $group,
                    ]);
                }

                if(!$operationsTime[$operationId][0]){
                    $operationsTime[$operationId] = [$time[0], $time[1]];
                } else {
                    $operationsTime[$operationId][0] = min($operationsTime[$operationId][0], $time[0]);
                    $operationsTime[$operationId][1] = max($operationsTime[$operationId][1], $time[1]);
                }
                
                if(!isset($owners[$operationId])) {
                    $owners[$operationId] = [];
                }

                $owners[$operationId][] = $cookie['workersNames'][$workerKey];
          
            }
        }
		
        $addedOperations = count($addedOperationsId) > 0 ? Operation::filter(['ID' => array_values($addedOperationsId)])->get() : [];
        $allOperations = array_merge($operations, $addedOperations);

        foreach($allOperations as $operation) {
            $id = array_search($operation->ID, $addedOperationsId);
            if($id === false) {
                $id = $operation->ID;
            }

            $operation->TYPE_OPERATION = $cookie["TYPE_OPERATION"][$id];
            $operation->COMMENT = $cookie["COMMENT"][$id];
            $operation->save();

            if(!empty($cookie['done']) && in_array($id, $cookie['done'])) {     // Если операция выполнена
                $operation->WORK_TIME = $operationsTime[$id][0].' - '.$operationsTime[$id][1];
                $operation->OWNER = implode(", ", $owners[$id]);
                $operation->save();
            } else {        //Если операция не выполнена
                $operation->updateCommentNoResult($cookie['COMMENT_NO_RESULT'][$id]);

                $operation = Operation::find($id);

                if(isset($cookie['year'][$id])) {
                    // Установка новой даты операции
                    // Вносим строку в Журнал учета проф. работ
                    // Если нужно, то создаем дату остановки
                    $newTime = mktime(0, 0, 0, (int)$cookie['month'][$id], (int)$cookie['day'][$id], (int)$cookie['year'][$id]);
                    $this->updatePlannedDate($operation, $newTime, History::SOURCE_PLAN_DATE);

                    StopService::deleteIfEmpty($operation->LINE_ID, $operation->PLANNED_DATE);

                    DateProcessService::deleteIfEmpty($operation->service, $operation->workshop, $operation->PLANNED_DATE);
                }
            }
        }
    }

    /**
     * @return void
     */
    private function updateDateProcesses()
    {
        global $USER;
		$cookie = $this->getCookie();
        foreach($this->dateProcesses as $dateProcess) {
            $dateProcess->COMMENT_EXPIRED = $cookie['COMMENT_EXPIRED'] ? $cookie['COMMENT_EXPIRED'] : "";
			$dateProcess->PLAN_DONE = date('Y-m-d H:i:s');
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_DONE;
            $dateProcess->PLAN_USER_ID = UserToir::current()->id;
    		$dateProcess->save();
        }
    }

    /**
     * @param array $allOperations = null
     *
     * @return array
     */
    private function getOperationsInLine(array $allOperations = null): array
    {
        if(is_null($allOperations)) {
            $allOperations = $this->getOperations();
        }


        $operationsInLine = [];
        foreach($allOperations as $operation) {
            if(!is_a($operation, Operation::class)) {
                $operation->equipment = Equipment::find($operation->EQUIPMENT_ID);
                $operation->line = $operation->equipment->line;
                $operation->workshop = $operation->equipment->workshop;
            }
            $opName = $operation->line->NAME." (".$operation->workshop->NAME.")";
            if(!isset($operationsInLine[$opName])) {
                $operationsInLine[$opName] = [];
            }

            $operationsInLine[$opName][] = $operation;
        }

        return $operationsInLine;
    }

    /**
     * @param array $allOperations = null
     *
     * @return array
     */
    private function getLines(array $operationsInLine): array
    {
        $lines = [];

        foreach($operationsInLine as $lineName => $operations) {
            $operation = reset($operations);
            $lines[$lineName] = $operation->LINE_ID;
        }            

        return $lines;
    }

    /**
     * @param Operation $operation
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $source
     *
     * @return void
     */
    private function updatePlannedDate(Operation $operation, int $time, string $source)
    {
        // Вносим строку в Журнал учета проф. работ
        HistoryService::createByOperationNotDone($operation, $source, $time);

        // Если нужно, то создаем дату остановки
        StopService::createIfNotExists($operation->LINE_ID, $time);

        // Установка новой даты операции
        OperationService::updatePlannedDate($operation, $time);

        // Если две по одной плановой, то оставляем одну
        OperationService::checkDoublePlan($operation, $time);
    }

}