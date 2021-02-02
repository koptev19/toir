<?php

class DateProcess extends ToirModel
{

    public $table = 'date_processes';

    public const STAGE_NEW = 1;
    public const STAGE_PLAN_REJECTED = 5;
    public const STAGE_PLAN_DONE = 10;
    public const STAGE_PLAN_APPROVED = 15;
    public const STAGE_REPORT_DONE = 20;

    public static $stages = [
        self::STAGE_NEW => 'Пройти планирование',
        self::STAGE_PLAN_REJECTED => 'Пройти планирование',
        self::STAGE_PLAN_DONE => 'Планирование на согласовании',
        self::STAGE_PLAN_APPROVED => 'Отчет не пройден',
        self::STAGE_REPORT_DONE => 'Отчет пройден',
    ];

    /**
     * @return ToirModelBuilder
     */
    public function verbalStage(): string
    {
        return self::$stages[$this->STAGE];
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['DATE_PROCESS_ID' => $this->ID]);
    }

    /**
     * @param ?array $filter = null
     * 
     * @return ToirModelBuilder
     */
    public static function filter(?array $filter = null): ToirModelBuilder
    {
        if(isset($filter['DATE'])) {
            $filter['DATE'] = date('Y-m-d', strtotime($filter['DATE']));
        }

        return parent::filter($filter);
    }


}