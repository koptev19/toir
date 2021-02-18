<?php

class ToirAddHistoryGroupController extends ToirController
{

    /**
     * @var ServiceRequest
     */
    public $sourceModel;

    /**
     * @var Crash
     */
    public $crash;

    /**
     * @var string
     */
    public $date;

    /**
     * @return void
     */
    public function __construct()
    {
        if($_REQUEST['service_request']){
			$this->sourceModel = ServiceRequest::find((int)$_REQUEST['service_request']);
		    if(!$this->sourceModel) {
                die('Не задана заявка на ремонт');
            }
            $this->crash = $this->sourceModel->crash;
            $this->date = $this->crash ? $this->crash->DATE : $this->sourceModel->created_at;
            $this->date = date("d.m.Y", strtotime($this->date));
        }

		if($_REQUEST['downtime_id']){
			$this->sourceModel = Downtime::find((int)$_REQUEST['downtime_id']);
		    if(!$this->sourceModel) {
			    die('Не задан простой');
			}
	        $this->date = date("d.m.Y", strtotime($this->sourceModel->DATE));
		}
		
		if(!$this->sourceModel){
			die();
		}
		
		UserToir::current()->checkServiceOrFail($this->sourceModel->SERVICE_ID);

        
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->view('add_history_group/index');
    }

    /**
     * @return void
     */
    public function save()
    {
        foreach($_REQUEST['NAME'] as $keyOperation => $name) {
            if(empty($name)) {
                continue;
            }

            $equipment = Equipment::find((int) $_REQUEST['equipment'][$keyOperation]);

			$reason = $this->crash ? Operation::REASON_CRASH : Operation::REASON_REPAIR;

            $fields = [
                'SERVICE_ID' => $this->sourceModel->SERVICE_ID,
                'EQUIPMENT_ID' => $equipment->ID,
                'WORK_ID' => $_REQUEST['WORK_ID'][$keyOperation] ? $_REQUEST['WORK_ID'][$keyOperation] : null,
                'LINE_ID' => $equipment->LINE_ID,
                'WORKSHOP_ID' => $equipment->WORKSHOP_ID,
                'NAME' => $_REQUEST['NAME'][$keyOperation],
                'RECOMMENDATION' => $_REQUEST['RECOMMENDATION'][$keyOperation],
                'TYPE_OPERATION' => $_REQUEST['TYPE_OPERATION'][$keyOperation],
                'COMMENT_NO_RESULT' => $_REQUEST['COMMENT'][$keyOperation],
                'OWNER' => $_REQUEST['OWNER'][$keyOperation],
                'PLANNED_DATE' => $_REQUEST['PLANNED_DATE'][$keyOperation] ?? $this->date,
                'START_DATE' => $_REQUEST['PLANNED_DATE'][$keyOperation] ?? $this->date,
                'WORK_TIME' => $_REQUEST['time_from'][$keyOperation]. ' - ' . $_REQUEST['time_to'][$keyOperation],
                'REASON' => $reason,
                'RESULT' => 'Y',
            ];

            $source ="";
			
			if(get_class($this->sourceModel) == "ServiceRequest"){
                $source = $this->crash
                    ? History::SOURCE_CRASH . ': ' . $this->crash->ID
                        : History::SOURCE_SERVICE . ': ' . $this->sourceModel->ID;
			}elseif(get_class($this->sourceModel) == "Downtime"){
				$source = History::SOURCE_DOWNTIME . ': ' . $this->sourceModel->ID;
			}

            $historyId = HistoryService::create($fields, $source);

            if(get_class($this->sourceModel) == "ServiceRequest"){
				ServiceRequestService::addHistory($this->sourceModel, $historyId);
			}
        }

        if($this->crash) {
            $this->crash->STATUS = max($this->crash->STATUS, Crash::STATUS_OPERATIONS);
            $this->crash->save();
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
        
        $this->view('add_history_group/new_row', compact('values', 'date'));
    }

}