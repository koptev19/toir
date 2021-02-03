<?php

class ToirAcceptController extends ToirController
{

    public function __construct()
    {
        if (!UserToir::current()->IS_ADMIN) {
            header("Location: /main");
        }        
    }

    /**
     * @return void
     */
    public function index()
    {
        $accepts = Accept::all();
        foreach($accepts as $id => $accept){
            $idEquipments[] = $accept->EQUIPMENT_ID;
        }

        $equipments = Equipment::filter(['ID' => $idEquipments])->get();
        $this->showHeader();
        $this->view('accept/index',compact('accepts','equipments'));
        $this->showFooter();
    }


	public function edit()
    {
       $errors = $_SESSION['accept_errors'];
       unset($_SESSION['accept_errors']);
	   $accept = Accept::find($_REQUEST['id']);
	   $equipment = Equipment::find($accept->EQUIPMENT_ID);
	   $this->showHeader();
       $this->view('accept/edit', compact('accept','equipment'));
	   $this->showFooter();
    }
	
	

	
    public function newAccept()
    {
        $errors = $_SESSION['accept_errors'];
        unset($_SESSION['accept_errors']);
        $this->showHeader();
        $this->view('accept/create', compact('errors'));
        $this->showFooter();
    }

    public function showHeader()
    {
        $this->view('_header', ['title' => "Приемка оборудования"]);
        $this->view('accept/header');
    }

    public function showFooter()
    {
        $this->view('accept/footer');
        $this->view('_footer');
    }


	public function save()
    {
	 	$accept = Accept::find($_REQUEST['id']);
        $accept->CHECKLIST  = trim($_REQUEST['CHECKLIST']);
        $accept->save(); 
		header("Location: accept.php");
	}

	public function delete()
    {

	 	$accept = Accept::find($_REQUEST['id']);
        $accept->delete();
		header("Location: accept.php");
	}

    public function create()
    {
        $errors = [];

        if(empty($_REQUEST['equipment'])) {
            $errors[] = 'Выберите оборудование';
        }

        if(count($errors) > 0) {
            $_SESSION['accept_errors'] = $errors;
            header('Location: accept.php?action=newAccept');
            die();
        }

        $create = [
            'CHECKLIST' => trim($_REQUEST['CHECKLIST']),
            'WORKSHOP_ID' => $_REQUEST['workshop'],
            'LINE_ID' => $_REQUEST['line'],
            'EQUIPMENT_ID' => $_REQUEST['equipment'],
        ];

        Accept::create($create);
        header("Location: accept.php");
    }

}