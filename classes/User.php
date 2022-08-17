<?php

class User
{
    private $db;
    private $data = null;
    private $session_name;
    private $isLoggedIn;
    private $cookieName;

    public function __construct($userId = null)
    {
        $this->db = DB::getInstanse();
        $this->session_name = Config::get('session.user_session');
        $this->cookieName = Config::get('cookie.cookie_name');
        //ar_dump($this->cookieName);die;

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

    public function login($email = null, $password = null, $remember = false)
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->session_name, $this->getData()->id);
        } else {
            $user = $this->find($email);
            //сравнили введеный пароль с БД
            if ($user) {
                if (password_verify($password, $this->data->password_hash)) {
                    Session::put($this->session_name, $this->getData()->id);

                    if ($remember) {
                        $hash = hash('sha256', uniqid());

                        $hashCheck = $this->db->getForTable('user_sessions', ['user_id' => $this->getData()->id]);

                        if (!$hashCheck) {
                            $this->db->insert('user_sessions', [
                                'user_id' => $this->getData()->id,
                                'hash' => $hash
                            ]);
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->cookieName, $hash, Config::get('cookie.cookie_expiry'));
                    }
                }
                return true;
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

    public function exists(): bool
    {
        return !empty($this->getData());
    }

    public function logout()
    {
        $this->db->deleteForTable('user_sessions', ['user_id', '=', $this->getData()->id]);
        Session::delete($this->session_name);
        Cookie::delete($this->cookieName);
    }

    public function update($fields = [], $id = null)
    {
        //получаем текущий id пользователя
        if (!$id && $this->getIsLogedIn()) {
            $id = $this->getData()->id;
        }
        $this->db->update('users', $id, $fields);
    }
}