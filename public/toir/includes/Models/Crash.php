<?php

class Crash extends ToirModel
{

    public const STATUS_NEW = 1;
    public const STATUS_DESCRIPTION = 5;
    public const STATUS_SERVICE_REQUEST = 7;
    public const STATUS_OPERATIONS = 10;
    public const STATUS_DECISION = 15;
    public const STATUS_DONE = 20;

    public $table = 'crashes';

    protected $modify = [];

    protected $files = ['DOCUMENTS'];

    /**
     * @param string $status
     * @return string
     */
    public static function verbalStatus(string $status): string
    {
        switch ($status) {
            case self::STATUS_NEW:
                return "Опишите аварию";
                break;
            case self::STATUS_DESCRIPTION:
                return "Необходимо привлечь службу";
                break;
            case self::STATUS_SERVICE_REQUEST:
                return "Добавьте операции по устранению аварии";
                break;
            case self::STATUS_OPERATIONS:
                return "Добавьте решение по предотвращению аварии в будущем";
                break;
            case self::STATUS_DECISION:
                return "Добавьте операции по предотвращению аварии в будущем";
                break;
            case self::STATUS_DONE:
                return "Завершена";
                break;
            default:
                return "";
        }
    }

    /**
     * @return ToirModelBuilder
     */
    public function serviceRequests(): ToirModelBuilder
    {
        return ServiceRequest::filter(['CRASH_ID' => $this->ID]);
    }

    /**
     * @return Stop|null
     */
    public function stop(): ?Stop
    {
        return Stop::find($this->STOP_ID);
    }

    /**
     * @return ToirModelBuilder
     */
    public function plans(): ToirModelBuilder
    {
        return Plan::filter(['CRASH_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['CRASH_ID' => $this->id, 'PLAN_ID' => null]);
    }

    /**
     * @return array
     */
    public function histories(): array
    {
        $filterTemplate = [
            'SERVICE_ID' => UserToir::current()->availableServicesIds,
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
        ];

        $histories = [];
        foreach($this->serviceRequests as $serviceRequest) {
            $filter = array_merge($filterTemplate, ['id' => $serviceRequest->histories]);
            $serviceRequestHistories = History::filter($filter)->get();

            foreach($serviceRequestHistories as $h) {
                $h->serviceRequestId = $serviceRequest->ID;
                $histories[$h->id] = $h;
            }
        }

        return $histories;
    }
}