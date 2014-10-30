<?php
Symfony\Component\Debug\Debug::enable();
$main = include __DIR__."/production.php";
return array_merge($main, [

    'debug' => true,
    
    'db'=> [
        'driver'     => 'pdo_mysql',
        'dbname'     => 'bolt_demo',
        'host'       => '127.0.0.1',
        'user'       => 'root',
        'password'   => '',
    ],


]);