<?php

class AcceptItem extends ToirModel
{
    public const STAGE_NEW = 10;
    public const STAGE_DONE = 20;

    public $table = 'accept_histories';

    /**
     * @return ToirModelBuilder
     */
    public function serviceRequests(): ToirModelBuilder
    {
        return ServiceRequest::filter(['receiving_id' => $this->id]);
    }

    /**
     * @return Accept|null
     */
    public function accept(): ?Accept
    {
        return Stop::find($this->ACCEPT_ID);
    }
}