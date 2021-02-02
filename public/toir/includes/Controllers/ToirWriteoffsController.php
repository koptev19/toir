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
            header("Location: main.php");
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
		foreach($writeOffs as $id => $writeOff){
			$idOperations[] = $writeOff['UF_OPERATIONID'];
			$idEquipments[] = $writeOff['UF_EQUIPMENTID'];
			$idUsers[] = $writeOff['UF_USERID'];
        }
        
		$operations = Operation::filter(['ID' => $idOperations])->get();
        $equipments = Equipment::filter(['ID' => $idEquipments])->get();
        $users = UserService::getList(['ID' => $idUsers]);

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
			$idOperations[] = $writeOff['UF_OPERATIONID'];
			$idEquipments[] = $writeOff['UF_EQUIPMENTID'];
			$idUsers[] = $writeOff['UF_USERID'];
        }
        
		$operations = Operation::filter(['ID' => $idOperations])->get();
        $equipments = Equipment::filter(['ID' => $idEquipments])->get();
        $users = UserService::getList(['ID' => $idUsers]);

        $this->view('writeoffs/print', compact('writeOffs', 'operations', 'equipments', 'users'));
    }

    /**
     * @return array
     */
    private function getWriteoffs()
    {
        $filter = [];
		
		if($this->filter['USER_ID']) {
            $filter["UF_USERID"] = $this->filter['USER_ID'];
        }

		if($this->filter['STORE']) {
            $filter["UF_STORE"] = $this->filter['STORE'];
        }
		
		if($this->filter['%NAME']) {
            $filter["%UF_NAME"] = $this->filter['%NAME'];
        }

        if($this->filter['EQUIPMENT_ID'] && $this->filter['EQUIPMENT_ID'] != $this->workshop->ID) {
            $filter['UF_EQUIPMENTID'] = [$this->filter['EQUIPMENT_ID']];
            $equipment = Equipment::find($this->filter['EQUIPMENT_ID']);
            foreach($equipment->allChildren as $child) {
                $filter['UF_EQUIPMENTID'][] = $child->ID;
            }
        } else {
            $equipments = Equipment::filter(['WORKSHOP_ID' => $this->workshop->ID])->get();
            $filter["UF_EQUIPMENTID"] = [];
            foreach($equipments as $equipment) {
                $filter["UF_EQUIPMENTID"][] = $equipment->ID;
            }
        }

		if($this->filter['OPERATION_ID']) {
            $filter["UF_OPERATIONID"] = $this->filter['OPERATION_ID'];
        }

		if($this->filter['PLANNED_DATE_FROM']){
			$filter[">=UF_DATE"] = date("d.m.Y 00:00:00", strtotime($this->filter['PLANNED_DATE_FROM']));
		}

		if($this->filter['PLANNED_DATE_TO']){
			$filter["<=UF_DATE"] = date("d.m.Y 00:00:00", strtotime($this->filter['PLANNED_DATE_TO']));
		}

        $writeOffs = HighloadBlockService::getList(HIGHLOAD_WRITEOFFS_BLOCK_ID, $filter, ['ID' => 'DESC']); 

        return $writeOffs;
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