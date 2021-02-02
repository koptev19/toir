<?php

class ToirServiceController extends ToirController
{

    /**
     * @return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /404.php");
        }
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->showHeader();
        $this->view('service/index');
        $this->showFooter();
    }


    /**
     * @return void
     */
    public function create()
    {        
        $this->showHeader();
        $this->view('service/create');
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function store()
    {        
        $create = [
            'NAME' => trim($_REQUEST['NAME']),
            'SHORT_NAME' => trim($_REQUEST['SHORT_NAME']),
			'MANAGER_ID' => $_REQUEST['MANAGER_ID'],
        ];

        Service::create($create);
        header("Location: service.php");
    }

    /**
     * @return void
     */
    public function edit()
    {
        $service = Service::find((int)$_REQUEST['ID']);
        $this->showHeader();
        $this->view('service/edit', compact('service'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function update()
    {        
        $service = Service::find((int)$_REQUEST['ID']);
        $service->NAME = $_REQUEST['NAME'];
        $service->SHORT_NAME = $_REQUEST['SHORT_NAME'];
		$service->MANAGER_ID = $_REQUEST['MANAGER_ID'];
        $service->save();
        header("Location: service.php");
    }

    /**
     * @return void
     */
	public function delete()
    {        
        $service = Service::find((int)$_REQUEST['ID']);
        $service->delete();
        header("Location: service.php");
    }

    /**
     * @return void
     */
    private function showHeader()
    {
        $this->view('_header', ['title' => 'Службы']);
        $services = Service::all();
        $this->view('service/header', compact('services'));
    }

    /**
     * @return void
     */
    public function showFooter()
    {
        $this->view('service/footer');
        $this->view('_footer');
    }

}