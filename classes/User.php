<?php
//14 урок заново и внимательно проверить
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

    public function login($email = null, $password = null)
    {
        if ($email) {
            //проверили усть-ли пользователь с таким емэйлом
            //findOneUserByEmail()
            $user = $this->db->getForTable('users', ['email', '=', $email])->first();
            //сравнили введеный пароль с БД
//            var_dump($user);die;
            if(password_verify($password, $user->password_hash)){
                Session::put('user', $user->id);
                return true;
            }


        }
        return false;
    }
}