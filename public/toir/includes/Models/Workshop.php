<?php

class Workshop extends Equipment
{

    public const LEVEL = 1;

    /**
     * @param int $id
     * 
     * @return ?Workshop
     */
    public static function find($id): ?Workshop
    {
        $workshop = parent::find($id);
        if ($workshop->TYPE !== Equipment::TYPE_WORKSHOP || $workshop->LEVEL === self::LEVEL) {
            $workshop = null;
        }
        return $workshop;
    }

    /**
     * @param ?array $filter = null
     * 
     * @return ToirModelBuilder
     */
    public static function filter(?array $filter = null): ToirModelBuilder
    {
        $filter = empty($filter) ? [] : $filter;
        $filter['TYPE'] = Equipment::TYPE_WORKSHOP;
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
        $fields['TYPE'] = Equipment::TYPE_WORKSHOP;
        $fields['LEVEL'] = self::LEVEL;
        $fields['PARENT_ID'] = null;
        unset($fields['WORKSHOP_ID']);
        $id = parent::create($fields);
        if($id) {
            $workshop = self::find($id);
            $workshop->WORKSHOP_ID = $id;
            $workshop->save();
        }
        return $id;
    }

    /**
     * @return ToirModelBuilder
     */
    public function lines(): ToirModelBuilder
    {
        return Line::filter(['PARENT_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function plans(): ToirModelBuilder
    {
        return Plan::filter(['WORKSHOP_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function crashes(): ToirModelBuilder
    {
        return Crash::filter(['WORKSHOP_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function stops(): ToirModelBuilder
    {
        return Stop::filter(['WORKSHOP_ID' => $this->ID]);
    }

    
    /**
     * @return ToirModelBuilder
     */
    public function histories(): ToirModelBuilder
    {
        return History::filter(['WORKSHOP_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function serviceRequests(): ToirModelBuilder
    {
        return ServiceRequest::filter(['WORKSHOP_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['WORKSHOP_ID' => $this->id]);
    }

}