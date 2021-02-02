<?php

class MysqlConnecter
{
    /**
     * @var MysqlConnecter
     */
    private static $instance;

    /**
     * @var mysqli
     */
    private $mysqli;

    /**
     * @var string
     */
    public $lastError;

    /**
     * @var array
     */
    public $errors;

    /**
     * @var array
     */
    public $queries;

    /**
     * @var array
     */
    public $env;

    /**
     * @return void
     */
    private function __construct()
    {
        $this->env = $this->parsingEnv();

        $this->mysqli = @new mysqli($this->env['DB_HOST'], $this->env['DB_USERNAME'], $this->env['DB_PASSWORD'], $this->env['DB_DATABASE']);

        if ($this->mysqli->connect_errno) {
            die('Ошибка соединения: ' . $this->mysqli->connect_error);
        }
    }

    /**
     * @return MysqlConnecter
     */
    public static function &getInstance(): MysqlConnecter
    {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    private function parsingEnv(): array
    {
        $vars = [];

        $filename = $_SERVER["DOCUMENT_ROOT"] . '/../.env';
        $f = fopen($filename, 'r');
        while($row = fgets($f)) {
            if(strpos($row, '=') !== false) {
                [$key, $value] = explode('=', $row);
                $vars[trim($key)] = trim($value);
            }
        }
        fclose($f);
        
        return $vars;
    }

    /**
     * @param string $table
     * @param array|null $where = null
     * @param array|null $orderBy = null
     * @param int|null $limit = null
     * @param int|null $offset = null
     * @param array|null $fields = null
     * 
     * @return mysqli_result|null
     */
    public function select(string $table, ?array $where = null, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $fields = null): ?mysqli_result
    {
        $fields = empty($fields) ? '*' : implode(',', $fields);
        $where = empty($where) ? '' : ' WHERE ' . implode(' AND ', $where);
        $orderBy = empty($orderBy) ? '' : ' ORDER BY ' . implode(', ', $orderBy);
        $limit = empty($limit) ? '' : ' LIMIT ' . $limit . (empty($offset ? '' : ', ' . $offset));
        
        $query = "SELECT {$fields} FROM {$table}{$where}{$orderBy}{$limit}";

        $result = $this->query($query);

        return $result ? $result : null;
    }

    /**
     * @param string $table
     * @param array $data
     * 
     * @return int|null
     */
    public function insert(string $table, array $data): ?int
    {
        $fieldsArray = [];
        $valuesArray = [];

        foreach($data as $key => $value) {
            if(is_array($value)) {
                die('Ошибка при добавлении. Поле ' . $key . ' - массив');
            }

            $fieldsArray[] = '`' . $key . '`';
            $valuesArray[] = is_null($value) 
                ? 'NULL' 
                : (is_bool($value) 
                    ? ($value ? 'TRUE' : 'FALSE')
                    : "'" . $this->real_escape_string($value) . "'"
                );
        }

        $fields = implode(', ', $fieldsArray);
        $values = implode(', ', $valuesArray);

        $query = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";

        $result = $this->query($query);

        return $result ? $this->mysqli->insert_id : null;
    }

    /**
     * @param string $table
     * @param array $data
     * @param string $where
     * 
     * @return mixed
     */
    public function update(string $table, array $data, string $where)
    {
        if(count($data)) {
            $values = [];

            foreach($data as $key => $value) {
                if(is_array($value)) {
                    die('Ошибка при обновлении. Поле ' . $key . ' - массив');
                }
                $v = is_null($value) 
                    ? 'NULL' 
                    : (is_bool($value) 
                        ? ($value ? 'TRUE' : 'FALSE')
                        : "'" . $this->real_escape_string($value) . "'"
                    );
                $values[] = '`' . $key . '` = ' . $v;
            }

            $query = "UPDATE {$table} SET " . implode(', ', $values) . ' WHERE ' . $where;

            return $this->query($query);
        } else {
            return true;
        }
    }

    /**
     * @param string $table
     * @param string $where
     * 
     * @return mixed
     */
    public function delete(string $table, string $where)
    {
        $query = "DELETE FROM {$table} WHERE " . $where;

        return $this->query($query);
    }

    /**
     * @param string $query
     * 
     * @return mixed
     */
    public function query(string $query)
    {
        $this->queries[] = $query;

        $result = $this->mysqli->query($query);

        if(!$result) {
            $this->lastError = $this->mysqli->error;
            $this->errors[] = $this->mysqli->error;

            if($this->env['APP_DEBUG'] == 'true') {
                dump($this->queries);
                dd($this->lastError);
            }
        }
        
        return $result;
    }

    /**
     * @param string $value
     * 
     * @return string
     */
    public function real_escape_string($value): string
    {
        return $this->mysqli->real_escape_string($value);
    }
}