<?php

class ToirCrashCreateController extends ToirController
{

    /**
     * @var Workshop
     */
    public $workshop;

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @return void
     */
    public function __construct()
    {
        $this->step = $_REQUEST['step'] ?? 1;
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->view('_header', ['title' => 'Добавление аварии']);
        $this->view('crash_create/index', []);
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function save()
    {
        $this->validate();

        $create = [
            'WORKSHOP_ID' => $_REQUEST['workshop'],
            'LINE_ID'     => $_REQUEST['line'],
            'EQUIPMENT_ID'=> $_REQUEST['equipment'],
            'DATE'        => $_REQUEST['DATE'],
            'TIME_FROM'   => $_REQUEST['TIME_FROM'],
            'TIME_TO'     => $_REQUEST['TIME_TO'],
            'OWNER'       => $_REQUEST['OWNER'],
        ];

        CrashService::create($create);

        $this->openerReloadAndSelfClose();
    }

    /**
     * @return void
     */
    public function validate()
    {
        if (empty($_REQUEST['workshop'])) {
            $this->errors[] = 'Не задан цех';
        }

        if (!$_REQUEST['line']) {
            $this->errors[] = 'Не задана линия';
        }

        if (!$_REQUEST['equipment']) {
            $this->errors[] = 'Не задано оборудование';
        }

        if (empty($_REQUEST['DATE'])) {
            $this->errors[] = 'Не задана дата';
        }

        if(!empty($this->errors)) {
            $this->index();
            die();
        }
    }

}