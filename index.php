<?php
require_once 'init.php';
//var_dump(Config::get('session.user_session'));
$user = new User();
//$user2= new User(2);
//echo $user->getData()->username;
//echo $user2->getData()->username;

if ($user->getIsLogedIn()) {
    echo "Hi, {$user->getData()->username}<br>";
    echo "<a href='logout.php'> Logout</a>";
    echo "<a href='update.php'> Update</a>";
}else{
    echo "<a href='login.php'>Login</a> or <a href='register.php'> register</a>";
}
?>

<?php

//Config::get('mysql.something');
//$users = DB::getInstanse()->query('SELECT * FROM users WHERE name  = ?', ['John Doe']);

//$users = DB::getInstanse()->getForTable('users', ['username', '=', 'PaveL']);

//DB::getInstanse()->deleteForTable('users', ['username', '=', 'test']);
//$users = DB::getInstanse()->insert('users', [
//    'username' => 'PaveL',
//    'password_hash' => 'password'
//]);
//DB::getInstanse()->update('users', 7, [
//    'username'=>'user',
//    'password_hash'=>'123'
//]);
//if ($users->getError()) {
//    echo 'we have error';
//} else {
//    foreach ($users->getResults() as $result) {
//        echo $result->id;
//        echo '<br>';
//        echo $result->username;
//        echo '<br>';
//    }
//}
//echo '<hr>';
//echo 'Количество записей:' . $users->getCount();
//var_dump(Token::check(Input::get('token')));
