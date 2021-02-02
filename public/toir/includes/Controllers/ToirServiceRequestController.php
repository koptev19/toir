<?php

use Bitrix\Main\UI\Extension;

class ToirServiceRequestController extends ToirController
{

    /**
     * @return void
     */
    public function index()
    {
        $limit = $_REQUEST['limit'] ?? 50;
        $page = (int)$_REQUEST['page'] > 0 ? (int)$_REQUEST['page'] : 1;

        $serviceRequests = ServiceRequest::filter([
                'SERVICE_ID' => UserToir::current()->availableServicesIds(),
                'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds(),
            ])
            ->orderBy('ID', 'desc')
            ->offset($limit * ($page - 1))
            ->limit($limit)
            ->get();

        $equipmentsId = [];
        $receivingsId = [];
        foreach($serviceRequests as $key => $serviceRequest) {
            $equipmentsId[] = $serviceRequest->EQUIPMENT_ID;
            $receivingsId[] = $serviceRequest->RECEIVING_ID;
            $serviceRequest->author = UserService::getById($serviceRequest->CREATED_BY);
            $serviceRequest->executor = UserService::getById($serviceRequest->USER_DONE);
            $serviceRequests[$key] = $serviceRequest;
        }

        $this->view('_header', ['title' => 'Журнал заявок на ремонт']);
        $this->view('service_request/index', [
            'serviceRequests' => $serviceRequests,
            'equipments' => Equipment::filter(['ID' => $equipmentsId])->get(),
            'receivings' => AcceptItem::filter(['ID' => $receivingsId])->get(),
            'maxPage' => ServiceRequest::maxPage(),
        ]);

        $this->showFooter();
    }

    public function operationsByServiceRequest()
    {
        $operations = [];

        $serviceRequest = ServiceRequest::findAvailabled((int)$_REQUEST['service_request_id']);

        if($operationsId = $serviceRequest->histories) {
            $operationsId = is_array($operationsId) ? $operationsId : [$operationsId];
            $filterOperation = ['ID' => $operationsId];

            foreach(Plan::filter($filterOperation)->get() as $plan) {
                $operations[] = $plan->operations()->orderBy('PLANNED_DATE', 'asc')->first();
            }

            foreach(Operation::filter($filterOperation)->get() as $operation) {
                $operations[] = $operation;
            }

            foreach(History::filter($filterOperation)->get() as $history) {
                $history->TASK_RESULT = $history->RESULT;
                $operations[] = $history;
            }
        }

         $this->view('service_request/operations', [
            'serviceRequest' => $serviceRequest,
			'operations' => $operations,
        ]);
    }

}