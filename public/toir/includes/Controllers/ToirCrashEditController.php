<?php

class ToirCrashEditController extends ToirController
{
    /**
     * @var Crash
     */
    public $crash;

    /**
     * @return void
     */
    public function __construct()
    {
		$this->crash = Crash::findAvailabled((int)$_REQUEST['crash']);
        if (!$this->crash) {
            die('Не задана авария');
        }
    }

    /**
     * @return void
     */
	public function crashDone()
    {
		$this->crash->STATUS = Crash::STATUS_DONE;
		$this->crash->save();
        header("Location: crashes.php?workshop=" . $this->crash->WORKSHOP_ID);
	}

    /**
     * @return void
     */
    public function operations()
    {
        $operations = array_merge($this->crash->plans, $this->crash->operations);

        foreach($operations as $key => $operation) {
            $operation->date = is_a($operation, Plan::class)
                ? $operation->START_DATE
                : $operation->PLANNED_DATE;
            $operation->date = date("d.m.Y", strtotime($operation->date));

            $operations[$key] = $operation;
        }

		$this->view('crash_edit/operations', compact('operations'));
    }
    
    /**
     * @return void
     */
    public function histories()
    {
        $histories = $this->crash->histories();

        $this->view('crash_edit/histories', compact('histories'));
    }
    
    /**
     * @return void
     */
    public function editDescription()
    {
        $this->view('crash_edit/edit_description');
    }
    
    /**
     * @return void
     */
    public function saveDescription()
    {
		$this->crash->DESCRIPTION = $_REQUEST['DESCRIPTION'];
        $this->crash->STATUS = max($this->crash->STATUS, Crash::STATUS_DESCRIPTION);
		$this->crash->save();
        header("Location: crashes.php?workshop=" . $this->crash->WORKSHOP_ID);
    }
    
    /**
     * @return void
     */
    public function editDecision()
    {
        $this->view('crash_edit/edit_decision');
    }
    
    /**
     * @return void
     */
    public function saveDecision()
    {
		$this->crash->DECISION = $_REQUEST['DECISION'];
        $this->crash->STATUS = max($this->crash->STATUS, Crash::STATUS_DECISION);
		$this->crash->save();
        header("Location: crashes.php?workshop=" . $this->crash->WORKSHOP_ID);
    }
    
    /**
     * @return void
     */
    public function saveFiles()
    {
        if($fileId = FileService::uploadMultiple('DOCUMENTS')) {
            $this->crash->DOCUMENTS = json_encode(array_merge(json_decode($this->crash->DOCUMENTS ?? "[]"), $fileId));
            $this->crash->save();
        }

        header("Location: crashes.php?workshop=" . $this->crash->WORKSHOP_ID);
    }

    
    /**
     * @return void
     */
    public function saveDecisionFiles()
    {
        if($fileId = FileService::uploadMultiple('DOCUMENTS')) {
            $this->crash->DECISION_DOCUMENTS = json_encode(array_merge(json_decode($this->crash->DECISION_DOCUMENTS ?? "[]"), $fileId));
            $this->crash->save();
        }

        header("Location: crashes.php?workshop=" . $this->crash->WORKSHOP_ID);
    }
    
    /**
     * @return void
     */
    public function deleteFile()
    {
        if($_REQUEST['delete_document_file']) {
            $documents = $this->crash->DOCUMENTS ? json_decode($this->crash->DOCUMENTS, true) : [];
            $key = array_search($_REQUEST['delete_document_file'], $documents);
            if($key !== false) {
                unset($documents[$key]);
                $this->crash->DOCUMENTS = json_encode($documents);
                $this->crash->save();
            }
        }
        if($_REQUEST['delete_decision_file']) {
            $documents = $this->crash->DECISION_DOCUMENTS ? json_decode($this->crash->DECISION_DOCUMENTS, true) : [];
            $key = array_search($_REQUEST['delete_decision_file'], $documents);
            if($key !== false) {
                unset($documents[$key]);
                $this->crash->DECISION_DOCUMENTS = json_encode($documents);
                $this->crash->save();
            }
        }

        echo json_encode(['result' => 'ok']);
    }
 
    /**
     * @return void
     */
    public function selectServiceRequest()
    {
        $this->view('crash_edit/select_service_request');
    }
 
    /**
     * @return void
     */
    public function selectServices()
    {
        $services = UserToir::current()->availableServices;
        
        $this->view('crash_edit/select_services', compact('services'));
    }
 
}