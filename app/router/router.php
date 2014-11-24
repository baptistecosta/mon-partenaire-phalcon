<?php

use Phalcon\Mvc\Router;

$router = new Router(false);
$router->setDefaultModule('frontend');
$router->mount(new \MyTennisPal\Router\Group\Api());
$router->mount(new \MyTennisPal\Router\Group\FrontEnd());
$router->notFound([
    'controller' => 'error',
    'action' => 'show404'
]);
return $router;