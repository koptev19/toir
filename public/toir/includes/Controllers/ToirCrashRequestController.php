<?php

use Bitrix\Main\UI\Extension;

class ToirCrashRequestController extends ToirController
{
    /**
     * @var Receiving
     */
    public $crash;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->crash =  Crash::findAvailabled($_REQUEST['crash_id']);
		if(!$this->crash) {
			die('Не найдена авария');
		}		
	}

    /**
     * @return void
     */
    public function index()
    {
        $services = UserToir::current()->availableServices;

        $this->view('_header', ['title' => 'Привлечь службу']);
		$this->view('crash_request/index', compact('services'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function save()
    {
        $url = [];
        $services = [];

        if(!empty($_REQUEST['SERVICE_ID'])) {
            $services = UserToir::current()->availableServices()
                ->setFilter(['ID' => $_REQUEST['SERVICE_ID']])
                ->get();
        }

        foreach($services as $service) {
            $create = [
                'EQUIPMENT_ID' => $this->crash->EQUIPMENT_ID,
                'WORKSHOP_ID' => $this->crash->WORKSHOP_ID,
                'LINE_ID' => $this->crash->LINE_ID,
                'CRASH_ID' => $this->crash->ID,
                'SERVICE_ID' => $service->ID,
                'AUTHOR_ID' => UserToir::current()->id,
                'COMMENT' => $this->crash->DESCRIPTION,
            ];
            
            $id = ServiceRequest::create($create);

            $url[] = 'selected_id[]=' . $id;
        }

        $this->crash->STATUS = Crash::STATUS_SERVICE_REQUEST;
        $this->crash->save();
        
        header("Location: service_request.php?" . implode("&", $url));
    }

}