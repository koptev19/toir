<?php

class ToirEditOperationController extends ToirController
{
    /**
     * @var Plan|Operation
     */
    public $operation;

    public function __construct()
    {
        $operationId = (int)$_REQUEST['operation_id'];
        $this->operation = Plan::findAvailabled($operationId);
        if (!$this->operation) {
            $this->operation = Operation::findAvailabled($operationId);
        }
        if(!$this->operation) {
            die('Не задана операция');
        }
    }

    public function step1()
    {
        $this->view('_header', ['title' => 'Редактирование операции']);
        $this->view('edit_operation/step1');
        $this->view('_footer');
    }

    public function step2()
    {
        // Сохранение данных с прошлого шага
        if(isset($_POST['update'])) {
            $this->update($_POST);
            header('Location: edit_operation.php?step=2&operation_id=' . $this->operation->ID);
            die();
        }

        $this->view('_header', ['title' => 'Редактирование операции']);
        $this->view('edit_operation/step2', [
            'stoppedDates' => $this->getStoppedDates(),
            'operations' => $this->getOperations(),
        ]);
        $this->view('_footer');
    }

    public function step3()
    {
        // Прижатие операций
        if(isset($_REQUEST['go'])) {
            $errorDate = $this->pushOperations();
            if($errorDate) {
                header('Location: edit_operation.php?step=2&operation_id=' . $this->operation->ID . "&error_date=" . $errorDate);
            } else {
                header('Location: edit_operation.php?step=3&operation_id=' . $this->operation->ID);
            }
            die();
        }

        $this->view('_header', ['title' => 'Редактирование операции']);
        $this->view('edit_operation/step3', [
            'stoppedDates' => $this->getStoppedDates(),
            'operations' => $this->getOperations(),
        ]);
        $this->view('_footer');
    }

    private function update($post)
    {
        $this->operation->NAME = $post['NAME'];
        $this->operation->RECOMMENDATION = $post['RECOMMENDATION'];
        $this->operation->TYPE_OPERATION = $post['TYPE_OPERATION'];

        if (is_a($this->operation, Plan::class)) {
            $this->operation->TYPE_TO = $post['TYPE_TO'];
        }

        $this->operation->save();

        if (is_a($this->operation, Plan::class)) {
            $this->updatePlan($post);
        }
    }

    private function updatePlan($post)
    {
        // Если есть "Периодичность" и она отличается от того, что есть в базе
        $periodicity = (int)$post["PERIODICITY"];
        if ($periodicity > 0 && $periodicity != $this->operation->PERIODICITY) {
            // Удалить все операции позже сегодняшнего дня
            $oldFutureOperations = $this->operation->operations()
                ->setFilter([">PLANNED_DATE" => date("Y-m-d")])
                ->get();

            foreach($oldFutureOperations as $oldFutureOperation) {
                $oldFutureOperation->delete();
            }

            // Обновляем периодичность у плановой операции
            $this->operation->PERIODICITY = $periodicity;
            $this->operation->save();
            $this->operation = Plan::find($this->operation->ID);

            // Формируем операции в реестр по плановой операции с новой периодичностью
            $month = (int)date('j') < Settings::getValueByName('plan_month_day') ? nextMonth() : next2Month();
            $lastDate = date('Y-m-t 23:59:59', mktime(0, 0, 0, $month['m'], 1, $month['Y']));

            OperationService::createByPlanInRange($this->operation, date("Y-m-d"), $lastDate);
        }
    }

    private function getStoppedDates()
    {
        $date1 = currentMonth();
        $date2 = nextMonth();
        $date3 = next2Month();

        return array_merge(
            Stop::getByLineInMonth($this->operation->LINE_ID, $date1['Y'], $date1['m']),
            Stop::getByLineInMonth($this->operation->LINE_ID, $date2['Y'], $date2['m']),
            Stop::getByLineInMonth($this->operation->LINE_ID, $date3['Y'], $date3['m'])
        );
    }

    private function getOperations()
    {
        if(is_a($this->operation, Plan::class)) {
            $allOperations = $this->operation->operations()
                ->setFilter(['>PLANNED_DATE' => date('Y-m-01')])
                ->get(); 
        } else {
            $allOperations = [];
            $allOperations[] = $this->operation;
        }
        
        $operations = [];
        foreach($allOperations as $op) {
            $operations[$op->PLANNED_DATE] = $op;
        }
        
        return $operations;
    }

    private function pushOperations(): ?string
    {
        $operations = $this->getOperations();
        return PushService::checkAndPush($operations);
    }

        /**
     * @return void
     */
    public function pushToLeft()
    {
        $error = $this->push($_REQUEST['pushToLeft'], 'left');
        header('Location: edit_operation.php?step=2&operation_id=' . $this->operation->ID . ($error ? '&error_date=' . $error : ''));
    }

    /**
     * @return void
     */
    public function pushToRight()
    {
        $error = $this->push($_REQUEST['pushToRight'], 'right');
        header('Location: edit_operation.php?step=2&operation_id=' . $this->operation->ID . ($error ? '&error_date=' . $error : ''));
    }

    /**
     * @param string $date
     * @param string $direction
     * 
     * @return string
     */
    private function push(string $date, string $direction): string
    {
        $error = '';
        if($date) {
            $date = date('Y-m-d', strtotime($date));

            if($direction === 'left') {
                $dateKey = '<DATE';
                $orderBy = 'desc';
            } else {
                $dateKey = '>DATE';
                $orderBy = 'asc';
            }

            $stop = Stop::filter([
                    'LINE_ID' => $this->operation->LINE_ID,
                    $dateKey => $date
                ])
                ->orderBy('DATE', $orderBy)
                ->first();

            if($stop) {
                $stopDate = date("Y-m-d", strtotime($stop->DATE));
                $countPlanOperations = $this->operation->operations()->setFilter(['PLANNED_DATE' => $stopDate])->count();
                if (!$countPlanOperations) {
                    $operation = $this->operation->operations()->setFilter(['PLANNED_DATE' => $date])->first();
                    if($operation) {
                        OperationService::updatePlannedDate($operation, strtotime($stop->DATE));                    
                    } else {
                        $error = 'Не найдена операция на дату ' . $date;                        
                    }
                } else {
                    $error = 'На дату ' . $stop->DATE . ' не может быть две операции';
                }
            } else {
                $error = 'Нет остановки линии, к которой можно было бы прижать';
            }
        } else {
            $error = 'Не задана дата';
        }

        return $error;
    }
}