<?php

class ToirCrashesController extends ToirController
{
    /**
     * @var Workshop
     */
    public $workshop;

    /**
     * @var Crash
     */
    public $selectedCrash;

    /**
     * @return void
     */
    public function __construct()
    {
		$this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        $this->selectedCrash = Crash::find((int)$_REQUEST['crash']);
        if($this->selectedCrash) {
            $this->workshop = $this->selectedCrash->workshop;
        }
        if (!$this->workshop) {
            header("Location: main.php");
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
    }

    /**
     * @return void
     */
    public function index()
    {
        $filter = [
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds
        ];

        if($_REQUEST['status']) {
            $filter['STATUS'] = $_REQUEST['status'];
        }

        if($this->selectedCrash) {
            $filter['ID'] = $this->selectedCrash->ID;
        }

        $crashes = Crash::filter($filter)->get();
        
        foreach($crashes as $key => $crash) {
            $crash->operationsByService = $this->getOperationsByService(array_merge($crash->plans, $crash->operations));
            $crash->historiesByService = $this->getOperationsByService($crash->histories);
            $crashes[$key] = $crash;
        }

        $this->view('crashes/index', compact('crashes'));
    }

    /**
     * @param array $operations
     * @return array
     */
    private function getOperationsByService(array $operations): array
    {
        $operationsByService = [];
        foreach($operations as $operation) {
            if(!isset($operationsByService[$operation->SERVICE_ID])) {
                $operationsByService[$operation->SERVICE_ID] = [
                    'name' => $operation->service->name,
                    'operations' => [],
                ];
            }

            $operationsByService[$operation->SERVICE_ID]['operations'][] = $operation;
        }

        return $operationsByService;
    }

}