<?php

class DB
{
    private static object|null $instance = null;
    private PDO $pdo;
    private  $query, $results;
    private bool $error = false;

    private function __construct()
    {
        try {
            $this->pdo = new \PDO('mysql:host=localhost:3307;dbname=marlin_oop', 'root', '');
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

    public function query($sql)//:object|array
    {
        $this->query= $this->pdo->prepare($sql);
        if (!$this->query->execute()) {
            $this->error = true;
        }
        $this->results =$this->query->fetchAll(PDO::FETCH_OBJ);
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }
}