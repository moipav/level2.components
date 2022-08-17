<?php
require_once 'init.php';
$user = new User();

$validate = new Validator();
$validate->check($_POST, [
    'current_password' => ['required' => true, 'min'=>3],
    'new_password' => ['required' =>true, 'min' => 6],
    'new_password_again' => ['required' =>true, 'min' => 6, 'matches' => 'new_password']
]);
if (Input::exists()) {
    if(Token::check(Input::get('token'))){
        if ($validate->passed()) {
            if(!password_verify(Input::get('current_password'), $user->getData()->password_hash)){
                echo 'Current password is invalid';exit;
            }

            $user->update(['password_hash' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT)]);
            Session::flash('success', 'Password update!');
            Redirect::to('index.php');// TODO вылазит ошибка валидации! из-за редиректа
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
        <label for="password">Current password</label>
        <input type="password" name="current_password" value="">
    </div>
    <div class="field">
        <label for="New password">New password</label>
        <input type="password" name="new_password" value="">
    </div>
    <div class="field">
        <label for="password">New password again</label>
        <input type="password" name="new_password_again" value="">
    </div>
    <div class="field">
        <button type="submit">Submit</button>
    </div>

    <input type="hidden" name="token" value="<?=Token::generate()?>">
</form>
</body>
</html>

