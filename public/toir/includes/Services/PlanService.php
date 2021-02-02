<?php

class PlanService
{
    /**
     * @param Plan|int $plan
     * 
     * @return void
     */
    public static function delete($plan)
    {
        if(is_int($plan)) {
            $plan = Plan::find($plan);
        }

        $operations = $plan->operations()->get();

        foreach($operations as $operation) {
            $process = DateProcess::find($operation->DATE_PROCESS_ID);
			if(!$process || $process->STAGE < DateProcess::STAGE_REPORT_DONE){
            OperationService::deleteAndDeleteStop($operation);
        }
        }

        $plan->delete();
    }

    /**
     * @param Workshop $workshop
     * @return array[Plan]
     */
    public static function getNotDone(Workshop $workshop, array $filter)
    {
        $notDone = [];

        $planFilter = [
            'SERVICE_ID' => $filter['SERVICE_ID'] ? $filter['SERVICE_ID'] : UserToir::current()->availableServicesIds,
        ];

        if($filter['line']) {
            $planFilter['LINE_ID'] = $filter['line'];
        }

        if($filter['mechanism']) {
            $planFilter['EQUIPMENT_ID'] = $filter['mechanism'];
        }

        if($filter['name']) {
            $planFilter['%NAME'] = $filter['name'];
        }

        $plans = $workshop->plans()->setFilter($planFilter)->get();

        foreach($plans as $plan) {
            $lastDone = $plan->operations()
                ->setFilter(['TASK_RESULT' => 'Y'])
                ->orderBy('PLANNED_DATE', 'desc')
                ->first();
            
            if($lastDone) {
                $nextOperation = $plan->operations()
                    ->setFilter(['>PLANNED_DATE' => date("Y-m-d", strtotime($lastDone->PLANNED_DATE))])
                    ->orderBy('PLANNED_DATE', 'asc')
                    ->first();
                
                if($nextOperation) {
                    $time = strtotime($nextOperation->PLANNED_DATE);
                } else {
                    $time = strtotime($lastDone->PLANNED_DATE) + 60 * 60 * 24 * $plan->PERIODICITY;
                }
            } else {
                $time = strtotime($plan->START_DATE);
            }

            if($time < strtotime(date("Y-m-d"))) {
                $notDone[$plan->ID] = $plan;
            }
        }

        return $notDone;
    }

}