<?php

class Accept extends ToirModel
{
    public const STATUS_NEW = 1;
    public const STATUS_RECEIVED = 5;
    public const STATUS_NOT_RECEIVED = 15;

    public $table = 'accepts';

    /**
     * @return ToirModelBuilder
     */
    public function items(): ToirModelBuilder
    {
        return AcceptItem::filter(['ACCEPT_ID' => $this->ID]);
    }

}