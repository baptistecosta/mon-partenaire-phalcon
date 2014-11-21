<?php
use Phalcon\Mvc\Router;

$router = new Router(false);
$router->setDefaultModule('www');

// Www
$router->add('/', ['controller' => 'index', 'action' => 'index']);
$router->add('/login', ['controller' => 'login', 'action' => 'index']);
$router->add('/sand-box', ['controller' => 'sand-box', 'action' => 'index']);

// Api
$router->add('/api', ['module' => 'api', 'controller' => 'index', 'action' => 'index']);
$router->addGet('/api/place-markers', ['module' => 'api', 'controller' => 'place_markers', 'action' => 'get']);
$router->addGet('/api/place-hint-markers', ['module' => 'api', 'controller' => 'place_hint_markers', 'action' => 'get']);
$router->addGet('/api/scrapped-place-markers', ['module' => 'api', 'controller' => 'scrapped_place_markers', 'action' => 'get']);
$router->addPost('/api/place/scrap', ['module' => 'api', 'controller' => 'place', 'action' => 'scrap']);

// Auth
$router->addPost('/auth/user', ['module' => 'auth', 'controller' => 'user', 'action' => 'add']);
$router->add('/auth/sign-in', ['controller' => 'auth', 'action' => 'signIn']);
$router->add('/auth/sign-out', ['controller' => 'auth', 'action' => 'signOut']);

$router->notFound(['controller' => 'error', 'action' => 'show404']);
return $router;