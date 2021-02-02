<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $DB;
global $USER;

require_once($_SERVER["DOCUMENT_ROOT"] . "/toir/includes/include.php");

die();


foreach(Operation::all() as $operation) {
	$stop = Stop::getByLineDate($operation->LINE_ID, $operation->PLANNED_DATE);
	if(!$stop)	continue;

	$dateProcess = DateProcessService::createIfNotExists($operation->service, $operation->DATE);
	
	$dateProcess->STAGE = $stop->STAGE;

	if($stop->PLAN_TIME){
		$dateProcess->PLAN_DONE = $stop->PLAN_TIME;
	}else{
		$time = new \Bitrix\Main\Type\DateTime($stop->DATE." 00:00:00");
		$time->add("-1 day");
		$dateProcess->PLAN_DONE = $time->format("Y-m-d"); 
	}

	$dateProcess->REPORT_DONE = $stop->REPORT_TIME ? $stop->REPORT_TIME : $stop->DATE;
	$dateProcess->PLAN_USER_ID = $stop->PLAN_USER_ID ? $stop->PLAN_USER_ID : TASK_USER_ID;
	$dateProcess->REPORT_USER_ID = $stop->REPORT_USER_ID ? $stop->REPORT_USER_ID : TASK_USER_ID;
	
	$dateProcess -> save();

	$operation->DATE_PROCESS_ID = $dateProcess->ID;
	$operation->save();	
}