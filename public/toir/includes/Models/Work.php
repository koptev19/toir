<?php

class Work extends ToirModel
{
    public $table = 'works';

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['WORK_ID' => $this->ID]);
    }

}