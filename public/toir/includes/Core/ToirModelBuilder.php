<?php

class ToirModelBuilder
{
    private $fields = null;
    private $filter = [];
    private $sort = [];
    private $limit = null;
    private $offset = null;
    private $withTrashed = false;

    public function __construct(ToirModel $model)
    {
        $this->emptyModel = $model;
        $this->table = $model->table;
        $this->withTrashed = !$model->softdelete;        
    }
    
    public function setFilter(?array $filter): ToirModelBuilder
    {
        if(!empty($filter)) {
            $this->filter = array_merge($this->filter, $filter);
        }
        return $this;
    }

    public function orderBy(string $prop, string $asc = 'ASC'): ToirModelBuilder
    {
        $this->sort[] = strtolower($prop) . ' ' . $asc;
        return $this;
    }

    public function limit(int $limit): ToirModelBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): ToirModelBuilder
    {
        $this->offset = $offset;
        return $this;
    }

    public function withTrashed(): ToirModelBuilder
    {
        $this->withTrashed = true;
        return $this;
    }

    public function get(): array
    {
        $mysql = MysqlConnecter::getInstance();
        $result = $mysql->select($this->table, $this->getWhereByFilter(), $this->sort, $this->limit, $this->offset, $this->fields);

        $models = [];

        if($result) {
            while ($object = $result->fetch_assoc()) {
                $models[$object['id']] = $this->createModelByArray($object);
            }
        }

        return $models;
    }

    public function first(): ?ToirModel
    {
        $this->limit(1);
        $models = $this->get();
        return count($models) > 0 ? reset($models) : null;
    }

    public function count(): ?int
    {

        $this->fields = ['count(*)'];
        $mysql = MysqlConnecter::getInstance();
        $result = $mysql->select($this->table, $this->getWhereByFilter(), null, null, null, $this->fields);
        $row = $result ? $result->fetch_array(MYSQLI_NUM) : null;
        return $row ? (int)$row[0] : null;
    }

    public function find($id): ?ToirModel
    {
        return $this->setFilter(['id' => $id])
            ->first();
    }

    private function createModelByArray(array $object): ToirModel
    {
        $model = clone $this->emptyModel;
        foreach($object as $key => $value) {
            $model->setOriginal($key, $value);
        }
        return $model;
    }

    private function getWhereByFilter(): array
    {
        $where = [];
        foreach($this->filter as $key => $value) {
            if(in_array(substr($key, 0, 2), ['<=', '>='])) {
                $where[] = $this->whereOne(substr($key, 2), substr($key, 0, 2), $value);
            } elseif (in_array($key[0], ['>', '<', '%'])) {
                $where[] = $this->whereOne(substr($key, 1), $key[0], $value);
            } elseif ($key[0] == '!') {
                $where[] = '(' . $this->whereOne(substr($key, 1), '!=', $value) . ' OR ' . $this->whereOne(substr($key, 1), '=', null) . ')';
            } else {
                $where[] = $this->whereOne($key, '=', $value);
            }
        }

        if(!$this->withTrashed) {
            $where[] = "deleted_at is NULL";
        }

        return $where;
    }

    private function whereOne(string $field, $znak, $value): string
    {
        $mysql = MysqlConnecter::getInstance();

        $field = strtolower($field);
        if(is_null($value)) {
            if($znak === '!=') {
                return $field." IS NOT NULL";
            } else {
                return $field." IS NULL";
            }
        } elseif(is_array($value)) {
            foreach($value as $k => $v) {
                $value[$k] = $mysql->real_escape_string($v);
            }
            if($znak === '!=') {
                return $field." NOT IN ('" . implode("', '", $value) . "')";
            } else {
                return $field." IN ('" . implode("', '", $value) . "')";
            }
        } else {
            if($znak == '%') {
                return $field . " like  '%"  . $mysql->real_escape_string($value) . "%'";
            } else {
                return $field . ' ' . $znak . " '"  . $mysql->real_escape_string($value) . "'";
            }
        }
    }
}