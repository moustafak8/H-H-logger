<?php
$apis = [
    '/users'         => ['controller' => 'usercontroller', 'method' => 'getUsers'],
    '/users/create' => ['controller' => 'usercontroller', 'method' => 'newUser'],
    '/users/update' => ['controller' => 'usercontroller', 'method' => 'updateuser'],
    '/users/delete' => ['controller' => 'usercontroller', 'method' => 'deleteuser'],
    '/users/login' => ['controller' => 'AuthController', 'method' => 'login'],
    '/habits'         => ['controller' => 'habitcontroller', 'method' => 'gethabits'],
    '/habits/create' => ['controller' => 'habitcontroller', 'method' => 'newhabit'],
    '/habits/update' => ['controller' => 'habitcontroller', 'method' => 'updatehabit'],
    '/habits/delete' => ['controller' => 'habitcontroller', 'method' => 'deletehabit'],
    '/entries'         => ['controller' => 'entrycontroller', 'method' => 'getentries'],
    '/entries/create' => ['controller' => 'entrycontroller', 'method' => 'newentry'],
    '/entries/update' => ['controller' => 'entrycontroller', 'method' => 'updateentry'],
    '/entries/delete' => ['controller' => 'entrycontroller', 'method' => 'deleteentry'],
    '/entries/process' => ['controller' => 'AI_controler', 'method' => 'processEntry'],
    '/entries/save' => ['controller' => 'AI_controler', 'method' => 'saveEntry'],
];
