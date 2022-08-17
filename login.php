<?php
require_once 'init.php';
var_dump($_POST);
if (Input::exists()){
    if (Token::check(Input::get('token'))){
        $validate = new Validator();

        $validate->check($_POST, [
            'email' => ['required'=>true, 'email'=>true],
            'password' => ['required'=>true]
        ]);

        if($validate->passed()){
            $user = new User();
            $remember = (Input::get('remember')) === 'on' ? true : false;

            $login = $user->login(Input::get('email'), Input::get('password'), $remember);
            if ($login) {
                echo 'login successful';
            } else {
                echo ' login failed';
            }
        }else{
            foreach ($validate->errors() as $error){
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
<?php var_dump($_SESSION)?>
<?= Session::flash('success'); ?>

<form action="" method="post">
    <div class="field">

        <label for="email">email</label>
        <input type="email" name="email" value="<?=Input::get('email');?>">
    </div>
    <div class="field">
        <label for="password">Password</label>

        <input type="text" name="password">

    </div>
    <div class="field">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me</label>
    </div>
    <input type="hidden" name="token" value="<?=Token::generate(); ?>">
    <div class="field">
        <button type="submit">Submit</button>
    </div>
</form>

</body>
</html>
