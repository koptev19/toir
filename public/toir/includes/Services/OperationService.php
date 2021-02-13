<?php

class OperationService
{
    /**
     * @param Plan $plan
     * @param string $firstDate
     * @param string $lastDate
     * 
     * @return void
     */
    public static function createByPlanInRange(Plan $plan, string $firstDate, string $lastDate)
    {
        if($plan->PERIODICITY <= 0) {
            return;
        }

        $period = $plan->PERIODICITY * 60 * 60 * 24;

        $lastOperation = $plan->operations()
            ->orderBy('PLANNED_DATE', "desc")
            ->first();

        $time = $lastOperation 
            ? strtotime($lastOperation->PLANNED_DATE) + $period
            : strtotime($plan->START_DATE);

        while($time < strtotime($lastDate)) {
            if($time >= strtotime($firstDate)) {
                self::createByPlan($plan, $time);
            }

            $time = $time + $period;
        }
    }

    /**
     * @param Plan $plan
     * @param int $time
     * @param array $newFields = []
     * 
     * @return int
     */
    public static function createByPlan(Plan $plan, int $time, array $newFields = []): int
    {
        $fields = [];
        $fields["SERVICE_ID"]         = $plan->SERVICE_ID;
        $fields["NAME"]               = $newFields['NAME'] ?? $plan->NAME;
        $fields["PLAN_ID"]            = $plan->ID;
        $fields["WORKSHOP_ID"]        = $plan->WORKSHOP_ID;
        $fields["LINE_ID"]            = $plan->LINE_ID;
        $fields["EQUIPMENT_ID"]       = $plan->EQUIPMENT_ID;
        $fields["TYPE_OPERATION"]     = $plan->TYPE_OPERATION;
        $fields["RECOMMENDATION"]     = $plan->RECOMMENDATION;
        $fields["PERIODICITY"]        = $plan->PERIODICITY;
        $fields["PLANNED_DATE"]       = date('Y-m-d', $time);
        $fields["START_DATE"]         = $fields["PLANNED_DATE"];
        $fields["CRASH_ID"]           = $plan->CRASH_ID;
        $fields["REASON"]             = $plan->REASON;
        $fields["DATE_PROCESS_ID"]    = $newFields['DATE_PROCESS_ID'] ?? null;

        $operationId = Operation::create($fields);

        TaskService::updateChecklistItems($fields["PLANNED_DATE"], intval($fields["LINE_ID"]));

        return $operationId;
    }

    /**
     * @param string $sessionKey
     * @param array $addedFields = []
     * 
     * @return array
     */
    public static function createGroup(string $sessionKey, array $addedFields = [], bool $stopdatecreate = true): array
    {
        $result = [];

        $operations = $_SESSION[$sessionKey] ?? [];
        foreach($operations as $operation) {
            $equipment = Equipment::find((int) $operation['EQUIPMENT_ID']);
            $service = service::find((int) $operation['SERVICE_ID']);
            if($stopdatecreate){
                $dateProcess = DateProcessService::createIfNotExists($service, $equipment->workshop, $operation["PLANNED_DATE"]);
                StopService::createIfNotExists($equipment->LINE_ID, strtotime($operation["PLANNED_DATE"]));
            }

            $fields = [];
            $fields["SERVICE_ID"]         = $operation['SERVICE_ID'];
            $fields["WORK_ID"]            = $operation['WORK_ID'] ? $operation['WORK_ID'] : null;
            $fields["NAME"]               = $operation['NAME'];
            $fields["WORKSHOP_ID"]        = $equipment->WORKSHOP_ID;
            $fields["LINE_ID"]            = $equipment->LINE_ID;
            $fields["EQUIPMENT_ID"]       = $operation['EQUIPMENT_ID'];
            $fields["TYPE_OPERATION"]     = $operation['TYPE_OPERATION_ENUM'];
            $fields["RECOMMENDATION"]     = $operation['RECOMMENDATION'];
            $fields["PLANNED_DATE"]       = date("Y-m-d", strtotime($operation['PLANNED_DATE']));
            $fields["START_DATE"]         = $fields["PLANNED_DATE"];
            $fields["REASON"]             = $operation['REASON'];
            $fields["DATE_PROCESS_ID"]    = $stopdatecreate ? $dateProcess->ID : false;

            foreach($addedFields as $fieldName => $fieldValue) {
                $fields[$fieldName] = $fieldValue;
            }

            $operationId = Operation::create($fields);

            $result[$operation['ID']] = $operationId;
        }

        unset($_SESSION[$sessionKey]);

        return $result;
    }

