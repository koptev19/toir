<?php

class Writeoff extends ToirModel
{
    public $table = 'writeoffs';

    /**
     * @return Operation
     */
    public function operation(): ?Operation
    {
        return Operation::find($this->operation_id);
    }

    /**
     * @return UserToir
     */
    public function user(): ?UserToir
    {
        return UserToir::find($this->user_id);
    }

}