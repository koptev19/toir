<?php

class ToirMasterReportDateController extends ToirController
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
    public $cookie_var;

    /**
     * @var string
     */
    public $sessionKey;

    /*
     * @return void
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
                    'STAGE' => DateProcess::STAGE_PLAN_APPROVED,
                ])
                ->get()
            : [];
        if(count($this->dateProcesses) == 0) {
            die('Прохождение отчета по службе "' . $this->service->NAME . '" недоступно');
        }

        $this->cookie_var = 'report_date_' . date('dmY', strtotime($this->date)) . '_'. $this->service->ID;
        $this->sessionKey = 'add_operation_group_' . Operation::SOURCE_GROUP_REPORT_DATE . '_' . $this->service->ID . '_' . date('dmY', strtotime($this->date));
    }

    /*
     * @return void
     */
    public function step1()
    {
		$this->showHeader();
        $this->view('master_report_date/step1', [
            'operationsInLine' => $this->getOperationsInLine(),
            'cookie' => $this->getCookie(),
        ]);
        $this->showFooter();
    }

    /*
     * function step2
     */
    public function step2()
    {
        // Сохранение данных с прошлого шага
        if(isset($_REQUEST['save'])) {
            $this->saveToCookie(1, ['COMMENT', 'day', 'month', 'year', 'stoplinedate', 'done','REPORT_COMMENT_EXPIRED']);
            if($_REQUEST['next'] == 'add_operation_group') {
                header('Location: add_operation_group.php?source=' . Operation::SOURCE_GROUP_REPORT_DATE .'&service=' .$this->service->ID .'&date=' . $this->date);
            } else {
            header('Location: master_report_date.php?step=2&date=' . $this->date . '&service='. $this->service->ID);
            }
            die();
        }

       	$cookie=$this->getCookie();
		$operations =  $this->getOperations($cookie['done'] ?? []);
	
		if(!$cookie['result']){
            $operationsId = [];
			foreach($operations as $operation) {
				$operationsId[] = $operation->ID;
            }
            
            $worktimes = count($operationsId) > 0 ? WorkTime::filter(['operation_id' => $operationsId])->get() : [];
		
            $operationsTime = [];
			$workers = [];
            $workersInGroup = [];
            $workerUn = [];
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
			
			$cookie['workers']=$workers;
			$cookie['result']=json_encode($operationsTime);
		}

		$this->showHeader();
        $this->view('master_report_date/step2', [
            'operationsInLine' => $this->getOperationsInLine($operations),
            'cookie' => $cookie,
        ]);
        $this->showFooter();
    }

    /*
     * function step3
     */
    public function step3()
    {
        // Сохранение данных с прошлого шага
        if(isset($_REQUEST['save'])) {
            $this->saveToCookie(2, ['result','workers','workersNames']);
            header('Location: master_report_date.php?date='.$this->date.'&service='. $this->service->ID.'&step='.( $_REQUEST['stepBack'] ? "1" : "3"));
            die();
        }

        $operationsTime = [];
        $owners = [];
		$cookie=$this->getCookie();
		$times = json_decode($cookie['result'], true);
       
		foreach($times as $workerKey => $operationObjects){
            if(!$operationObjects) {
                continue;
            }
            foreach($operationObjects as $operationId => $time){
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
		
		
        $this->showHeader();
     	$this->view('master_report_date/step3', [
            'operationsInLine' => $this->getOperationsInLine($this->getOperations()),
            'cookie' => $cookie,
			'operationsTime' => $operationsTime,
			'owners' =>  $owners

        ]);
        $this->showFooter();
    }

    /*
     * function step3
     */
    public function step4()
    {
        // Сохранение данных с прошлого шага
        if(isset($_REQUEST['save'])) {
            $this->saveData();
            $this->updateDateProcess();
            $this->deleteCookie();
        }

        $this->openerReloadAndSelfClose();
    }

    /**
     * @return void
     */
    public function deleteInSession()
    {
        unset($_SESSION[$this->sessionKey][$_REQUEST['delete_in_session']]);
        header("Location: ?step=3&date=" . $this->date . "&service=" . $this->service->ID);
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
        header("Location: ?mode=plan&step=3&service=". $this->service->ID . "&date=" . $this->date);
    }



    private function showHeader()
    {
        $this->view('_header',['title' => 'Отчет "План работ на день профилактики" '.d($this->date)]);
    }

    /**
     * @param array|null $done = null
     * @param bool $addInSession = true
     * 
     * @return array[Operation]
     */
    private function getOperations(?array $done = null, bool $addInSession = true): array
    {
		$operations = [];
		
        if(!(is_array($done) && count($done) == 0)) {
            foreach($this->dateProcesses as $dateProcess) {
                $filter = [
                    'WORKSHOP_ID' => $dateProcess->WORKSHOP_ID,
                    'PLANNED_DATE' => date('Y-m-d', strtotime($dateProcess->DATE))
                ];

                if(is_array($done)) {
                    $filter['ID'] = $done;
                }

                $operations = array_merge($operations, Operation::filter($filter)->get());
            }
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
        $cookie1 = json_decode($_SESSION[$this->cookie_var. '1'], true);
        $cookie2 = json_decode($_SESSION[$this->cookie_var. '2'], true);
        $cookie1 = is_array($cookie1) ? $cookie1 : [];
        $cookie2 = is_array($cookie2) ? $cookie2 : [];
        return array_merge($cookie1, $cookie2);
    }

    /**
     * @return void
     */
    private function deleteCookie()
    {
        unset($_SESSION[$this->cookie_var. '1']);
        unset($_SESSION[$this->cookie_var. '2']);
    }

    /**
     * @return void
     */
    private function saveData()
    {
        global $USER;

        $operations = $this->getOperations(null, false);
        $addedOperationsId = OperationService::createGroup($this->sessionKey);
        $cookie = $this->getCookie();
        $operationsStep3 = json_decode($cookie['result']);
       
        // Сохранение времени работ по операциям для каждого исполнителя
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
                        "action" => Worktime::ACTION_REPORT,
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

        // Сохранение отложенных списаний
        if(!empty($_REQUEST['delay_writeoff'])) {
            foreach($_REQUEST['delay_writeoff'] as $delayedOperationId) {
                $id = array_search($delayedOperationId, $addedOperationsId);
                if($id === false) {
                    $id = $delayedOperationId;
                }

                DelayedWriteoff::create([
                    'author_id' => UserToir::current()->id,
                    'OPERATION_ID' => $id,
                    'IS_DONE' => false,
                ]);
            }
        }

        $addedOperations = count($addedOperationsId) > 0 ? Operation::filter(['ID' => array_values($addedOperationsId)])->get() : [];
        $allOperations = array_merge($operations, $addedOperations);

        foreach($allOperations as $operation) {
            $id = array_search($operation->ID, $addedOperationsId);
            if($id === false) {
                $id = $operation->ID;
            }

            // Независимо от выполнения - обновляем комментарий по результату
            $operation->updateCommentNoResult($cookie['COMMENT'][$id]);


            if(!empty($cookie['done']) && in_array($id, $cookie['done'])) {     // Если операция выполнена
                // Ставим отметку о выполнении
                $operation->TASK_RESULT = 'Y';
                $operation->LAST_DATE_FROM_CHECKLIST = date('d.m.Y');
                $operation->save();

                $operation = Operation::find($operation->ID);

                // Вставляем запись в Журнал учета проф. работ
                $historyId = HistoryService::createByOperationDone($operation, History::SOURCE_REPORT_DATE);

                // Обновляем поля: время работы, исполнитель
                $history = History::find($historyId);
                $history->WORK_TIME = $operationsTime[$id][0].' - '.$operationsTime[$id][1];
                $history->OWNER = implode(", ", $owners[$id]);
                $history->save();

                // Обновляем плановую операцию
                if($operation->PLAN_ID) {
                    $this->updatePlannedOperation($operation, true);
                }

                // Обновляем операцию без даты
                if($operation->WORK_ID) {
                    $operation->work->LAST_COMPLETED = date("Y-m-d", strtotime($this->date));
                    $operation->work->save();
                }

                // Обновляем копии этой операции
                $this->updateCopies($operation);
            } else {        //Если операция не выполнена
                $time = mktime(0, 0, 0, $cookie['month'][$id], $cookie['day'][$id], $cookie['year'][$id]);

                // Вносим строку в Журнал учета проф. работ
                HistoryService::createByOperationNotDone($operation, History::SOURCE_REPORT_DATE, $time);

                // Создаем копию текущей операции
                $newFields = [
                    'TASK_RESULT' => 'N',
                    'LAST_DATE_FROM_CHECKLIST' => '',
                ];
                OperationService::copyOperation($operation, $newFields);

                // Установка новой даты операции
                OperationService::updatePlannedDate($operation, $time);

                $operation = Operation::find($operation->ID);

                // Обновляем плановую операцию
                if($operation->PLAN_ID) {
                    $this->updatePlannedOperation($operation, false);
                }

                // Если две по одной плановой, то оставляем одну
                OperationService::checkDoublePlan($operation, $time);

                // Если нужно, то создаем дату остановки
                if(isset($cookie['stoplinedate'][$id])){
                    StopService::createIfNotExists(intval($cookie['stoplinedate'][$id]), $time);
                }
            }
        }
    }

    /**
     * @return void
     */
    private function updateDateProcess()
    {
        global $USER;
        $cookie = $this->getCookie();       
        foreach($this->dateProcesses as $dateProcess) {
            $dateProcess->REPORT_COMMENT_EXPIRED = $cookie['REPORT_COMMENT_EXPIRED'] ? $cookie['REPORT_COMMENT_EXPIRED'] : "";
			$dateProcess->REPORT_DONE = date('Y-m-d H:i:s');
            $dateProcess->STAGE = DateProcess::STAGE_REPORT_DONE;
            $dateProcess->REPORT_USER_ID = UserToir::current()->id;
            $dateProcess->save();
        }
    }

    /**
     * @param array|null $allOperations = null
     * 
     * @return array
     */
    private function getOperationsInLine(?array $allOperations = null): array
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
     * @param Operation $childOperation
     * @param bool $isDone
     * 
     * @return void
     */
    private function updatePlannedOperation(Operation $childOperation, bool $isDone)
    {
        if($plan = $childOperation->plan) {
            $childOperationNext = $plan->operations()
                ->setFilter(['>PLANNED_DATE' => date('Y-m-d', strtotime($childOperation->PLANNED_DATE))])
                ->orderBy('PLANNED_DATE', 'ASC')
                ->first();

            if($childOperationNext) {
                $nextExecutionDate = $childOperationNext->PLANNED_DATE;
            } else {
                $nextExecutionDate = date("d.m.Y", strtotime($childOperation->PLANNED_DATE) + $plan->PERIODICITY * 24 * 60 * 60);
            }

            $plan->NEXT_EXECUTION_DATE = $nextExecutionDate;
            $plan->TASK_RESULT = $isDone ? 'Y' : 'N';
            $plan->COMMENT_NO_RESULT = $isDone ? '' : $childOperation->COMMENT_NO_RESULT;

            if ($isDone) {
                $plan->LAST_DATE_FROM_CHECKLIST = $childOperation->PLANNED_DATE;
                $operationsNotDone = null;

                $oldOperations = $plan->operations()
                    ->setFilter(['<PLANNED_DATE' => date('Y-m-d', strtotime($childOperation->PLANNED_DATE)), '!TASK_RESULT' => 'Y'])
                    ->get();

                foreach($oldOperations as $oldOperation) {
                    $oldOperation->TASK_RESULT = 'Y';
                    $oldOperation->save();
                }
            } else {
                $operationsNotDone = is_array($plan->OPERATIONS_NOT_DONE) ? $plan->OPERATIONS_NOT_DONE : [];
                $operationsNotDone[] = $childOperation->ID;
            }
            $plan->OPERATIONS_NOT_DONE = json_encode($operationsNotDone);
            $plan->save();
        }
    }

    private function updateCopies(Operation $sourceOperation)
    {
        foreach($sourceOperation->copies as $copyOperation) {
            $copyOperation->TASK_RESULT = 'Y';
            $copyOperation->save();
        }
    }

}