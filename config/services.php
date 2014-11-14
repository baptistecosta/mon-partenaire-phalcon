<?php

use Phalcon\DI\FactoryDefault;

$di = new FactoryDefault();

$di->set('db', function() use ($config) {
    $dbConfig = $config->database->myTennisPal;

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $dbConfig->adapter;
    return new $class([
        'host' => $dbConfig->host,
        'username' => $dbConfig->username,
        'password' => $dbConfig->password,
        'dbname' => $dbConfig->name
    ]);
});

//Specify routes for modules
$di->set('router', function () {
    return include('./../config/routes.php');
});
