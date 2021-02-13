<?php

class PushService
{
    /**
     * @param int $id
     * @return array|bool
     */
	public static function pushToLeft(int $id) 
    {
        $operation= Operation::find($id);
		$stop = Stop::filter([
                    'LINE_ID' => $operation->LINE_ID, 
                    '<=DATE' => date('Y-m-d', strtotime($operation->PLANNED_DATE))
                ])
                ->orderBy('DATE', 'desc')
                ->first();
		if (!$stop) {
				return ["error"=>'слева нет дня остановки для операции '];
        }
		
		$existOperations = Operation::filter([
                        'PLAN_ID' => $operation->PLAN_ID,
                        'PLANNED_DATE' => date('Y-m-d', strtotime($stop->DATE)),
                        '!ID' => $operation->ID,
                    ])->count();
		
                
        if($existOperations) {
            return ["error"=>'Прижатие невозможно! На дату '.$stop->DATE.' попадает несколько одинаковых операций'];
        }
		
		$operation->PLANNED_DATE = $stop->DATE;
        $operation->save();
		return true;
    }

    /**
     * @param int $id
     * @return array|bool
     */
	public static function pushToRight(int $id) 
    {
        $operation= Operation::find($id);
		$stop = Stop::filter([
                    'LINE_ID' => $operation->LINE_ID, 
                    '>=DATE' => date('Y-m-d', strtotime($operation->PLANNED_DATE))
                ])
                ->orderBy('DATE', 'asc')
                ->first();
		if (!$stop) {
				return ["error"=>'справа нет дня остановки для операции '];
        }
		
		$existOperations = Operation::filter([
                        'PLAN_ID' => $operation->PLAN_ID,
                        'PLANNED_DATE' => date('Y-m-d', strtotime($stop->DATE)),
                        '!ID' => $operation->ID,
                    ])->count();

                
        if($existOperations) {
            return ["error"=>'Прижатие невозможно! На дату '.$stop->DATE.' попадает несколько одинаковых операций'];
        }
		
		$operation->PLANNED_DATE = $stop->DATE;
        $operation->save();
		return true;
    }


	/**
     * Проверяет на наличие ошибок и если нет, то прижимает операции
     * Массив $operation обязательно должен состоять из операций одной плановой
     *
     * @param array $operations
     * 
     * @return string|null
	  */
    public static function checkAndPush(array $operations): ?string
    {
        $errorDate = self::check($operations);

        if(!$errorDate) {
            self::push($operations);
        }

        return $errorDate;
    }

    /**
     * @param array $operations
     * 
     * @return string|null
     */
    private static function check(array $operations): ?string
    {
        $error = null;

        $operationsInDate = [];

        foreach ($operations as $operation) {
            $stop = Stop::filter([
                    'LINE_ID' => $operation->LINE_ID, 
                    '<=DATE' => date('Y-m-d', strtotime($operation->PLANNED_DATE))
                ])
                ->orderBy('DATE', 'desc')
                ->first();

            if (!$stop) {
                $error = 'Нет дня остановки для операции '.$operation->NAME;
                continue;
            }
            
            if ($operation->PLAN_ID) {
                $existOperations = Operation::filter([
                        'PLAN_ID' => $operation->PLAN_ID,
                        'PLANNED_DATE' => date('Y-m-d', strtotime($stop->DATE)),
                        '!ID' => $operation->ID,
                    ])->count();
                
                if($existOperations || in_array($stop->DATE, $operationsInDate)) {
                    $error = "На дату " . $stop->DATE .' две одинаковых плановых операции';
                    break;
                }

                $operationsInDate[] = $stop->DATE;
            }
        }

        return $error;
    }


    /**
     * @param array $operations
     * 
     * @return void
     */
    private static function push(array $operations)
    {
        foreach ($operations as $operation) {
            $stop = Stop::filter([
                    'LINE_ID' => $operation->LINE_ID, 
                    '<=DATE' => date('Y-m-d', strtotime($operation->PLANNED_DATE))
                ])
                ->orderBy('DATE', 'desc')
                ->first();

            $dateProcess = DateProcessService::createIfNotExists($operation->service, $operation->workshop, $stop->DATE);

            $operation->PLANNED_DATE = $stop->DATE;
            $operation->DATE_PROCESS_ID = $dateProcess->ID;
            $operation->save();
        }
    }
}