<?php

class ToirAcceptItemController extends ToirController
{

    public function __construct()
    {
    }

    /**
     * @return void
     */
    public function index()
    {
        $accept = Accept::find((int)$_REQUEST['id']);
	   if(!$accept) {
		   echo "<h3>Ссылка устарела!</h3>";
		   die;
	   }	
        $equipment = Equipment::filter(['ID' => $accept->EQUIPMENT_ID])->first();
        $this->view('_header', ['title' => 'Прием оборудования']);
        $this->view('accept_item/index',compact('accept','equipment'));
        $this->showFooter();
    }

    public function close()
    {
        $this->view('accept_item/close');
    }


    public function store()
    {
        $accept = Accept::find((int)$_REQUEST['id']);

        $create = [
            'ACCEPT_ID' => $accept->ID,
            'WORKSHOP_ID' => $accept->WORKSHOP_ID,
            'LINE_ID' => $accept->LINE_ID,
            'EQUIPMENT_ID' => $accept->EQUIPMENT_ID,
            'USER_SECOND_NAME' => $_REQUEST['USER_NAME'],
			'STAGE' => $_REQUEST['noComment'] ? AcceptItem::STAGE_DONE : AcceptItem::STAGE_NEW,
			'COMMENT' => $_REQUEST['COMMENT'],
        ];
        if($files = FileService::uploadMultiple('files')) {
            $create['FILES'] = json_encode($files);
        }
        $id = AcceptItem::create($create);

		header("Location: ?action=close&id=" . $_REQUEST['id']);
    }


}