<?php

class ToirAddWorkController extends ToirController
{
    /**
     * @var Equipment
     */
    public $equipment;

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @return void
     */
    public function __construct()
    {
        if($_REQUEST['equipment']){
        $this->equipment = Equipment::findAvailabled((int)$_REQUEST['equipment']);
		}elseif($_REQUEST['work_id']){
			$work = Work::find($_REQUEST['work_id']);
			$this->equipment = Equipment::findAvailabled($work->EQUIPMENT_ID); 
		}
        if(!$this->equipment) {
            header("Location: /main");
        }
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->view('add_work/index');
    }

    public function store()
    {
        if(!$this->validate($_REQUEST)) {
            $_SESSION['add_work_errors'] = $this->errors;
            header('Location: add_work.php?equipment=' . $this->equipment->ID);
            exit;
        }

        $this->createWork();

        $this->openerReloadAndSelfClose();            
    }

    public function storeAjax()
    {
        if(!$this->validate($_REQUEST)) {
            echo json_encode(['errors' => implode("\n", $this->errors)]);
            exit;
        }

        $this->createWork();

        echo json_encode(['errors' => '']);
    }

    public function edit()
    {
		$services = UserToir::current()->availableServices;
		$work = Work::find($_REQUEST["work_id"]);
        $this->view('add_work/edit', compact('work','services'));
    }

    public function save()
    {
        if(!$this->validate($_REQUEST)) {
            $_SESSION['add_work_errors'] = $this->errors;
            header('Location: add_work.php?action=edit&work_id=' . $_REQUEST["work_id"]);
            exit;
        }

		$work = Work::find($_REQUEST["work_id"]);
        $work->NAME = $_REQUEST["NAME"];
        $work->SERVICE_ID = $_REQUEST["SERVICE_ID"];
        $work->RECOMMENDATION = $_REQUEST["RECOMMENDATION"];
        $work->TYPE = $_REQUEST["TYPE"];

        $work->save();
        $this->openerReloadAndSelfClose();            
    }

    /**
     * @return void
     */
	public function delete()
    {
		$work = Work::find($_REQUEST["work_id"]);
        $work->delete();
        header('Location: /equipments?id=' . $work->EQUIPMENT_ID);
    }

    /**
     * @param array $request
     *
     * @return bool
     */
    private function validate(array $request): bool
    {
        $this->errors = [];

        if(empty($request["SERVICE_ID"])){
            $this->errors[] = "Выберите службу";
        } else {
            UserToir::current()->checkServiceOrFail($request["SERVICE_ID"]);
        }

        if(empty($request["NAME"])){
            $this->errors[] = "Укажите название операции";
        }

        if(empty($request["TYPE"])){
            $this->errors[] = "Выберите тип операции";
        }

        if(count($this->errors) > 0) {
            return false;
        } else {
            return true;
        }	
    }    
    
    private function createWork()
    {
        $create = [
            'NAME' => $_REQUEST["NAME"],
            'WORKSHOP_ID' => $this->equipment->WORKSHOP_ID,
            'LINE_ID' => $this->equipment->LINE_ID,
            'EQUIPMENT_ID' => $this->equipment->ID,
            'SERVICE_ID' => $_REQUEST["SERVICE_ID"],
            'RECOMMENDATION' => $_REQUEST["RECOMMENDATION"],
            'TYPE' => $_REQUEST["TYPE"],
        ];

        Work::create($create);
   }
}