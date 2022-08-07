<?php

class User
{
    private $db;
    private $data = null;
    private $session_name;

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
        $this->session_name = Config::get('session.user_session');

    }

    public function login($email = null, $password = null)
    {
        if ($email) {
            //проверили усть-ли пользователь с таким емэйлом

            $user = $this->getEmail($email);
            //сравнили введеный пароль с БД
            if ($user) {
                if (password_verify($password, $this->data->password_hash)) {
                    Session::put($this->session_name, $this->data->id);
                    return true;
                }
            }


        }
        return false;
    }

    public function getEmail($email)
    {
        $this->data = $this->db->getForTable('users', ['email', '=', $email])->first();
        if ($this->data) {
            return true;
        } else return false;
    }

    public function getData()
    {
        return $this->data;
    }
}