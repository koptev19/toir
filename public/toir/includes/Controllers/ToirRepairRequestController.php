<?php

use Bitrix\Main\UI\Extension;

class ToirRepairRequestController extends ToirController
{
    /**
     * @var Receiving
     */
    public $receiving;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->receiving = AcceptItem::findAvailabled((int)$_REQUEST['log_receiving_id']);
		if(!$this->receiving) {
			die('Не задана приемка оборудования');
		}
    }

    /**
     * @return void
     */
    public function index()
    {
        $services = UserToir::current()->availableServices;
        $this->view('_header', ['title' => 'Привлечь службу']);
        $this->view('repair_request/index', compact('services'));
        $this->showFooter();
    }

    /**
     * @return void
     */
    public function save()
    {
        $services = [];

        if(!empty($_REQUEST['SERVICE_ID'])) {
            $services = UserToir::current()->availableServices()
                ->setFilter(['ID' => $_REQUEST['SERVICE_ID']])
                ->get();
        }

        $ids = [];

        foreach($services as $service) {
            $create = [
                'EQUIPMENT_ID' => $this->receiving->EQUIPMENT_ID,
                'WORKSHOP_ID' => $this->receiving->WORKSHOP_ID,
                'LINE_ID' => $this->receiving->LINE_ID,
                'RECEIVING_ID' => $this->receiving->ID,
                'SERVICE_ID' => $service->ID,
                'AUTHOR_ID' => UserToir::current()->id,
                'COMMENT' => $_REQUEST['COMMENT'],
            ];

            $ids[] = ServiceRequest::create($create);
        }
    
        $this->receiving->STAGE = AcceptItem::STAGE_DONE;
        $this->receiving->save();
        
        $this->view('repair_request/save', []);
    }


}