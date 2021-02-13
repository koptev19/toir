<?php

use Bitrix\Main\Loader; 
Loader::includeModule("highloadblock"); 
use Bitrix\Highloadblock as HL; 

class HighloadBlockService
{

    /**
     * @param int $blockId
     * @param array $data
     * @return int|null
     */
    public static function add(int $blockId, array $data): ?int
    {
        $class = self::getDataClass($blockId);
        $result = $class::add($data);
        return $result ? $result->getId() : null;
    }

    /**
     * @param int $blockId
     * @param array $filter
     * @param array $order = ['ID' => 'ASC']
     * @return array
     */
    public static function getList(int $blockId, array $filter, array $order = ['ID' => 'ASC']): array
    {
        $class = self::getDataClass($blockId);

        $dbResult = $class::getList([
            'select' => ['*'],
            'order' => $order,
            'filter' => $filter,
        ]);

        $rows = [];

        while ($row = $dbResult->Fetch()){
            $rows[$row['ID']] = $row;
        }

        return $rows;
    }

    /**
     * @param int $blockId
     * @param array $filter
     * @return array
     */
    public static function getTimes(int $blockId, array $filter): array
    {
        $allTimes = self::getList($blockId, $filter, ['UF_BEGINTIME' => 'ASC']);

        $times = [];

        foreach($allTimes as $time) {
            if(!isset($times[$time['UF_OPERATIONID']])) {
                $times[$time['UF_OPERATIONID']] = [];
            }

            $times[$time['UF_OPERATIONID']][$time['UF_WORKERID']] = [
                $time['UF_BEGINTIME'], 
                $time['UF_ENDTIME']
            ];
        }

        return $times;
    }


	/**
     * @param int $blockId
     * @param array $filter
     * @return array
     */
    public static function getTimesGroup(int $blockId, array $filter): array
    {
        $allTimes = self::getList($blockId, $filter, ['UF_BEGINTIME' => 'ASC']);

        $times = [];
		$return =[];

        foreach($allTimes as $time) {
            if(!isset($times[$time['UF_OPERATIONID']][$time['UF_GROUP']])) {
                $times[$time['UF_OPERATIONID']][$time['UF_GROUP']] = 1;
				$return[] = [
                    $time['UF_BEGINTIME'], 
                    $time['UF_ENDTIME']
				];
			}

	      }

        return $return;
    }

    /**
     * @param array $filter = []
     * @return array
     */
    public static function getWorkers(array $filter = []): array
    {
        $allWorkers = self::getList(HIGHLOAD_WORKER_BLOCK_ID, $filter);

        $workers = [];

        foreach($allWorkers as $worker) {
            $workers[$worker['ID']] = $worker['UF_NAME'];
        }

        return $workers;
    }

    /**
     * @return array
     */
    public static function getWorkersInOperations(int $blockId, $operationsIds): array
    {
        $workersIds = [];

        $times = self::getList($blockId, ['UF_OPERATIONID' => $operationsIds]);
        foreach($times as $time) {
            $workersIds[] = $time['UF_WORKERID'];
        }

        $filterWorkers = ['ID' => $workersIds];

        return self::getWorkers($filterWorkers);
    }

    /**
     * @param int $blockId
     * @param int $deleteId
     * @return void
     */
	public static function delete(int $blockId, int $deleteId)
    {
        $class = self::getDataClass($blockId);
        $class::delete($deleteId);
    }

    /**
     * @param int $blockId
     * @param array $filter
     * @return void
     */
	public static function deleteByFilter(int $blockId, array $filter)
    {
        $rows = HighloadBlockService::getList($blockId, $filter);		
        foreach($rows as $id => $row){
            self::delete($blockId, $id);	
	    }
    }

    /**
     * @param int $blockId
     * @param array $filter
     * @param array $order = ['ID' => 'ASC']
     * @return array|null
     */
    public static function first(int $blockId, array $filter, array $order = ['ID' => 'ASC']): ?array
    {
        $rows = self::getList($blockId, $filter, $order);       

        return count($rows) > 0 ? reset($rows) : null;
    }

    /**
     * @param int $blockId
     * @param int $id
     * @param array $update 
     * @return null
     */
    public static function update(int $blockId, int $id, array $update)
    {
        $class = self::getDataClass($blockId);
        $class::update($id,$update);
    }


	
	
	/**
     * @param int $blockId
     * @return string
     */
    private static function getDataClass(int $blockId): string
    {
        $hlblock = HL\HighloadBlockTable::getById($blockId)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        return $entity->getDataClass();
    }

}