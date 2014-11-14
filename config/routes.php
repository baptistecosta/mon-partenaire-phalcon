<?php
use Phalcon\Mvc\Router;

$router = new Router(false);
$router->setDefaultModule('www');
$router->add('/', [
    'controller' => 'index',
    'action' => 'index'
]);
$router->add('/login', [
    'controller' => 'login',
    'action' => 'index'
]);
$router->add('/api', [
    'module' => 'api',
    'controller' => 'index',
    'action' => 'index'
]);
$router->addGet('/api/place-markers', [
    'module' => 'api',
    'controller' => 'place_markers',
    'action' => 'get'
]);
$router->addGet('/api/place-hint-markers', [
    'module' => 'api',
    'controller' => 'place_hint_markers',
    'action' => 'get'
]);
$router->addGet('/api/scrapped-place-markers', [
    'module' => 'api',
    'controller' => 'scrapped_place_markers',
    'action' => 'get'
]);
$router->notFound([
    'controller' => 'error',
    'action' => 'show404'
]);
return $router;