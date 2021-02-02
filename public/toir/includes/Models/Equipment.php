<?php

class Equipment extends ToirModel
{

    public const TYPE_WORKSHOP = 'workshop';
    public const TYPE_LINE = 'line';
    public const TYPE_MECHANISM = 'mechanism';
    public const TYPE_NODE = 'node';
    public const TYPE_DETAIL = 'detail';

    public static $types = [
        self::TYPE_WORKSHOP => 'Цех',
        self::TYPE_LINE => 'Линия',
        self::TYPE_MECHANISM => 'Механизм',
        self::TYPE_NODE => 'Узел',
        self::TYPE_DETAIL => 'Деталь',
    ];

    public static $SOSTOYANIE_LINII = [
        1 => 'Исправна',
        2 => 'Неисправна',
        3 => 'Ремонт',
    ];

    public $table = 'equipment';

    /**
     * @return ToirModelBuilder
     */
    public function children(): ToirModelBuilder
    {
        return self::filter(['PARENT_ID' => $this->id]);
    }
    
    /**
     * @return Equipment
     */
    public function parent(): ?Equipment
    {
        return self::find($this->PARENT_ID);
    }
    
    /**
     * @return array[Equipment]
     */
    public function parents(): array
    {
        $parents = [];
        $element = $this;
        while($element = $element->parent()) {
            $parents = [$element->ID => $element] + $parents;
        }
        return $parents;
    }

    /**
     * @return ToirModelBuilder
     */
    public function notPlans(): ToirModelBuilder
    {
        $filter = [
            'WORKSHOP_ID' => $this->WORKSHOP_ID,
            'PLAN_ID' => null,
        ];

        if($this->LINE_ID) {
            $filter['LINE_ID'] = $this->LINE_ID;
        }

        return Operation::filter($filter);
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
    public function histories(): ToirModelBuilder
    {
        return History::filter(['EQUIPMENT_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function serviceRequests(): ToirModelBuilder
    {
        return ServiceRequest::filter(['EQUIPMENT_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function plans(): ToirModelBuilder
    {
        return Plan::filter(['EQUIPMENT_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function works(): ToirModelBuilder
    {
        return Work::filter(['EQUIPMENT_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function operations(): ToirModelBuilder
    {
        return Operation::filter(['EQUIPMENT_ID' => $this->id]);
    }

    /**
     * @param string $delimiter = ' / '
     * @param bool $addLink = false
     * @param bool $includeWorkshop = false
     *
     * @return string
     */
    public function getPath(string $delimiter = ' / ', bool $addLink = false, bool $includeWorkshop = false): string
    {
        $path = [];
        foreach($this->parents() as $parent) {
            if ($parent->TYPE_ENUM === self::TYPE_WORKSHOP && ! $includeWorkshop) {
                continue;
            }
            $path[] = $addLink ? $parent->link() : $parent->NAME;
        }
        return implode($delimiter, $path);
    }

    /**
     * @param string $delimiter = ' / '
     * @param bool $addLink = false
     * @param bool $includeWorkshop = false
     *
     * @return string
     */
    public function getFullPath(string $delimiter = ' / ', bool $addLink = false, bool $includeWorkshop = false): string
    {
        $path = $this->getPath($delimiter, $addLink, $includeWorkshop);
        $path .= ($path ? $delimiter : '') . ($addLink ? $this->link() : $this->NAME);
        return $path;
    }

    /**
     * @param string $delimiter = ' / '
     *
     * @return string
     */
    public function link(bool $newWindow = true): string
    {
        return '<a href="equipment.php?id='. $this->ID . '" ' . ($newWindow ? 'target=_blank' : '') . '>' . $this->NAME . '</a>';
    }

    /**
     * @param bool $isLink = true
     * @return string
     */
    public function path(bool $isLink = true): string
    {
        $path = '';
        if($this->LINE_ID) {
            $path .= $isLink ? $this->line()->link() : $this->line()->NAME;
        }
        if($this->LINE_ID != $this->ID) {
            if($this->LINE_ID) {
                $path .= ' / ';
            }
            $path .= $isLink ? $this->link() : $this->NAME;
        }
        return $path;
    }

    /**
     * @return array
     */
    public function allChildren(): array
    {
        $children = $this->children;
        foreach($children as $child) {
            $children = array_merge($children, $child->allChildren());
        }
        return $children;
    }
    

}