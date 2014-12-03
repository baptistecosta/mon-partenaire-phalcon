<?php

use MyTennisPal\Api\Controller\UserController;
use MyTennisPal\Api\Model\DataMapper\UserDataMapper;

$di->set('MyTennisPal\\Api\\Controller\\UserController', function() {
    $controller = new UserController();
    return $controller->setUserDataMapper(new UserDataMapper());
});