    /**
     * @param Operation $operation
     * @param int $time
     * 
     * @return void
     */
    public static function updatePlannedDate(Operation $operation, int $time)
    {
        $newDate = date('Y-m-d', $time);

        // Если новая дата не совпадает со старой
        if($newDate == $operation->PLANNED_DATE) {
            return;
        }

        // У плановой операции обновляем поле NEXT_EXECUTION_DATE (следующая дата выполнения)
        if ($plan = $operation->plan) {
            if($plan->NEXT_EXECUTION_DATE == $operation->PLANNED_DATE) {
                $plan->NEXT_EXECUTION_DATE = $newDate;
                $plan->save();
            }
        }

        $dateProcess = DateProcessService::createIfNotExists($operation->service, $operation->workshop, $newDate);

        // Устанавливаем новую дату
        $operation->PLANNED_DATE = $newDate;
        $operation->WORK_TIME = '';
        $operation->DATE_PROCESS_ID = $dateProcess->ID;
        $operation->save();

        // Обновляем чеклист по старой дате и по новой
        TaskService::updateChecklistItems($operation->PLANNED_DATE, $operation->LINE_ID);
        TaskService::updateChecklistItems($newDate, $operation->LINE_ID);
    }

    /**
     * @param Operation|int $operation
     * 
     * @return void
     */
    public static function deleteAndDeleteStop($operation)
    {
        if(is_int($operation)) {
            $operation = Operation::find($operation);
        }

        $operation->delete();

        StopService::deleteIfEmpty($operation->LINE_ID, $operation->PLANNED_DATE);
        DateProcessService::deleteIfEmpty($operation->service, $operation->workshop, $operation->PLANNED_DATE);
    }

    /**
     * @param Operation|int $operation
     * 
     * @return void
     */
    public static function checkDoublePlan(Operation $operation, int $time)
    {
        if ($operation->PLAN_ID) {
            $filter = [
                'PLAN_ID' => $operation->PLAN_ID,
                'PLANNED_DATE' => date('Y-m-d', $time),
                '!ID' => $operation->ID,
            ];
            $count = Operation::filter($filter)->count();
            if($count > 0) {
                $operation->delete();
            }
        }
    }

    /**
     * @param Workshop $workshop
     * 
     * @return array[Operation]
     */
    public static function getNotDone(Workshop $workshop, array $filter):array
    {
        $notDone = [];

        $operationFilter = [
            '!TASK_RESULT' => 'Y',
            'SERVICE_ID' => $filter['SERVICE_ID'] ? $filter['SERVICE_ID'] : UserToir::current()->availableServicesIds,
        ];

        if($filter['line']) {
            $operationFilter['LINE_ID'] = $filter['line'];
        }

        if($filter['mechanism']) {
            $operationFilter['EQUIPMENT_ID'] = $filter['mechanism'];
        }

        if($filter['name']) {
            $operationFilter['%NAME'] = $filter['name'];
        }

        $operations = $workshop->notPlans()
            ->setFilter($operationFilter)
            ->get();
        
        foreach($operations as $operation) {
            $time = strtotime($operation->PLANNED_DATE);

            if($time < strtotime(date("Y-m-d"))) {
                $notDone[$operation->ID] = $operation;
            }
        }

        return $notDone;
    }

    /**
     * @param Operation $operation
     * 
     * @return int
     */
    public static function copyOperation(Operation $operation, array $newFields = []): int
    {
        $create = [
            'NAME' => $operation->NAME,
            'WORK_ID' => $operation->WORK_ID,
            'PLAN_ID' => $operation->PLAN_ID,
            'WORKSHOP_ID' => $operation->WORKSHOP_ID,
            'LINE_ID' => $operation->LINE_ID,
            'EQUIPMENT_ID' => $operation->EQUIPMENT_ID,
            'TYPE_OPERATION' => $operation->TYPE_OPERATION,
            'OWNER' => $operation->OWNER,
            'RECOMMENDATION' => $operation->RECOMMENDATION,
            'COMMENT' => $operation->COMMENT,
            'WORK_TIME' => $operation->WORK_TIME,
            'START_DATE' => $operation->START_DATE,
            'PLANNED_DATE' => $operation->PLANNED_DATE,
            'TASK_RESULT' => $operation->TASK_RESULT,
            'LAST_DATE_FROM_CHECKLIST' => $operation->LAST_DATE_FROM_CHECKLIST,
            'COMMENT_NO_RESULT' => $operation->COMMENT_NO_RESULT,
            'PERIODICITY' => $operation->PERIODICITY,
            'CRASH_ID' => $operation->CRASH_ID,
            'SERVICE_ID' => $operation->SERVICE_ID,
            'REASON' => $operation->REASON,
            'SOURCE_OPERATION_ID' => $operation->SOURCE_OPERATION_ID ?: $operation->ID,
        ];

        foreach($newFields as $fieldKey => $fieldValue) {
            $create[$fieldKey] = $fieldValue;
        }

        return Operation::create($create);
    }

}