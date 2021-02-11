<?php

class ToirInstantWriteoffController extends ToirController
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
     }
    
    /**
     * @return void
     */
    public function index()
    {
        $operations = $_SESSION[$this->sessionKey] ?? null;

        $this->showHeader();
        $this->view('instant_writeoff/index', compact('operations'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function save()
    {
       
		$_SESSION['instantWriteoff'] = [];
        foreach($_REQUEST['NAME'] as $operationId => $name) {
            if(empty($name)) {
                continue;
            }

            $_SESSION['instantWriteoff'][$operationId] = [
                'ID' => $operationId,
                'SERVICE_ID' => $this->service->ID,
                'EQUIPMENT_ID' => $_REQUEST['equipment'][$operationId],
                'WORK_ID' => $_REQUEST['WORK_ID'][$operationId],
                'NAME' => $_REQUEST['NAME'][$operationId],
                'TYPE_OPERATION_ENUM' => $_REQUEST['TYPE_OPERATION'][$operationId],
                'PLANNED_DATE' => $_REQUEST['PLANNED_DATE'][$operationId] ?? $this->date,
				
            ];
		}	    
		
		$addedFiels = ['TASK_RESULT' => 'Y', 'LAST_DATE_FROM_CHECKLIST' => date('d.m.Y')];
		$addedOperationsIds = OperationService::createGroup('instantWriteoff', $addedFiels, false);
					 
		foreach($addedOperationsIds as $oldId => $newId){
			$operation =Operation::find($newId);
			HistoryService::createByOperationDone($operation, History::SOURCE_TMC);
		}

		if(!empty($_REQUEST['delay_writeoff'])) {
            foreach($_REQUEST['delay_writeoff'] as $oldId => $value) {
              $id = $addedOperationsIds[$oldId];
                    if($id){
						 DelayedWriteoff::create([
						'NAME' => $GLOBALS['USER']->GetFullName() . ' (' . $id . ')',
						'OPERATION_ID' => $id,
						'IS_DONE' => 0,
						]);
					}
            }
        }
		
		echo json_encode($addedOperationsIds);
		die();	
			
    }

	
	function close(){
		$this->openerReloadAndSelfClose();
	}



	function deleteOperations(){
		dump($_REQUEST['operations']);
		foreach($_REQUEST['operations'] as $oldId => $id) {
			Operation::find((int)$id) ->delete();	
		}	
	}
		

    /**
     * @return void
     */
    public function newRow()
    {
        $values = $_REQUEST['values'] ?? [];
        $date = $_REQUEST['date'] ?? null;
        
        $this->view('instant_writeoff/new_row', compact('values', 'date'));
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

        $this->view('instant_writeoff/works', compact('works', 'id', 'equipment', 'workId'));
    }

       
    /**
     * @return void
     */
    private function showHeader()
    {
        $this->view('_header', ['title' => 'Добавление операций и списания ТМЦ']);
    }

}