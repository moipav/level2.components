<?php

class DB
{
    private static object|null $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new \PDO('mysql:host=localhost:3307;dbname=marlin_oop', 'root', '');
            echo 'ok';
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
}