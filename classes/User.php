<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstanse();
    }

    /*
     *добавит пользователя в БД
     */
    public function create($fields = [])
    {
        $this->db->insert('users', $fields);
        var_dump($fields);
    }
}