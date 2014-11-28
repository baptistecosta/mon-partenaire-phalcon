<?php

namespace MyTennisPal\Api;

use MyTennisPal\Api\Plugin\SecurityPlugin;
use Phalcon\Http\Response;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            'MyTennisPal\\Api\\Controller' => './../app/module/api/controller/',
            'MyTennisPal\\Api\\Model' => './../app/module/api/model/',
            'MyTennisPal\\Api\\Model\\DataMapper' => './../app/module/api/model/data_mapper',
            'MyTennisPal\\Api\\Plugin' => './../app/module/api/plugin/',
            'BCosta' => './../vendor/bcosta/src',
        ])->register();
    }

    public function registerServices($di)
    {
        $di->set('dispatcher', function() use ($di) {
            $eventsManager = $di->getShared('eventsManager');

            $eventsManager->attach('dispatch', $di->getShared('MyTennisPal\\Api\\Plugin\\Security'));

            $eventsManager->attach('dispatch:beforeException', function($event, Dispatcher $dispatcher, \Exception $exception) use ($di) {
                header("HTTP/1.1 500 Server error");
                header('Content-type: application/json');
                echo json_encode([
                    'exception' => [
                        'message' => $exception->getMessage(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTrace()
                    ]
                ]);
                exit;
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MyTennisPal\\Api\\Controller');
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });

        $di->set('view', function() {
            $view = new View();
            $view->disable();
            return $view;
        });
    }
}