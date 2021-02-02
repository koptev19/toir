<?php

use Bitrix\Main\UI\Extension;

class ToirMekhannikController extends ToirController
{

    public $workshop;

    public function __construct()
    {
        $this->workshop = Workshop::find((int)$_REQUEST['workshop']);
        if(!$this->workshop) {
            header("Location: main.php");
        }
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
    }

    /*
     * function step1
     */
    public function step1()
    {
        $mekhannik = UserService::getById($this->workshop->MECHANIC);

        $this->showHeader();
        $this->view('mekhannik/step1', compact('mekhannik'));
		$this->showFooter();
    }

    /*
     * function step2
     */
    public function step2()
    {
        // Сохранение данных с прошлого шага
        if(isset($_REQUEST['save'])) {
            $this->save();
            header('Location: mekhannik.php?step=2&workshop=' . $this->workshop->ID);
            die();
        }

        $this->showHeader();

        $this->view('mekhannik/step2', [
        ]);
    }

    private function showHeader()
    {
        $this->view('_header', ['title' => 'Установка Механика цеха']);
    }

    private function save()
    {
        global $USER;

        if (!$_REQUEST['_user_input']) {
            return;
        }
        $userId = UserService::getUserIdByName($_REQUEST['_user_input']);
        if(!$userId) {
            return;
        }

        $this->workshop->MECHANIC = $userId;
        $this->workshop->save();

        foreach($this->workshop->lines as $line) {
            $stops = Stop::filter(['LINE_ID' => $line->ID, '>DATE' => date("Y-m-d", time() - 60*60*24)])->get();
            foreach($stops as $stop) {
                TaskService::changeResponsibleId($stop->TASK, $userId);

                if($stop->PRE_TASK) {
                    TaskService::changeResponsibleId($stop->PRE_TASK, $userId);
                }

                if($stop->REPORT_TASK) {
                    TaskService::changeResponsibleId($stop->REPORT_TASK, $userId);
                }
            }
        }
    }

}