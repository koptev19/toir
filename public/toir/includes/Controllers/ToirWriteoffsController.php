<?php

class ToirWriteoffsController extends ToirController
{
    /**
     * @var array
     */
    public $filter;

    /**
     * @var Workshop
     */
    public $workshop;

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

        $this->filter = $_REQUEST['filter'];
    }

    /**
     * @return void
     */
    public function index()
    {
        $writeOffs = $this->getWriteoffs();
        
        $idOperations = [];
        $idEquipments = [];
        $idUsers = [];
		foreach($writeOffs as $writeOff){
			$idOperations[] = $writeOff->OPERATION_ID;
			$idEquipments[] = $writeOff->EQUIPMENT_ID;
			$idUsers[] = $writeOff->USER_ID;
        }
        
		$operations = Operation::filter(['ID' => $idOperations])->get();
        $equipments = Equipment::filter(['ID' => $idEquipments])->get();
        $users = UserToir::filter(['ID' => $idUsers])->get();

		$this->view('_header', ['title' => 'Журнал списания ТМЦ']);
        $this->view('writeoffs/index', compact('writeOffs', 'operations', 'equipments', 'users'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function print()
    {
        $writeOffs = $this->getWriteoffs();
        
        $idOperations = [];
        $idEquipments = [];
        $idUsers = [];
		foreach($writeOffs as $id => $writeOff){
			$idOperations[] = $writeOff->OPERATION_ID;
			$idEquipments[] = $writeOff->EQUIPMENT_ID;
			$idUsers[] = $writeOff->USER_ID;
        }
        
		$operations = Operation::filter(['ID' => $idOperations])->get();
        $equipments = Equipment::filter(['ID' => $idEquipments])->get();
        $users = UserToir::filter(['ID' => $idUsers])->get();

        $this->view('writeoffs/print', compact('writeOffs', 'operations', 'equipments', 'users'));
    }

    /**
     * @return array
     */
    private function getWriteoffs()
    {
        $filter = [];
		
		if($this->filter['USER_ID']) {
            $filter["user_id"] = $this->filter['USER_ID'];
        }

		if($this->filter['STORE']) {
            $filter["store"] = $this->filter['STORE'];
        }
		
		if($this->filter['%NAME']) {
            $filter["%name"] = $this->filter['%NAME'];
        }

        if($this->filter['EQUIPMENT_ID'] && $this->filter['EQUIPMENT_ID'] != $this->workshop->ID) {
            $filter['EQUIPMENT_ID'] = [$this->filter['EQUIPMENT_ID']];
            $equipment = Equipment::find($this->filter['EQUIPMENT_ID']);
            foreach($equipment->allChildren as $child) {
                $filter['EQUIPMENT_ID'][] = $child->ID;
            }
        } else {
            $equipments = Equipment::filter(['WORKSHOP_ID' => $this->workshop->ID])->get();
            $filter["EQUIPMENT_ID"] = [];
            foreach($equipments as $equipment) {
                $filter["EQUIPMENT_ID"][] = $equipment->ID;
            }
        }

		if($this->filter['OPERATION_ID']) {
            $filter["OPERATION_ID"] = $this->filter['OPERATION_ID'];
        }

		if($this->filter['PLANNED_DATE_FROM']){
			$filter[">=DATE"] = date("Y-m-d", strtotime($this->filter['PLANNED_DATE_FROM']));
		}

		if($this->filter['PLANNED_DATE_TO']){
			$filter["<=DATE"] = date("Y-m-d", strtotime($this->filter['PLANNED_DATE_TO']));
		}

        $writeoffs = Writeoff::filter($filter)
            ->orderBy('created_at', 'desc')
            ->get(); 

        return $writeoffs;
    }

    public function printUrl()
    {
        $url = '?action=print&workshop=' . $this->workshop->ID;

        foreach($this->filter ?? [] as $key => $value) {
            $url .= '&filter['. $key . ']=' . $value;
        }

        return $url;
    }


}