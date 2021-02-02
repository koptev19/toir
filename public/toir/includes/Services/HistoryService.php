<?php

class HistoryService
{
    /**
     * @param array $create
     * @param string $source
     * 
     * @return int
     */
    public static function create(array $create, string $source): int
    {
        $create['SOURCE'] = $source;
        $create['COMPLETION_DATE'] = date('Y-m-d');
        $create["PLANNED_DATE"] = date("Y-m-d", strtotime($create["PLANNED_DATE"]));
        $create['author_id'] = UserToir::current()->id;

        $id = History::create($create);

        if($id) {
            return $id;
        }
    }

    /**
     * @param Operation $operation
     * @param string $source
     * @param array $addedProps = []
     * 
     * @return int
     */
    public static function createByOperation(Operation $operation, string $source, array $addedProps = []): int
    {
        $create = [
            'OPERATION_ID' => $operation->ID,
        ];

        foreach(['NAME', 'SERVICE_ID', 'NAME', 'WORK_ID', 'PLAN_ID', 'WORKSHOP_ID', 'LINE_ID', 'EQUIPMENT_ID', 'TYPE_OPERATION', 'OWNER', 'RECOMMENDATION', 'COMMENT', 'START_DATE', 'PLANNED_DATE', 'RESULT', 'COMMENT_NO_RESULT', 'PERIODICITY', 'WORK_TIME', 'REASON'] as $prop) {
            $create[$prop] = $addedProps[$prop] ?? $operation->$prop;
        }

        return self::create($create, $source);
    }

    /**
     * @param Operation $operation
     * @param string $source
     * @param int $time
     * 
     * @return int
     */
    public static function createByOperationNotDone(Operation $operation, string $source, int $time)
    {
        $addedProps = [
            'RESULT' => 'N',
            'COMMENT_NO_RESULT' => $operation->COMMENT_NO_RESULT . '. Перенесена с ' . d($operation->PLANNED_DATE) .' на ' . date('d.m.Y', $time),
        ];

        return self::createByOperation($operation, $source, $addedProps);
    }

    /**
     * @param Operation $operation
     * @param string $source
     * 
     * @return int
     */
    public static function createByOperationDone(Operation $operation, string $source): int
    {
        $addedProps = [
            'RESULT' => 'Y',
        ];

        return self::createByOperation($operation, $source, $addedProps);
    }

}