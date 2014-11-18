<?php

use BCosta\Filter\Geolocation;
use BCosta\Validator\Validator;
use Phalcon\DI\FactoryDefault;
use Phalcon\Filter;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

$di = new FactoryDefault();

$di->setShared('security-salt', function() {
    return '8cd384dc9pOxiz19xs65Z813zs5wMlsfZe155ec';
});

$di->set('db-auth', function() use ($config) {
    $dbConfig = $config->database->auth;
    $class = 'Phalcon\Db\Adapter\Pdo\\' . $dbConfig->adapter;
    return new $class([
        'host' => $dbConfig->host,
        'username' => $dbConfig->username,
        'password' => $dbConfig->password,
        'dbname' => $dbConfig->name
    ]);
});

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

$di->set('Validation\\Place', function() {
    $validation = new Validation();
    $validation->add('geolocation', new PresenceOf([
        'message' => 'The geolocation is required'
    ]));
    $validation->setFilters('geolocation', 'geolocation');
    return $validation;
});

$di->set('Validation\\PlaceHintMarker', function() {
    $validation = new Validation();
    $validation->add('south-west-bound', new PresenceOf([
        'message' => 'The south west bound is required'
    ]));
    $validation->add('north-east-bound', new PresenceOf([
        'message' => 'The north east bound is required'
    ]));
    $validation->add('zoom', new PresenceOf([
        'message' => 'The map zoom is required'
    ]));
    $validation->setFilters('south-west-bound', 'geolocation');
    $validation->setFilters('north-east-bound', 'geolocation');
    $validation->setFilters('zoom', 'int');
    return $validation;
});

$di->set('Validator\\Place', function() use ($di) {
    return new Validator($di->get('Validation\\Place'));
});
$di->set('Validator\\PlaceHintMarker', function() use ($di) {
    return new Validator($di->get('Validation\\PlaceHintMarker'));
});