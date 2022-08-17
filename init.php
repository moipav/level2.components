<?php
session_start();
require_once 'classes/Cookie.php';
require_once 'classes/DB.php';
require_once 'classes/Config.php';
require_once 'classes/Validator.php';
require_once 'classes/Input.php';
require_once 'classes/Token.php';
require_once 'classes/Session.php';
require_once 'classes/User.php';
require_once 'classes/Redirect.php';
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
        'token_name' => 'token',
        'user_session' => 'user'
    ],

    'cookie' => [
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ]
];

if (Cookie::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))) {
    $hash = Cookie::get(Config::get('cookie.cookie_name'));
    $hashCheck = DB::getInstanse()->getForTable('user_sessions', ['hash', '=', $hash]);

    if ($hashCheck->getCount()) {

        $user = new User($hashCheck->first());
        $user->login();
    }
}
