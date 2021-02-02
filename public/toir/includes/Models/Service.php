<?php

class Service extends ToirModel
{

    public $table = 'departments';

    protected $modify = [];

    /**
     * @return ToirModelBuilder
     */
    public function workshops(): ToirModelBuilder
    {
        return Workshop::filter(['ID' => $this->WORKSHOP_ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function histories(): ToirModelBuilder
    {
        return History::filter(['SERVICE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function dateProcesses(): ToirModelBuilder
    {
        return DateProcess::filter(['SERVICE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function serviceRequests(): ToirModelBuilder
    {
        return ServiceRequest::filter(['SERVICE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function plans(): ToirModelBuilder
    {
        return Plan::filter(['SERVICE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['SERVICE_ID' => $this->id]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function workers(): ToirModelBuilder
    {
        return Worker::filter(['SERVICE_ID' => $this->id])->orderBy('name', 'asc');
    }

}