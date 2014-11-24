<?php

namespace MyTennisPal\FrontEnd;

use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            'MyTennisPal\\FrontEnd\\Controller' => '../app/module/frontend/controller/',
            'MyTennisPal\\FrontEnd\\Model' => '../app/module/frontend/model/',
            'BCosta' => './../vendor/bcosta/src',
        ])->register();
    }

    public function registerServices($di)
    {
        $di->set('dispatcher', function() use ($di) {
            $eventManager = $di->getShared('eventsManager');
            $eventManager->attach('dispatch:beforeException', function($event, Dispatcher $dispatcher, $exception) {
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward([
                            'controller' => 'error',
                            'action' => 'show404',
                        ]);
                        return false;
                }
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MyTennisPal\\FrontEnd\\Controller');
            return $dispatcher;
        });

        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir('../app/module/frontend/view/');
            return $view;
        });
    }
}