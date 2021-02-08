<?php

class Writeoff extends ToirModel
{
    public $table = 'writeoffs';

    /**
     * @return Operation
     */
    public function operation(): ?Operation
    {
        return Operation::filter(['ID' => $this->OPERATION_ID])->withTrashed()->first();
    }

    /**
     * @return UserToir
     */
    public function user(): ?UserToir
    {
        return UserToir::find($this->user_id);
    }

}