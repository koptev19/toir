<?php


class ToirTable2Controller extends ToirController
{
    private $workshop;

    public function __construct()
    {
        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        if(!$this->workshop) {
            die('Не задан цех');
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
    }

    public function notDone()
    {
        $filter = $_REQUEST['filter'] ?? [];

        $operations = array_merge(PlanService::getNotDone($this->workshop, $filter), OperationService::getNotDone($this->workshop, $filter));

        foreach($operations as $key => $operation) {
            if(is_a($operation, Plan::class)) {
                $ownerUser = UserService::getById($operation->OWNER);
                $operation->owner = $ownerUser['NAME'].' '.$ownerUser['LAST_NAME'];
                $operation->nextExecutionDate = $operation->NEXT_EXECUTION_DATE;
                $operation->late = $operation->getLate();
                $operation->status = $operation->TASK_RESULT;
            } else {
                $operation->owner = $operation->OWNER;
                $operation->nextExecutionDate = date("d.m.Y", strtotime($operation->PLANNED_DATE));
                $operation->status = $operation->LAST_DATE_FROM_CHECKLIST ? 'Y' : 'N';
                $operation->late = $operation->getLate();
            }
            $operations[$key] = $operation;
        }

        $this->view('index/table2_readonly', [
            'title' => 'Не выполненные операции',
            'operations' => $operations,
        ]);
    }


 
}