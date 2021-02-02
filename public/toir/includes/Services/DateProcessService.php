<?php

class DateProcessService
{

    /**
     * @param Service $service
     * @param Workshop $workshop
     * @param string|int $date
     * 
     * @return DateProcess
     */
    public static function createIfNotExists(Service $service, Workshop $workshop, $date): DateProcess
    {
        $time = is_int($date) ? $date : strtotime($date);
        $date = date('Y-m-d', $time);

        $dateProcess = self::getByServiceAndDate($service, $workshop, $date);

        if(!$dateProcess) {
            $dateProcessId = DateProcess::create([
                'SERVICE_ID' => $service->ID,
                'WORKSHOP_ID' => $workshop->ID,
                'DATE' => $date,
                'STAGE' => DateProcess::STAGE_NEW,
            ]);

            $dateProcess = DateProcess::find($dateProcessId);
        }

        return $dateProcess;
    }

    /**
     * @param Service $service
     * @param Workshop $workshop
     * @param string|int $date
     * 
     * @return DateProcess|null
     */
    public static function getByServiceAndDate(Service $service, Workshop $workshop, $date): ?DateProcess
    {
        $time = is_int($date) ? $date : strtotime($date);
        $date = date('Y-m-d', $time);

        return $service->dateProcesses()
            ->setFilter(['DATE' => $date, 'WORKSHOP_ID' => $workshop->ID])
            ->first();
    }

    /**
     * @param Service $service
     * @param Workshop $workshop
     * @param string|int $date
     * 
     * @return void
     */
    public static function deleteIfEmpty(Service $service, Workshop $workshop, $date)
    {
        $dateProcess = self::getByServiceAndDate($service, $workshop, $date);
        if(count($dateProcess->operations) == 0)
        {
            $dateProcess->delete();
        }
    }


	/**
     * @param Service $service
     * @return boolean
     */
    public static function outOfDate(DateProcess $DateProcess)
    {
         if ((strtotime($DateProcess->DATE."14:59:59")-time())/60/60/24 > 1){
			return true;
		  }else{
			return false;
		  }		
    }

	public static function reportOutOfDate(DateProcess $dateProcess)
    {
         $daysBetween=(time()-strtotime($dateProcess->DATE))/60/60/24; 
				if ($daysBetween < 2){
					return false;
                }elseif($daysBetween>2 && $daysBetween<4){
                    $dayNumber=date("N", strtotime($dateProcess->DATE));
                    if($dayNumber==5 && $daysBetween<4){
                        return false;
                    }elseif($dayNumber==6 && $daysBetween<3){
                        return false;		
                    }else{
                        
                        return true;	
                    }		
                }else{
                    return true;	
                }				

    }


}

