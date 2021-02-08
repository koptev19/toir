<?php

class Worktime extends ToirModel
{
    public const ACTION_PLAN = 'plan';
    public const ACTION_REPORT = 'report';

    public $table = 'worktimes';

    /**
     * @return Worker
     */
    public function worker(): ?Worker
    {
        return Worker::find($this->worker_id);
    }

    /**
     * @return Operation
     */
    public function operation(): ?Operation
    {
        return Operation::find($this->operation_id);
    }

}