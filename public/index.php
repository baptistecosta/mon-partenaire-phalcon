<?php

use Phalcon\Mvc\Application,
    Phalcon\Exception;

$di = new Phalcon\DI\FactoryDefault();

//Specify routes for modules
$di->set('router', function () {

    $router = new Phalcon\Mvc\Router();
    $router->setDefaultModule('www');
    $router->add('/login', ['module' => 'www', 'controller' => 'login', 'action' => 'index']);
    $router->add('/api', ['module' => 'api', 'controller' => 'index', 'action' => 'index']);
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