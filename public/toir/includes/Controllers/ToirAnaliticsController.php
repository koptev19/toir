<?php

class ToirAnaliticsController extends ToirController
{
    /**
     * @var Workshop
     */
    public $workshop;

    /**
     * @var array
     */
    public $analitic = [];

    /**
     * @var string
     */
    public $dateFrom;

    /**
     * @var string
     */
    public $dateTo;


    /**
     * @return void
     */
    public function __construct()
    {
        if(!UserToir::current()->IS_ADMIN) {
            header("Location: /");
        }

        $this->dateFrom = $_REQUEST['date_from'] ?? date('Y-m-01');
        $this->dateTo = $_REQUEST['date_to'] ?? date('Y-m-t');
        $this->analitic = new AnaliticService($this->dateFrom, $this->dateTo);
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->view('_header', ['title' => 'Аналитика']);
        $this->view('analitics/index');
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function table4Equipment()
    {
        $parent = Equipment::find((int)$_REQUEST['parent']);
        foreach($parent->children as $child) {
            $this->view('analitics/table4_equipment', ['equipment' => $child]);
        }
    }

}