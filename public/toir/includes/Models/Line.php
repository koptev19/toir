<?php

class Line extends Equipment
{

    public const LEVEL = 2;

    /**
     * @param int $id
     * 
     * @return ?ToirModel
     */
    public static function find($id): ?ToirModel
    {
        $line = parent::find($id);
        if ($line->TYPE !== Equipment::TYPE_LINE || $line->LEVEL != self::LEVEL) {
            $line = null;
        }
        return $line;
    }

    /**
     * @param ?array $filter = null
     * 
     * @return ToirModelBuilder
     */
    public static function filter(?array $filter = null): ToirModelBuilder
    {
        $filter = empty($filter) ? [] : $filter;
        $filter['TYPE'] = Equipment::TYPE_LINE;
        $filter['LEVEL'] = self::LEVEL;
        return parent::filter($filter);
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        return self::filter()->get();
    }

    /**
     * @param array $fields
     * 
     * @return null|int
     */
    public static function create(array $fields): ?int
    {
        $fields['TYPE'] = Equipment::TYPE_LINE;
        $fields['LEVEL'] = self::LEVEL;
        unset($fields['LINE_ID']);
        $id = parent::create($fields);
        if($id) {
            $line = self::find($id);
            $line->LINE_ID = $id;
            $line->save();
        }
        return $id;
    }

    
    /**
     * @return ToirModelBuilder
     */
    public function crashes(): ToirModelBuilder
    {
        return Crash::filter(['LINE_ID' => $this->ID]);
    }

    
    /**
     * @return ToirModelBuilder
     */
    public function stops(): ToirModelBuilder
    {
        return Stop::filter(['LINE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function histories(): ToirModelBuilder
    {
        return History::filter(['LINE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function serviceRequests(): ToirModelBuilder
    {
        return ServiceRequest::filter(['LINE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function plans(): ToirModelBuilder
    {
        return Plan::filter(['LINE_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['LINE_ID' => $this->id]);
    }

}