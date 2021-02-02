<?php


class ToirLogReceivingController extends ToirController
{

    public function __construct()
    {
    }

    public function index()
    {
        $limit = (int)$_REQUEST['limit'] > 0 ? (int)$_REQUEST['limit'] : 50;
        $page = (int)$_REQUEST['page'] > 0 ? (int)$_REQUEST['page'] : 1;

        $acceptItems = AcceptItem::filter(['WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds()])
            ->orderBy('ID', 'desc')
            ->offset($limit * ($page - 1))
            ->limit($limit)
            ->get();

        $equipmentsId = [];
        foreach($acceptItems as $receiving) {
            $equipmentsId[] = $receiving->EQUIPMENT_ID;
        }
    
        $selectedReceiving = AcceptItem::findAvailabled((int)$_REQUEST['receiving']);
        $equipments = Equipment::filter(['ID' => $equipmentsId])->get();
        $maxPage = AcceptItem::maxPage();

        $this->view('_header', ['title' => 'Журнал приемки оборудования']);
        $this->view('log_receiving/index', compact('acceptItems', 'equipments', 'maxPage', 'selectedReceiving'));
        $this->showFooter();
    }
 
    public function done()
    {
        $acceptItem = AcceptItem::find((int)$_REQUEST['id']);
        $acceptItem->STAGE = AcceptItem::STAGE_DONE;
        $acceptItem->COMMENT_DONE = $_REQUEST['COMMENT_DONE'];
        $acceptItem->save();
        
        header("Location: log_receiving.php");
    }
 
}