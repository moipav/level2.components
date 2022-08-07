<?php
require_once 'init.php';
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
            'email' => [
                'required' =>true,
                'email'=> true,
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
/*при необходимости можем добавлять правила и поля */
            $user = new User;
            $user->create([
                'username' => Input::get('username'),
                'email' =>Input::get('email'),
                'password_hash' => password_hash(Input::get('password'), PASSWORD_DEFAULT)
            ]);

            Session::flash('success', 'регистрация прошла успешно!');

        } else {
            foreach ($validate->errors() as $error) {
                echo $error . "<br>";

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
<?php var_dump($_SESSION)?>
<?= Session::flash('success'); ?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?=Input::get('username');?>">
    </div>
    <div class="field">
        <label for="email">email</label>
        <input type="text" name="email" value="<?=Input::get('email');?>">
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
