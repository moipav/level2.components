<?php
require_once 'init.php';
$user = new User();


$validate = new Validator();
$validate->check($_POST, [
        'username' => ['required' => true, 'min'=>2]
]);
if (Input::exists()) {
    if(Token::check(Input::get('token'))){
        if ($validate->passed()) {
            $user->update(['username' => Input::get('username')]);
            Redirect::to('update.php');// TODO вылазит ошибка валидации! из-за редиректа
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
    <title>Update</title>
</head>
<body>
<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?= $user->getData()->username?>">
    </div>
    <div class="field">
        <button type="submit">Submit</button>
    </div>

    <input type="hidden" name="token" value="<?=Token::generate()?>">
</form>
</body>
</html>
