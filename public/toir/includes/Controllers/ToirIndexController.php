<?php

class ToirIndexController extends ToirController
{
    public $workshop;
    public $month;
    public $year;
    public $lines = [];
    public $filter = [];

    /**
     * @return void
     */
    public function __construct()
    {
        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        if(!$this->workshop) {
            header("Location: /main");
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
        $this->month = intval($_REQUEST['month'] ?? date('n'));
        $this->year = intval($_REQUEST['year'] ?? (int)date('Y'));
        $this->nextMonth = $this->month < 12 ? $this->month + 1 : 1;
        $this->nextYear = $this->month < 12 ? $this->year : $this->year + 1;
        $this->table2 = $_REQUEST['table2'] ?? 'plan';
        $this->getRequestFilter();

   }

    /**
     * @return void
     */
    public function index()
    {
        $this->showHeader();

        $this->setLines();

        /************** Alerts *******************/

        $masterPlanDate = $this->getMasterPlanDate();

        $this->view('index/alerts', [
            'createServiceRequest' => ReceivingService::countNotDone(),
            'countRepairRequest' => ServiceRequestService::countNotDone(),
            'masterPlanDate' => $this->getMasterPlanDate(),
            'masterReportDate' => $this->getMasterReportDate(),
            'operationsNotPush' => $this->getOperationsNotPush(),
            'crashesNotDone' => $this->getCountCrashesNotDone(),
            'operationsNotDone' => count(OperationService::getNotDone($this->workshop, $this->filter)),
            'showPlanMonth' => UserToir::current()->IS_ADMIN ? StopService::isStopPlanMonth($this->workshop) : null,
            'planCheck' => UserToir::current()->IS_ADMIN ? $this->getPlanCheck() : null,
        ]);

        /************** Таблица 1 *******************/

        $this->view('index/table1', [
            'allWorkshops' => UserToir::current()->availableWorkshops,
            'services' => UserToir::current()->availableServices,
            'mekhannik' => UserService::getById($this->workshop->MECHANIC_ID),
            'dateProcesses' => $this->getGroupedDateProcesses(),
            'masterPlanDate' => $masterPlanDate,
        ]);

        /************** Таблица 2 *******************/
        $table2Filter = [
            'WORKSHOP_ID' => $this->workshop->ID,
        ];
        
        $table2Filter["SERVICE_ID"] = $this->filter['SERVICE_ID'] ?? UserToir::current()->availableServicesIds;

        if($this->filter['line']) {
            $table2Filter["LINE_ID"] = $this->filter['line'];
        }

        if($this->filter['mechanism']) {
            $table2Filter["EQUIPMENT_ID"] = $this->filter['mechanism'];
        }

        if($this->filter['name']) {
            $table2Filter["%NAME"] = $this->filter['name'];
        }

        if ($this->table2 == 'plan') {
            $operations = Plan::filter($table2Filter)->get();

            foreach($operations as &$operation) {
                $operation->status = $operation->TASK_RESULT;
                $operation->difference = $operation->NEXT_EXECUTION_DATE ? intval(intval((-time() + strtotime($operation->NEXT_EXECUTION_DATE)))/ (3600 * 24)) : "";
                $operation->late = $operation->getLate();
            }

            $this->view('index/table2', compact('operations'));
        } elseif ($this->table2 == 'noplan') {
            $table2Filter[">PLANNED_DATE"] = date("Y-m-d", mktime(0, 0, 0, $this->month, 1, $this->year) - 1);
            $table2Filter["<=PLANNED_DATE"] = date("Y-m-t", mktime(0, 0, 0, $this->nextMonth, 1, $this->nextYear));
            $table2Filter["TASK_RESULT"] = null;
            $table2Filter["PLAN_ID"] = null;
            $operations = Operation::filter($table2Filter)->get();

            foreach($operations as &$operation) {
                $operation->status = $operation->LAST_DATE_FROM_CHECKLIST ? 'Y' : 'N';
                $operation->difference = $operation->LAST_DATE_FROM_CHECKLIST ? 0 : intval(intval((-time() + strtotime($operation->PLANNED_DATE)))/ (3600 * 24));
                $operation->late = $operation->getLate();
            }

            $this->view('index/table2', compact('operations'));
        } elseif ($this->table2 == 'notdone') {
            $operations = OperationService::getNotDone($this->workshop, $table2Filter);

            foreach($operations as $key => &$operation) {
                $operation->owner = $operation->OWNER;
                $operation->nextExecutionDate = date("d.m.Y", strtotime($operation->PLANNED_DATE));
                $operation->status = $operation->LAST_DATE_FROM_CHECKLIST ? 'Y' : 'N';
                $operation->late = $operation->getLate();
            }

    
            $this->view('index/table2_not_done', compact('operations'));
        }

        /************** Таблица 3 *******************/

        $this->view('index/table3_block');
    }

    /**
     * @return void
     */
	public function printTable1()
    {
        $this->setLines(); 
        $this->view('index/table1_print');
    }

    /**
     * @return void
     */
    private function setLines()
    {
        $filterLines = ['PARENT_ID' => $this->workshop->ID];

        if($this->filter['line']) {
            $filterLines['ID'] = $this->filter['line'];
        }

        if(!empty($this->filter['name'])) {
            $filterName = [
                'WORKSHOP_ID' => $this->workshop->ID,
                'SERVICE_ID' => $this->filter['SERVICE_ID'] ? $this->filter['SERVICE_ID'] : UserToir::current()->availableServicesIds,
                '%NAME' => $this->filter['name']
            ];
            if($this->filter['line']) {
                $filterName['LINE_ID'] = $this->filter['line'];
            }
            $operationsByName = Operation::filter($filterName)->get();

            $linesId = [];
            foreach($operationsByName as $operation) {
                $linesId[] = $operation->LINE_ID;
            }

            $filterLines['ID'] = $linesId;
        }

        $this->lines = Line::filter($filterLines)->get();
        foreach($this->lines as $key => $line) {
            $line->countInDates = $this->getOperationsInCurrentMonthByLine($line);
            $line->stoppedDates = array_merge(
                Stop::getByLineInMonth($line->ID, $this->year, $this->month),
                Stop::getByLineInMonth($line->ID, $this->nextYear, $this->nextMonth)
            );
            $this->lines[$key] = $line;
        }
    }

    /**
     * @param Line $line
     * @return array
     */
    private function getOperationsInCurrentMonthByLine(Line $line): array
    {
        $dateStarted = date("Y-m-d H:i:s", mktime(0, 0, 0, $this->month, 1, $this->year) - 1);
        $dateEnded = date("Y-m-t 23:59:59", mktime(0, 0, 0, $this->nextMonth, 1, $this->nextYear) + 1);
        $filterOperations = [
            "WORKSHOP_ID" => $this->workshop->ID,
            "LINE_ID" => $line->ID,
            ">PLANNED_DATE" => $dateStarted,
            "<PLANNED_DATE" => $dateEnded,
            'SERVICE_ID' => $this->filter['SERVICE_ID'] ? $this->filter['SERVICE_ID'] : UserToir::current()->availableServicesIds,
          ];

          if($this->filter['name']) {
            $filterOperations["%NAME"] = $this->filter['name'];
        }

        $operations = Operation::filter($filterOperations)->get();

        $count = [];
        foreach($operations as $operation) {
            $count[$operation->PLANNED_DATE]++;
        }

        return $count;
    }

    /**
     * @return void
     */
    private function showHeader()
    {
        $errors = [];
        if($errorSaveOperation = $_COOKIE['error_save_operation']) {
            $errors[] = $errorSaveOperation;
        }
        if($errorPushOperation = $_COOKIE['error_push_operation']) {
            $errors[] = $errorPushOperation;
        }

        $this->view('_header', ['title' => 'График ТОиР']);
        $this->view('index/header', [
                'errors' => $errors,
        ]);
    }

    /**
     * @return int
     */
    private function getOperationsNotPush(): int
    {
        $result = 0;

        foreach($this->lines as $line) {
            foreach($line->countInDates as $date => $c) {
                if(!isset($line->stoppedDates[$date])) {
                    $result += $c;
                }
            }
        }

        return $result;
    }

    /**
     * @return void
     */
    private function getRequestFilter()
    {
        $this->filter = [
            'workshop' => $this->workshop->ID,
        ];

        if($_REQUEST['filter_line']) {
            $this->filter['line'] = (int)$_REQUEST['filter_line'];
        }

        if($_REQUEST['filter_mechanism']) {
            $this->filter['mechanism'] = (int)$_REQUEST['filter_mechanism'];
        }

        if($_REQUEST['filter_name']) {
            $this->filter['name'] = $_REQUEST['filter_name'];
        }

        if($_REQUEST['SERVICE_ID']) {
            $this->filter['SERVICE_ID'] = $_REQUEST['SERVICE_ID'];
            UserToir::current()->checkServiceOrFail((int)$this->filter['SERVICE_ID']);
        }
    }

    /**
     * @return array|null
     */
    private function getMasterPlanDate(): ?array
    {
        $time = time() + 60*60*24;
        if(date('N') == 5) {
            $time += 60*60*24*2;
        }
        if(date('N') == 6) {
            $time += 60*60*24;
        }
        $time += 60*60*24;

        $dateProcesses = DateProcess::filter([
                '<STAGE' => DateProcess::STAGE_PLAN_DONE,
                '<DATE' => date('Y-m-d', $time),
                'SERVICE_ID' => UserToir::current()->availableServicesIds,
                'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            ])
            ->orderBy('DATE', 'asc')
            ->get();
        
        $stoppedDates = [];
        foreach($dateProcesses as $dateProcess) {
            if(!isset($stoppedDates[$dateProcess->DATE])) {
                $stoppedDates[$dateProcess->DATE] = [];
            }
            $stoppedDates[$dateProcess->DATE][] = $dateProcess;
        }

        return $stoppedDates;
    }

    /**
     * @return array|null
     */
    private function getMasterReportDate(): ?array
    {
        $dates = [];

        $dateProcesses = DateProcess::filter([
            'STAGE' => DateProcess::STAGE_PLAN_APPROVED,
            'SERVICE_ID' => UserToir::current()->availableServicesIds,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            '<DATE' => date("Y-m-d"),
        ])
        ->orderBy('DATE', 'asc')
        ->get();

        foreach($dateProcesses as $dateProcess) {
            $dates[$dateProcess->DATE] = $dateProcess->DATE;
        }

        return count($dates) > 0 ? $dates : null;
    }

    /**
     * @return array|null
     */
    private function getCountCrashesNotDone(): array
    {
        $crashes = Crash::filter([
                'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
                '<STATUS' => Crash::STATUS_DONE,
            ])
            ->orderBy('STATUS', 'asc')
            ->get();
        
        $countCrashes = [];
        foreach($crashes as $crash) {
            $countCrashes[$crash->STATUS]++;
        }

        return $countCrashes;
    }

    /**
     * @return array
     */
    private function getPlanCheck(): array
    {
        $dateProcesses = DateProcess::filter(['STAGE' => DateProcess::STAGE_PLAN_DONE])->get();

        $dates = [];
        foreach($dateProcesses as $dateProcess) {
            $dates[$dateProcess->DATE] = date("d.m", strtotime($dateProcess->DATE));
        }

        return array_unique($dates);
    }

    /**
     * @return array
     */
    private function getGroupedDateProcesses(): array
    {
        $filter = [
            '>=DATE' => sprintf('%d-%02d', $this->year, $this->month) . '-01',
            '<=DATE' => sprintf('%d-%02d', $this->nextYear, $this->nextMonth) . '-31',
            'WORKSHOP_ID' => $this->workshop->ID,
            'SERVICE_ID' => $this->filter['SERVICE_ID'] ?? UserToir::current()->availableServicesIds,
        ];

        $allDateProcesses = DateProcess::filter($filter)->get();

        $dateProcesses = [];

        foreach($allDateProcesses as $dateProcess) {
            if(!isset($dateProcesses[$dateProcess->DATE])) {
                $dateProcesses[$dateProcess->DATE] = [];
            }
            $dateProcesses[$dateProcess->DATE][] = $dateProcess->STAGE;
        }

        return $dateProcesses;
    }

    /**
     * @param array $dateProcesses
     * @param string $date
     * @param array $masterPlanDate
     * @return string
     */
    public function getClassByDateProcess(array $dateProcesses, string $date, array $masterPlanDate): string
    {
        $time = strtotime($date);
        $class = isWeekend((int)date('d', $time), (int)date('m', $time), (int)date('Y', $time)) ? 'table-danger' : '';

        if(isset($dateProcesses[$date])) {
            $class = 'table-info';
            $minStage = min($dateProcesses[$date]);
            switch ($minStage){
                case DateProcess::STAGE_NEW:
                case DateProcess::STAGE_PLAN_REJECTED:
                        $class = isset($masterPlanDate[$date]) ? 'table-warning' : 'table-info';
                    break;
                case DateProcess::STAGE_PLAN_DONE:
                    $class = 'table-success';
                    break;
                
                case DateProcess::STAGE_PLAN_APPROVED:
                    $class = 'table-success';
                    break;
                
                case DateProcess::STAGE_REPORT_DONE:
                    $class = 'table-secondary';
                    break;
            }
        }

        return $class;
    }


}