<?php
session_start();
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
        'token_name' => 'token'
    ]
];


