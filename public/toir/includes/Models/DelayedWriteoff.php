<?php

class DelayedWriteoff extends ToirModel
{

    public $table = 'dalayed_writeoffs';


    /**
     * @return array
     */
    public function author(): ?UserToir
    {
        return UserService::getById($this->author_id);
    }

    /**
     * @return Operation|null
     */
    public function operation(): ?Operation
    {
        return Operation::find($this->OPERATION_ID);
    }


}