<?php

use BCosta\Filter\Geolocation;
use Phalcon\DI\FactoryDefault;
use Phalcon\Filter;

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

$di->set('place-filter', function() {
    $filter = new Filter();
    return $filter->add('geolocation', new Geolocation());
});