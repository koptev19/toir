<?php

class ServiceRequestService
{
    /**
     * @param int $id
     * @return void
     */
	public static function addOperation(ServiceRequest $serviceRequest, int $id)
    {
        $operationsId = $serviceRequest->OPERATIONS;
		$operationsId[] = $id;
        $serviceRequest->OPERATIONS = $operationsId;

        $serviceRequest->save();
    }

    /**
     * @param int $id
     * @return void
     */
	public static function addHistory(ServiceRequest $serviceRequest, int $id)
    {
        $historiesId = $serviceRequest->histories;
		$historiesId[] = $id;
        $serviceRequest->histories = $historiesId;

        $serviceRequest->USER_DONE = UserToir::current()->id;
        $serviceRequest->save();
    }

    /**
     * @return int
     */
	public static function countNotDone(): int
    {
        return ServiceRequest::filter([
            'USER_DONE' => null, 
            'SERVICE_ID' => UserToir::current()->availableServicesIds,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds
        ])->count();
    }

}