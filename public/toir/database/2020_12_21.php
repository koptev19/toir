<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $DB;
global $USER;

require_once($_SERVER["DOCUMENT_ROOT"] . "/toir/includes/include.php");

die();


foreach(Operation::all() as $operation) {
    $dateProcess = $operation->dateProcess;

    if($dateProcess) {    
        if($dateProcess->WORKSHOP_ID) {
            if($dateProcess->WORKSHOP_ID == $operation->WORKSHOP_ID) {
                continue;
            } else {
                $newDateProcess = DateProcessService::createIfNotExists($operation->service, $operation->workshop, $opertation->PLANNED_DATE);
                $newDateProcess->STAGE = $dateProcess->STAGE;
                $newDateProcess->COMMENT_REJECT = $dateProcess->COMMENT_REJECT;
                $newDateProcess->PLAN_DONE = $dateProcess->PLAN_DONE;
                $newDateProcess->PLAN_USER_ID = $dateProcess->PLAN_USER_ID;
                $newDateProcess->PLAN_APPROVE_ADMIN_ID = $dateProcess->PLAN_APPROVE_ADMIN_ID;
                $newDateProcess->PLAN_APPROVE_DATE = $dateProcess->PLAN_APPROVE_DATE;
                $newDateProcess->PLAN_REJECT_ADMIN_ID = $dateProcess->PLAN_REJECT_ADMIN_ID;
                $newDateProcess->PLAN_REJECT_DATE = $dateProcess->PLAN_REJECT_DATE;
                $newDateProcess->REPORT_DONE = $dateProcess->REPORT_DONE;
                $newDateProcess->REPORT_USER_ID = $dateProcess->REPORT_USER_ID;
                $newDateProcess->save();

                $operation->DATE_PROCESS_ID = $newDateProcess->ID;
                $operation->save();
            }
        } else {
            $dateProcess->WORKSHOP_ID = $operation->WORKSHOP_ID;
            $dateProcess->save();
        }
    } else {
        $newDateProcess = DateProcessService::createIfNotExists($operation->service, $operation->workshop, $opertation->PLANNED_DATE);
        $operation->DATE_PROCESS_ID = $newDateProcess->ID;
        $operation->save();
    }
}
