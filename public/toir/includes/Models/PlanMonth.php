<?php

class PlanMonth extends ToirModel
{

    const STAGE_NEW = 1;
    const STAGE_PROCESS = 10;
    const STAGE_DONE = 20;

    public $table = 'plan_monthes';

}