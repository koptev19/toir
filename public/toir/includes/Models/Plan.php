<?php

class Plan extends ToirModel
{

    public const TYPE_TO_ENTO = 'ento';
    public const TYPE_TO_EМTO = 'emto';
    public const TYPE_TO_TO1 = 'to1';
    public const TYPE_TO_TO2 = 'to2';

    private static $typesTo = [
        self::TYPE_TO_ENTO => 'ЕНТО',
        self::TYPE_TO_EМTO => 'ЕМТО',
        self::TYPE_TO_TO1 => 'ТО-1',
        self::TYPE_TO_TO2 => 'ТО-2',
    ];

    public $table = 'plans';

    protected $modify = [
        'PERIODICITY' => 'int',
    ];

    /**
     * @return array
     */
    public static function getTypesTo(): array
    {
        return self::$typesTo;
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getVerbalTypeTo(string $type): string
    {
        return self::$typesTo[$type];
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['PLAN_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function operationsNotDone(): ToirModelBuilder
    {
        return $this->OPERATIONS_NOT_DONE 
            ? Operation::filter(['ID' => json_decode($this->OPERATIONS_NOT_DONE)]) 
            : (new ToirModelBuilder(new Operation))->setFilter(['id' => 0]);
    }

    /**
     * @return Crash|null
     */
    public function crash(): ?Crash
    {
        return Crash::find($this->CRASH_ID);
    }

    /**
     * @return ToirModelBuilder
     */
    public function histories(): ToirModelBuilder
    {
        return History::filter(['PLAN_ID' => $this->ID]);
    }

    /**
     * @return int
     */
    public function getLate(): int
    {
        return $this->LAST_DATE_FROM_CHECKLIST 
            ? floor((time() - (strtotime($this->LAST_DATE_FROM_CHECKLIST) + $this->PERIODICITY * 60 * 60 * 24)) / (60 * 60 * 24))
            : floor((time() - strtotime($this->START_DATE)) / (60 * 60 * 24));
    }

    /**
     * @return int
     */
    public function getLateDate(): string
    {
        return $this->LAST_DATE_FROM_CHECKLIST 
            ? date("d.m.Y", (strtotime($this->LAST_DATE_FROM_CHECKLIST) + $this->PERIODICITY * 60 * 60 * 24))
            : $this->START_DATE;
    }


}