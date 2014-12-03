<?php

namespace MyTennisPal\Api;

use MyTennisPal\Api\Plugin\SandBox;
use MyTennisPal\Api\Plugin\BodyParser;
use MyTennisPal\Api\Plugin\ExceptionHandler;
use Phalcon\Events\Manager as EventsManager;
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
            'MyTennisPal\\Api' => './../app/module/Api/',
            'BCosta' => './../vendor/bcosta/src',
        ])->register();
    }

    public function registerServices($di)
    {
        $di->set('dispatcher', function() use ($di) {
            /** @var EventsManager $eventsManager */
            $eventsManager = $di->getShared('eventsManager');
            $eventsManager->attach('dispatch:beforeExecuteRoute', new BodyParser());
            $eventsManager->attach('dispatch:beforeExecuteRoute', $di->getShared('MyTennisPal\\Api\\Plugin\\Security'));
            $eventsManager->attach('dispatch:beforeException', new ExceptionHandler());
            $eventsManager->attach('dispatch:afterInitialize', new SandBox());

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