<?php

use BCosta\Filter\Geolocation;
use BCosta\Validator\Validator;
use Phalcon\DI\FactoryDefault;
use Phalcon\Filter;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

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

$di->setShared('filter', function() {
    $filter = new Filter();
    $filter->add('geolocation', new Geolocation());
    return $filter;
});

$di->set('Filter\\Geolocation', function() {
    $filter = new Filter();
    $filter->add('geolocation', new Geolocation());
    return $filter;
});

$di->set('Sanitizer\\Place', function() use ($di) {
    return new \BCosta\Sanitizer\Place($di->get('Filter\\Geolocation'));
});

$di->set('Validation\\Place', function() {
    $validation = new Validation();
    return $validation->add('geolocation', new PresenceOf([
        'message' => 'The geolocation is required'
    ]));
});

$di->set('Validator\\Place', function() use ($di) {
    $validation = $di->get('Validation\\Place');
    return new Validator($validation);
});