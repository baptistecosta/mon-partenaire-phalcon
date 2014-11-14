<?php

use Phalcon\Mvc\Application;
use Phalcon\Exception;
use Phalcon\Mvc\Router;

/**
 * Read the configuration
 */
$config = new \Phalcon\Config\Adapter\Json('./../config/application.json');

include('./../config/services.php');

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