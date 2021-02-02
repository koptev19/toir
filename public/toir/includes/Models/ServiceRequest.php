<?php

class ServiceRequest extends ToirModel
{

    public $table = 'service_requests';

    public $relations = [
        'operations' => ['table' => 'operations_service_requests', 'foreign_key' => 'operation_id', 'owner_key' => 'service_request_id'],
        'histories' => ['table' => 'histories_service_requests', 'foreign_key' => 'history_id', 'owner_key' => 'service_request_id'],
    ];

    /**
     * @return Receiving|null
     */
    public function receiving(): ?Receiving
    {
        return Receiving::find($this->RECEIVING_ID);
    }

    /**
     * @return Crash|null
     */
    public function crash(): ?Crash
    {
        return Crash::find($this->CRASH_ID);
    }

    /**
     * @return UserToir|null
     */
    public function author(): ?UserToir
    {
        return UserToir::find($this->author_id);
    }

}