<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $DB;
global $USER;

require_once("includes/include.php");

//die();


foreach(Operation::all() as $operation) {
    $filterStops = [
                'DATE' => date("Y-m-d", strtotime($operation->PLANNED_DATE)),
                'STAGE' => StopProcess::STAGE_NEW,
				'LINE_ID' => $operation->LINE_ID
        ];
        
    $stop = Stop::filter($filterStops)->first();
	$time = new \Bitrix\Main\Type\DateTime($stop->DATE." 00:00:00");
	$time->add("-1 day");
	echo $time->format("Y-m-d") ;
	$s=StopProcess::find(322); 
	$s -> PLAN_DONE = $time->format("Y-m-d hhh"); 
	$s ->save();	
	break;
	dump ($time);

	if(!$stop)	continue;
	$service = Service::filter(["ID"=>$operation->SERVICE_ID])->first();
	dump($service);
	if(!$service)	continue;
	
	/*
	$stopService = StopProcessService::createIfNotExists($stop, $service);
	
	$stopService -> STAGE = $stop -> STAGE;

	if($stop -> STAGE >= Stop::STAGE_PLAN_DONE ){
		$stopService-> PLAN_STEP = 5;
	}

	if($stop -> STAGE == Stop::STAGE_REPORT_DONE ){
		$stopService-> PLAN_STEP = 4;
	}
	
	if($stop -> PLAN_TIME){
		$stopService-> PLAN_DONE = $stop->PLAN_TIME;
	}else{
		$stopService-> PLAN_DONE = $stop->DATE - 1; //!!!!!!!!!!!!!!!!
	}

	if($stop -> REPORT_TIME){
		$stopService-> REPORT_DONE = $stop->REPORT_TIME;
	}else{
		$stopService-> REPORT_DONE = $stop->DATE;
	}

	if($stop -> PLAN_USER_ID){
		$stopService-> PLAN_USER_ID = $stop->PLAN_USER_ID;
	}else{
		$stopService-> PLAN_USER_ID = TASK_USER_ID; //???????????????????
	}

	if($stop -> REPORT_USER_ID){
		$stopService-> REPORT_USER_ID = $stop->REPORT_USER_ID;
	}else{
		$stopService-> REPORT_USER_ID = TASK_USER_ID; //???????????????????
	}
	
	$stopService -> save();*/

	
}