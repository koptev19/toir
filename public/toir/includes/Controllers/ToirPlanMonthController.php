<?php


class ToirPlanMonthController extends ToirController
{
    /**
     * @var Workshop
     */
    public $workshop;

    /**
     * @var object
     */
    public $date;

    /**
     * @var int
     */
    public $timeStart;

    /**
     * return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /404.php");
        }

        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);

        if (!$this->workshop) {
            dump($_REQUEST);
            die('Не задан цех');
        }

        if(!$_SESSION['plan_month_date']) {
            $_SESSION['plan_month_date'] = StopService::isStopPlanMonth($this->workshop);
        }
        $this->date = $_SESSION['plan_month_date'];
        if(!$this->date) {
            die('Планирование уже пройдено');
        }
        $this->timeStart = strtotime($this->date->year . '-' . ($this->date->month < 10 ? '0' : '') . $this->date->month . '-01 00:00:00');
    }

    /**
     * return void
     */
    public function step1()
    {
        $this->view('plan_month/header');

        $this->view('plan_month/step1', [
            'lines' => $this->getLines(false),
        ]);
        $this->view('_footer');
    }

    /**
     * return void
     */
    public function step1_save()
    {
        // Формируем даты остановки
        foreach($_REQUEST['stop'] ?? [] as $lineId => $dates) {
            foreach($dates as $date => $s) {
                if($s) {
                    StopService::createIfNotExists($lineId, strtotime($date));
                } else {
                    StopService::deleteIfExists($lineId, $date);
                }
            }
        }

        // Теперь формируем операции на основе плановых
        foreach($this->workshop->plans as $plan) {
            // Сначала удалим все старые
            $oldOperations = $plan->operations()
                ->setFilter(['>PLANNED_DATE' => date('Y-m-d', $this->timeStart - 1)])
                ->get();
            
            foreach ($oldOperations as $operation) {
                $operation->delete();
            }

            // Теперь создадим новые
            $firstDate = date("Y-m-d 00:00:00", $this->timeStart);
            $lastDate = date("Y-m-t 23:59:59", $this->timeStart);
            OperationService::createByPlanInRange($plan, $firstDate, $lastDate);
        }

        header('Location: ?step=2&workshop=' . $this->workshop->ID);
    }

    /**
     * return void
     */
    public function step2()
    {
        $this->view('plan_month/header');

        $this->view('plan_month/step2', [
            'lines' => $this->getLines(),
            'operations' => $this->getCreatedOperations(),
        ]);
        $this->view('_footer');
    }

    /**
     * return void
     */
    public function step2_save()
    {
        $errorDate = [];

        foreach($this->workshop->plans as $plan) {
            $createdOperations = $plan->operations()
                ->setFilter(['>PLANNED_DATE' => date('Y-m-d', $this->timeStart - 1)])
                ->get();

            $errorDate = PushService::checkAndPush($createdOperations);
            if($errorDate) {
                break;
            }
        }

        if($errorDate) {
            header('Location: ?step=2&workshop=' . $this->workshop->ID . "&error_date=" . $errorDate);
        } else {
            header('Location: ?step=3&workshop=' . $this->workshop->ID);
        }
    }

    /**
     * return void
     */
    public function step3()
    {
        unset($_SESSION['plan_month_date']);

        $this->view('plan_month/header');
        $this->view('plan_month/step3', [
            'lines' => $this->getLines(),
            'operations' => $this->getCreatedOperations(),
        ]);
        $this->view('_footer');
    }

    /**
     * @param bool $isAddStops = true
     *
     * @return array
     */
    private function getLines(bool $isAddStops = true): array
    {
        $lines = $this->workshop->lines;
        
        if($isAddStops) {
            foreach ($lines as $lineId => $line) {
                $line->stoppedDates = Stop::getByLineInMonth($line->ID, $this->date->year, $this->date->month);
                $lines[$lineId] = $line;
            }
        }


        return $lines;
    }

    /**
     * @return array
     */
    private function getCreatedOperations()
    {
        $createdOperations = $this->workshop->operations()
            ->setFilter(['>PLANNED_DATE' => date('Y-m-d', $this->timeStart - 1)])
            ->get();
        

        $operations = [];

        foreach($createdOperations as $operation) {
            $operations[$operation->LINE_ID][$operation->PLANNED_DATE][] = $operation;
        }

        return $operations;
    }

	 /**
     * @return void
     */
    public function pushToRight()
    {
	  $this -> push('pushToRight');
    }

	/**
     * @return void
     */
	public function pushToLeft()
    {
	  $this -> push('pushToLeft');
    }

	       /**
     * @return void
     */
    public function push(string $direction)
    {
        
		$operations = Operation::filter(['LINE_ID' => $_REQUEST['line'],
										'PLANNED_DATE' => date('Y-m-d',strtotime($_REQUEST['date'])),
										])
										->get();
		$errors =[];
		foreach($operations as $operation){
				$error = PushService::$direction($operation->ID);
				if(is_array($error)) $errors[] = $error; 
		}
        
		if(count($errors)){
			$_SESSION['pushErrors'] = $errors;
		}

		header('Location: plan_month.php?step=2&workshop=' . $this->workshop->ID );
    }


    /**
     * @param string $date
     * @param string $direction
     * 
     * @return string
     */
  

}