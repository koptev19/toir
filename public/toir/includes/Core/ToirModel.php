<?php

class ToirModel
{

    private $newFields = [];
    private $original = [];
    public $relations = [];
    public $softdelete = true;

    
    /*********      public static functions      ************/


    /**
     * @param int $id
     * 
     * @return mixed
     */
    public static function find($id)
    {
        $model = new static();
        $builder = new ToirModelBuilder($model);
        return $builder->find($id);
    }

    /**
     * @param int $id
     * 
     * @return mixed
     */
    public static function findAvailabled($id)
    {
        $model = self::find($id);
        if($model) {
            UserToir::current()->checkModelOrFail($model);
        }
        return $model;
    }

    /**
     * @param ?array $filter = null
     * 
     * @return ToirModelBuilder
     */
    public static function filter(?array $filter = null): ToirModelBuilder
    {
        $builder = self::getBuilder();
        $builder->setFilter($filter);

        return $builder;
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        $builder = self::getBuilder();
        return $builder->get();
    }

    /**
     * @param ?array $filter = null
     * 
     * @return ToirModelBuilder
     */
    public static function withTrashed(): ToirModelBuilder
    {
        $builder = self::getBuilder();
        $builder->withTrashed();
        return $builder;
    }

    /**
     * @param array $models
     * @param string $fieldName
     * 
     * @return array
     */
    public static function getByOtherModels(array $models, string $fieldName):array
    {
        $ids = [];
        foreach($models as $model) {
            if($model->$fieldName) {
                $ids[] = $model->$fieldName;
            }
        }

        return count($ids) > 0 ? static::filter(['ID' => $ids])->get() : [];
    }

    /**
     * @param array $fields
     * 
     * @return null|int
     */
    public static function create(array $fields): ?int
    {
        $fields['created_at'] = date('Y-m-d H:i:s');
        $fields['updated_at'] = date('Y-m-d H:i:s');

        $model = new static();

        $mysql = MysqlConnecter::getInstance();
        return $mysql->insert($model->table, $fields);
    }

    /**
     * @param int $limit
     * 
     * @return int
     */
    public static function maxPage(?int $limit = null): int
    {
        $limit = (int)$_REQUEST['limit'] > 0 ? (int)$_REQUEST['limit'] : 50;
        $count = self::filter([])->count();
        return $limit > 0 ? ceil($count / $limit) : 1;
    }



    /*********      public functions      ************/



    /**
     * @param string $fieldName
     * 
     * @return mixed
     */
    public function __get(string $fieldName)
    {
        if(method_exists($this, $fieldName)) {
            return $this->getPropertyByMethod($fieldName);
        }

        $fieldName = strtolower($fieldName);

        if(isset($this->newFields[$fieldName])) {
            return $this->newFields[$fieldName];
        }elseif(isset($this->relations[$fieldName])) {
            return $this->getByRelation($fieldName);
        } elseif(isset($this->original[$fieldName])) {
            return $this->original[$fieldName];
        } else {
            return $this->$fieldName;
        }
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * 
     * @return void
     */
    public function __set(string $name, $value)
    {
        $name = strtolower($name);
        if($name == 'id') {
            die('Поле id нельзя обновлять');
        }
        $this->newFields[$name] = $value;
    }

    public function setOriginal(string $key, $value)
    {
        if(is_integer($value)) {
            $this->original[$key] = (int)$value;
        } elseif(is_numeric($value)) {
            $this->original[$key] = (float)$value;
        } else {
            $this->original[$key] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function save()
    {
        if(count($this->newFields) === 0) {
            return true;
        }

        $data = ['updated_at' => date('Y-m-d H:i:s')];
        foreach($this->newFields as $key => $value) {
            if($this->relations && isset($this->relations[$key])) {
                $this->saveRelation($key, $value);
                continue;
            }
            $data[$key] = $value;
        }

        $model = new static();

        $mysql = MysqlConnecter::getInstance();
        return $mysql->update($model->table, $data, 'id = ' . $this->id);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $data = [
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s'),
        ];

        $model = new static();

        $mysql = MysqlConnecter::getInstance();
        return $mysql->update($model->table, $data, 'id = ' . $this->id);
    }

    /**
     * @return Workshop
     */
    public function workshop(): Workshop
    {
        return Workshop::find($this->WORKSHOP_ID);
    }
    
    /**
     * @return Line
     */
    public function line(): ?Line
    {
        return Line::find($this->LINE_ID);
    }

    /**
     * @return Equipment|null
     */
    public function equipment(): ?Equipment
    {
        return Equipment::find($this->EQUIPMENT_ID);
    }

    /**
     * @return Service|null
     */
    public function service(): ?Service
    {
        return Service::withTrashed()
            ->find($this->SERVICE_ID);
    }


    /*********      private functions      ************/



    /**
     * @return ToirModelBuilder
     */
    protected static function getBuilder(): ToirModelBuilder
    {
        $model = new static();
        return new ToirModelBuilder($model);
    }

    /**
     * @param string $functionName
     * @return mixed
     */
    private function getPropertyByMethod(string $functionName)
    {
        $object = $this->$functionName();

        if(is_a($object, ToirModelBuilder::class)) {
            return $object->get();
        } else {
            return $object;
        }
    }

    /**
     * @param string $field
     * 
     * @return mixed
     */
    private function getByRelation(string $field)
    {
        if(!isset($this->relations[$field])) {
            return null;
        }

        $relation = $this->relations[$field];

        $ids = [];

        $mysql = MysqlConnecter::getInstance();
        $result = $mysql->select($relation['table'], [$relation['owner_key'] . '=' . $this->id]);
        if($result) {
            while ($object = $result->fetch_assoc()) {
                $ids[] = $object[$relation['foreign_key']];
            }
        }

        return $ids;
    }

    /**
     * @param string $field
     * @param array $values
     */
    private function saveRelation(string $field, array $values)
    {
        $relation = $this->relations[$field];

        $mysql = MysqlConnecter::getInstance();
        $mysql->delete($relation['table'], $relation['owner_key'] . ' = ' . $this->id);

        foreach($values as $fKey) {
            $mysql->insert($relation['table'], [
                $relation['owner_key'] => $this->id,
                $relation['foreign_key'] => $fKey,
            ]);            
        }
    }

    
}