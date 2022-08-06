<?php
require 'Config.php';

class DB
{
    private static object|null $instance = null;
    private PDO $pdo;
    private PDOStatement|null $query;
    private object|array $results;
    private bool $error = false;
    private int $count;

    private function __construct()
    {
        try {
            $this->pdo = new \PDO("mysql:host=" . Config::get('mysql.host').";dbname=".Config::get('mysql.database') . "",Config::get('mysql.username') . "", "" . Config::get('mysql.password'));
        } catch (\PDOException $exception) {
            die($exception->getMessage());
        }
    }

    public static function getInstanse()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    } 

    /**
     * @param $sql
     * @param array $params
     * @return object|array
     */
    public function query(string $sql, array $params = []): object|array
    {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }
        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count = $this->query->rowCount();
        }
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array|object
     */
    public function getResults(): array|object
    {
        return $this->results;
    }

    function getCount(): int
    {
        return $this->count;
    }


    /**
     * @param string $table
     * @param array $where(string name column, string action(<,>,=), $value)
     * @return object|bool
     */
    public function getForTable(string $table, array $where = []): object|bool
    {
        return $this->getForAction('SELECT *', $table, $where);
    }

    public function deleteForTable(string $table, array $where = []): object|bool
    {
        return $this->getForAction('DELETE', $table, $where);
    }

    public function getForAction(string $action, string $table, array $where = []):object|bool
    {
        $operators = ['=', '<', '>', '<=', '>='];

        if (count($where) === 3) {
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, [$value])->getError()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function insert(string $table, $fields = []): bool
    {
        $values = '';
        foreach ($fields as $field) {
            $values .= '?,';
        }
        $values =  rtrim($values, ',');

        $sql = "INSERT INTO {$table} (`"  . implode('`, `', array_keys($fields)) . "`) VALUES (" . $values . ")";

        if(!$this->query($sql, $fields)->getError()){
            return true;
        }
        return false;
    }

    /**
     * @param $table
     * @param $id
     * @param $fields
     * @return bool
     */
    public function update($table, $id, $fields = []):bool
    {
        $set ='';
        foreach ($fields as $key => $field) {
            $set .= "{$key} = ?, ";
        }
        $set = rtrim($set, ', ');
        $sql = "UPDATE {$table} SET {$set} WHERE `id` = {$id}";

        if (!$this->query($sql, $fields)->getError()) {
            return true;
        }
        return false;
    }

    public function first()
    {
        return $this->results[0];
    }

}