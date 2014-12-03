<?php

use BCosta\Filter\Geolocation;
use MyTennisPal\Api\Model\DataMapper\AccessTokenDataMapper;
use MyTennisPal\Api\Model\DataMapper\ClientDataMapper;
use MyTennisPal\Api\Plugin\Security;
use Phalcon\Config\Adapter\Json as JsonConfig;
use Phalcon\DI\FactoryDefault;
use Phalcon\Filter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Router;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Validation;

$di = new FactoryDefault();

// Start the session the first time some component request the session service
$di->set('session', function() {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

// Register the flash service with Bootstrap CSS classes
$di->set('flash', function(){
    return new FlashSession([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
    ]);
});

//Specify routes for modules
$di->set('router', function () {
    return include('./../app/router/router.php');
});

$di->setShared('filter', function() {
    $filter = new Filter();
    $filter->add('geolocation', new Geolocation());
    return $filter;
});

$di->setShared('MyTennisPal\\Api\\Plugin\\Security', function() {
    return new Security(
        new AccessTokenDataMapper(),
        new ClientDataMapper(),
        (array) new JsonConfig('./../app/config/public-resource.json'),
        (array) new JsonConfig('./../app/config/scopes.json')
    );
});

include('./../app/config/service/db.php');
include('./../app/config/service/auth.php');
include('./../app/config/service/place.php');
include('./../app/config/service/place_hint_marker.php');
include('./../app/config/service/user.php');
