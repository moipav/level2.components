<?php
require_once 'classes/DB.php';

$posts = DB::getInstanse()->query('SELECT * FROM posts');
if ($posts->getError()) {
    echo 'we have error';
}else{
    echo 'ok';
}
die();
echo '<pre>';
var_dump($posts);
echo '</pre>';