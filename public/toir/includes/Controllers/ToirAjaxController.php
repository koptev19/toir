<?php


class ToirAjaxController extends ToirController
{

	public function getNodes()
    {
        $parentId = $_REQUEST['PARENT_ID'] ? $_REQUEST['PARENT_ID'] : null;
        $nodes = Equipment::filter([
            'PARENT_ID' => $parentId,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds(),
        ])
        ->get();

        $items = [];
        foreach ($nodes as $node) {
            $items[$node->ID] = [
                'name' => $node->NAME,
                'isLine' => $node->TYPE_ENUM == Equipment::TYPE_LINE,
                'countChildren' => Equipment::filter(['PARENT_ID' => $node->ID])->count(),
            ];
        }

		echo json_encode($items);
    }

	
	public function getStops()
    {
        $line = Line::findAvailabled((int)$_POST['LINE_ID']);
        $service = Service::find((int)$_POST['SERVICE_ID']);

        $date1 = prevMonth();
        $date2 = currentMonth();
        $date3 = nextMonth();

        $stops = array_merge(
            Stop::getByLineInMonth($line->ID, $date1['Y'], $date1['m']),
            Stop::getByLineInMonth($line->ID, $date2['Y'], $date2['m']),
            Stop::getByLineInMonth($line->ID, $date3['Y'], $date3['m'])
        );

        $result = [];
        foreach($stops as $date => $stop) {
            // Если задана служба, то ищем по ней DateProcess. 
            // А если не задана, то берём любую на эту дату с максимальной STAGE
            if($service) {
                $dateProcess = DateProcessService::getByServiceAndDate($service, $line->workshop, $date);
            } else {
                $dateProcess = DateProcess::filter(['DATE' => $date, 'WORKSHOP_ID' => $line->WORKSHOP_ID])
                    ->orderBy('STAGE', 'DESC')
                    ->first();
            }
            $result[$date] = [
                'id' => $stop->ID,
                'stage' => $dateProcess ? $dateProcess->STAGE : 0,
            ];
        }

		echo json_encode($result);
    }

	public function getStopDates()
    {
        $lineId = (int)$_REQUEST['line'];
        $year = (int)$_REQUEST['year'];
        $month = (int)$_REQUEST['month'];

        Line::findAvailabled($lineId);

        $stops = Stop::getByLineInMonth($lineId, $year, $month);

        $dates = [];
        foreach($stops as $date => $stop) {
            $dates[] = date("Y-m-d", strtotime($date));
        }

    	echo json_encode(compact('dates', 'year', 'month'));
    }

	public function getLines()
    {
        $workshop = Workshop::find((int)$_REQUEST['workshop']);
        UserToir::current()->checkWorkshopOrFail($this->workshop->ID);
        
        $result = [];
        foreach($workshop->lines as $line) {
            $result[$line->ID] = $line->NAME;
        }

		echo json_encode($result);
    }

	public function getMechanismes()
    {
        $line = Line::findAvailabled((int)$_REQUEST['line']);

        $mechanismes = Equipment::filter([
            'LINE_ID' => $line->ID,
            'TYPE' => Equipment::TYPE_MECHANISM
        ])->get();
        
        $result = [];
        foreach($mechanismes as $mechanism) {
            $result[$mechanism->ID] = $mechanism->NAME;
        }

		echo json_encode($result);
    }

	public function pushToLeft()
    {
        $result=PushService::pushToLeft((int)$_REQUEST['operation_id']);
		echo json_encode($result);
    }

	public function pushToRight()
    {
        $result=PushService::pushToRight((int)$_REQUEST['operation_id']);
		echo json_encode($result);
    }

	public function createStop()
    {
        $lineId = (int)$_REQUEST['lineId'];
        $time = strtotime($_REQUEST['date']);

        StopService::createIfNotExists($lineId, $time);

		echo json_encode([]);
    }

	public function getDateProcess()
    {
        $mode = $_REQUEST['mode'] ?? 'plan';

        $workshops = [];
        $services = [];

        foreach(UserToir::current()->availableServices as $service) {
            $services[$service->ID] = [
                'id' => $service->ID,
                'name' => $service->NAME,
            ];
        }
        
        foreach(UserToir::current()->availableWorkshops as $workshop) {
            $workshops[$workshop->ID] = [
                'id' => $workshop->ID,
                'name' => $workshop->NAME,
            ];
        }
        
		$filter = [
            'DATE' => date("Y-m-d", strtotime($_REQUEST['date'])),
            'SERVICE_ID' => array_keys ($services),
			'WORKSHOP_ID' => array_keys ($workshops)
        ];

        $dateProcesses = DateProcess::filter($filter)->get();

        foreach($dateProcesses as $dateProcess) {
			if($mode == 'report') {
                switch($dateProcess->STAGE) {
                    case DateProcess::STAGE_NEW:
                    case DateProcess::STAGE_PLAN_REJECTED:
                        $cellValue = '<a href="master_plan_date.php?service=' . $dateProcess->SERVICE_ID . '&date=' . $_REQUEST['date'] . '" target=_blank>' . $dateProcess->verbalStage() . '</a>';
                        break;
                    case DateProcess::STAGE_PLAN_DONE:
                        $cellValue = $dateProcess->verbalStage();
                        break;
                    case DateProcess::STAGE_PLAN_APPROVED:
                        $cellValue = '<span class="text-danger">' . $dateProcess->verbalStage() . '</span>';
                        break;
                    case DateProcess::STAGE_REPORT_DONE:
                        $cellValue = '<span class="text-success">' . $dateProcess->verbalStage() . '</span>';
                        break;
                }
			}else{
                $cellValue = ($dateProcess->STAGE >= DateProcess::STAGE_PLAN_DONE) 
                    ? '<span class="text-success">Планирование пройдено</span>' 
                    : '<span class="text-danger">Планирование не пройдено</span>'; 	
            }
            $result[$dateProcess->WORKSHOP_ID][$dateProcess->SERVICE_ID] = $cellValue;
			$workshopsRes[$dateProcess->WORKSHOP_ID] = $workshops[$dateProcess->WORKSHOP_ID];
            $servicesRes[$dateProcess->SERVICE_ID] = $services[$dateProcess->SERVICE_ID];
        }
		
		echo json_encode(["table"=>$result,"services"=>$services,"workshops"=>$workshopsRes]);
    }

	public function getParents(){
		$element =  Equipment::find($_REQUEST['id']);
		$parents = $element->parents();
        $path = '';
		foreach($parents as $k=>$v )
		{
			$path.= $v->NAME . ">";
			$parents[$k]=1;
		}
		$path.= $element->NAME;
		echo json_encode(["parents"=>$parents,"path"=>$path]);
	}	

	public function addWorker(){
        $create = [
            "NAME" => $_REQUEST['workerName'],
			"SERVICE_ID" => $_REQUEST['serviceId']
        ];
        $id = Worker::create($create);
		echo json_encode(["id"=>$id,"name"=>$_REQUEST['workerName']]);
	}	

	public function deleteWorker(){
		HighloadBlockService::update(HIGHLOAD_WORKER_BLOCK_ID, $_REQUEST['id'], 
			["UF_DELETED" => true]
			);
	}	

}