<?php
session_start();
require_once 'classes/DB.php';
require_once 'classes/Config.php';
require_once 'classes/Validator.php';
require_once 'classes/Input.php';
require_once 'classes/Token.php';
require_once 'classes/Session.php';
require_once 'classes/User.php';
$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost:3307',
        'username' => 'root',
        'password' => '',
        'database' => 'marlin_oop',
        'something' => [
            'no' => 'yes'
        ]
    ],
    'session' => [
        'token_name' => 'token'
    ]
];

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
    $validate = new Validator();

    $validate->check($_POST, [
        'username' => [
            'required' => true,
            'min' => 2,
            'max' => 15,
            'unique' => 'users'
        ],
        'password' => [
            'required' => true,
            'min' => 3
        ],
        'password_again' => [
            'required' => true,
            'matches' => 'password'
        ]
    ]);
    if ($validate->passed()) {
            echo "validate OK";
        $user = new User;
        $user->create([
            'username' => Input::get('username'),
            'password_hash' => password_hash(Input::get('password'), PASSWORD_DEFAULT)
        ]);


        Session::flash('success', 'Все ок');
        echo 'passed';
    } else {
        foreach ($validate->errors() as $error) {
            echo $error . '<br>';
        }
    }
    }
}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
    <?= Session::flash('success'); ?>
    <form action="" method="post">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" value="<?=Input::get('username');?>">
        </div>
        <div class="field">
            <label for="password">Password</label>

            <input type="text" name="password">

        </div>
        <div class="field">
            <label for="password_again">Password Again</label>
            <input type="text" name="password_again">
        </div>
        <input type="text" name="token" value="<?=Token::generate(); ?>">
        <div class="field">
            <button type="submit">Submit</button>
        </div>
    </form>

    </body>
    </html>
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
