<?php

namespace MonPartenaire\Auth;

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
            'MonPartenaire\\Auth\\Controller' => '../apps/auth/controller/',
            'MonPartenaire\\Auth\\Model' => '../apps/auth/model/',
            'BCosta' => './../vendor/bcosta/src',
        ])->register();
    }

    public function registerServices($di)
    {
        $di->set('dispatcher', function() use ($di) {
            $eventManager = $di->getShared('eventsManager');
            $eventManager->attach('dispatch:beforeException', function($event, Dispatcher $dispatcher, $exception) {
                //
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MonPartenaire\\Auth\\Controller');
            return $dispatcher;
        });

        $di->set('view', function() {
            $view = new View();
            $view->disable();
            return $view;
        });
    }
}