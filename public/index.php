<?php

use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Exception;
use Phalcon\Mvc\Router;

$config = new \Phalcon\Config\Adapter\Json('./../app/config/application.json');

include('./../app/config/services.php');

$loader = new Loader();
$loader->registerNamespaces([
    'MyTennisPal\\Router' => './../app/router/',
])->register();

try {
    $app = new Application($di);
    $app->registerModules([
        'frontend' => [
            'className' => 'MyTennisPal\\FrontEnd\\Module',
            'path' => './../app/module/frontend/Module.php',
        ],
        'api' => [
            'className' => 'MyTennisPal\\Api\\Module',
            'path' => './../app/module/api/Module.php',
        ],
        'auth' => [
            'className' => 'MyTennisPal\\Auth\\Module',
            'path' => './../app/module/auth/Module.php',
        ]
    ]);

    echo $app->handle()->getContent();

} catch (Exception $e) {
    echo $e->getMessage();
}