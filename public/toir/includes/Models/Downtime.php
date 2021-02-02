<?php

class Downtime extends ToirModel
{
    public $table = 'downtimes';

    public const STAGE_NEW = 1;
	public const STAGE_SERVICE = 10;
	public const STAGE_EQUIPMENT = 20;
	public const STAGE_COMMENT = 30;
	public const STAGE_OPERATIONS = 40;
	public const STAGE_DONE = 100;


}