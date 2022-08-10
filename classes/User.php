<?php

class User
{
    private $db;
    private $data = null;
    private $session_name;
    private $isLoggedIn;

    public function __construct($userId = null)
    {
        $this->db = DB::getInstanse();
        $this->session_name = Config::get('session.user_session');

        if (!$userId) {
            if (Session::exists($this->session_name)) {
                $userId = Session::get($this->session_name);

                if ($this->find($userId)) {
                    $this->isLoggedIn = true;
                }
            }
        }
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

            $user = $this->find($email);
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

    public function find($value)
    {
        if (is_numeric($value)) {
            $this->data = $this->db->getForTable('users', ['id', '=', $value])->first();
        } else {
            $this->data = $this->db->getForTable('users', ['email', '=', $value])->first();
        }
        if ($this->data) {
            return true;
        } else return false;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getIsLogedIn()
    {
        return $this->isLoggedIn;
    }
}