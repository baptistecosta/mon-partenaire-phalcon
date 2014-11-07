<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Exception;
use Phalcon\Mvc\Router;

$di = new FactoryDefault();

$di->set('db', function() {
    return new \Phalcon\Db\Adapter\Pdo\Mysql([
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'dbname' => 'my_tennis_pal'
    ]);
});

//Specify routes for modules
$di->set('router', function () {

    $router = new Router(false);
    $router->setDefaultModule('www');
    $router->add('/', ['module' => 'www', 'controller' => 'index', 'action' => 'index']);
    $router->add('/login', ['module' => 'www', 'controller' => 'login', 'action' => 'index']);
    $router->add('/api', ['module' => 'api', 'controller' => 'index', 'action' => 'index']);
    $router->addGet('/api/place-markers', ['module' => 'api', 'controller' => 'place_markers', 'action' => 'get']);
    $router->addGet('/api/place-small-markers', ['module' => 'api', 'controller' => 'place_small_markers', 'action' => 'get']);
    $router->notFound(['module' => 'www', 'controller' => 'error', 'action' => 'show404']);
    return $router;

});

try {
    //Create an application
    $app = new Application($di);

    // Register the installed modules
    $app->registerModules([
        'www' => [
            'className' => 'MonPartenaire\\Www\\Module',
            'path' => '../apps/www/Module.php',
        ],
        'api' => [
            'className' => 'MonPartenaire\\api\\Module',
            'path' => '../apps/api/Module.php',
        ]
    ]);

    //Handle the request
    echo $app->handle()->getContent();

} catch (Exception $e) {
    echo $e->getMessage();
}