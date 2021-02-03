<?php

class ToirPlanDateCheckingController extends ToirController
{

    /**
     * @var string
     */
    public $date;

    /**
     * @var array
     */
    public $operations;

    /**
     * @var array
     */
    public $dateProcesses;

    /*
     * @return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /");
        }

        $this->date = $_REQUEST['date'];
		if(!$this->date) {
			die('Не задана дата остановки линии');
		}
    }

    /*
     * @return void
     */
    public function index()
    {
        $workshops = Workshop::all();
        $services = Service::all();

        $allOperations = Operation::filter([
            'PLANNED_DATE' => date("Y-m-d", strtotime($this->date)),
        ])->get();

        $operations = [];
        foreach($allOperations as $operation) {
            if($operation->dateProcess->STAGE == DateProcess::STAGE_NEW) {
                continue;
            }
            if(!isset($operations[$operation->LINE_ID])) {
                $operations[$operation->LINE_ID] = [];
            }
            $operations[$operation->LINE_ID][$operation->ID] = $operation;
        }

        $this->view('_header', ['title' => 'Проверка планирования на ' . $this->date]);
        $this->view('plan_date_checking/index', compact('workshops', 'services', 'operations'));
        $this->showFooter();
    }

    /*
     * @return void
     */
    public function approve()
    {
        global $USER;

        $dateProcessId = (int)$_REQUEST['approve'];
        $dateProcess = DateProcess::find($dateProcessId);
        if($dateProcess) {
            $dateProcess->PLAN_APPROVE_ADMIN_ID = UserToir::current()->id;
            $dateProcess->PLAN_APPROVE_DATE = date('Y-m-d H:i:s');
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_APPROVED;
            $dateProcess->save();
        }

        header("Location: ?date=" . $this->date);
    }

    /*
     * @return void
     */
    public function reject()
    {
        $dateProcessId = (int)$_REQUEST['reject'];
        $dateProcess = DateProcess::find($dateProcessId);
        if($dateProcess) {
            $dateProcess->PLAN_REJECT_ADMIN_ID = UserToir::current()->id;
            $dateProcess->PLAN_REJECT_DATE = date('Y-m-d H:i:s');
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_REJECTED;
            $dateProcess->COMMENT_REJECT = $_REQUEST['COMMENT'];
            $dateProcess->save();
        }

        header("Location: ?date=" . $this->date);
    }

    /*
     * @return void
     */
    public function cancel_stage()
    {
        $dateProcessId = (int)$_REQUEST['cancel_stage'];
        $dateProcess = DateProcess::find($dateProcessId);
        if($dateProcess) {
            $dateProcess->STAGE = DateProcess::STAGE_PLAN_DONE;
            $dateProcess->COMMENT_REJECT = '';
            $dateProcess->save();
        }

        header("Location: ?date=" . $this->date);
    }

    /**
     * @param DateProcess $dateProcess
     * @param Line|null $line = null
     * @return string
     */
    public function timeBeginEnd(DateProcess $dateProcess, ?Line $line = null): string
    {
        $timeMinutes = [60 * 24, 0];

        foreach($dateProcess->operations as $operation) {
            if($line && $operation->LINE_ID != $line->ID) {
                continue;
            }
            if(!$operation->WORK_TIME) {
                continue;
            }

            [$begin, $end] = explode('-', $operation->WORK_TIME);
            [$beginH, $beginM] = explode(':', trim($begin));
            [$endH, $endM] = explode(':', trim($end));

            $timeMinutes[0] = min($timeMinutes[0], $beginH * 60 + $beginM);
            $timeMinutes[1] = max($timeMinutes[1], $endH * 60 + $endM);
        }

        return $timeMinutes[1] > 0 
            ? ($timeMinutes[0] < 600 ? '0' : '') . floor($timeMinutes[0] / 60) . ':' . ($timeMinutes[0] % 60 < 10 ? '0' : '') . ($timeMinutes[0] % 60)
                . ' - '
                . ($timeMinutes[1] < 600 ? '0' : '') . floor($timeMinutes[1] / 60) . ':' . ($timeMinutes[1] % 60 < 10 ? '0' : '') . ($timeMinutes[1] % 60)
            : '';
    }

    /**
     * @param DateProcess $dateProcess
     * @param Line|null $line = null
     * @return void
     */
    public function timeDuration(DateProcess $dateProcess, ?Line $line = null): string
    {
        $timeBeginEnd = $this->timeBeginEnd($dateProcess, $line);

        return $this->durationByTime($timeBeginEnd);
    }

    /**
     * @param Workshop $workshop
     * @param Line|null $line = null
     * @return void
     */
    public function timeDurationInWorkshop(Workshop $workshop, ?Line $line = null): string
    {
        $dateProcesses = DateProcess::filter([
            'WORKSHOP_ID' => $workshop->ID,
            'DATE' => $this->date,
        ])->get();

        $timeMinutes = [60 * 24, 0];

        foreach ($dateProcesses as $dateProcess) {
            $timeBeginEnd = $this->timeBeginEnd($dateProcess, $line);
            if ($timeBeginEnd) {
                [$begin, $end] = explode(' - ', $timeBeginEnd);
                [$beginH, $beginM] = explode(':', $begin);
                [$endH, $endM] = explode(':', $end);
    
                $timeMinutes[0] = min($timeMinutes[0], $beginH * 60 + $beginM);
                $timeMinutes[1] = max($timeMinutes[1], $endH * 60 + $endM);    
            }
        }

        $time = $timeMinutes[1] > 0 
            ? ($timeMinutes[0] < 600 ? '0' : '') . floor($timeMinutes[0] / 60) . ':' . ($timeMinutes[0] % 60 < 10 ? '0' : '') . ($timeMinutes[0] % 60)
                . ' - '
                . ($timeMinutes[1] < 600 ? '0' : '') . floor($timeMinutes[1] / 60) . ':' . ($timeMinutes[1] % 60 < 10 ? '0' : '') . ($timeMinutes[1] % 60)
            : '';
        
        return $this->durationByTime($time);
    }

    /**
     * @param string|null $time
     * @return string
     */
    public function durationByTime(?string $time): string
    {
        $duration = '';
        if ($time) {
            [$begin, $end] = explode(' - ', $time);
            [$beginH, $beginM] = explode(':', $begin);
            [$endH, $endM] = explode(':', $end);

            $durationMinutes = $endH * 60 + $endM - ($beginH * 60 + $beginM);

            $duration = floor($durationMinutes / 60) . ' ч. ' . ($durationMinutes % 60) . ' м.';
        }

        return $duration;
    }

   

}