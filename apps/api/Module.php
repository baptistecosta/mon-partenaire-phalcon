<?php

namespace MonPartenaire\Api;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces([
            'MonPartenaire\\Api\\Controllers' => '../apps/api/controllers/',
            'MonPartenaire\\Api\\Models' => '../apps/api/models/',
        ])->register();
    }

    public function registerServices($di)
    {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MonPartenaire\\Api\\Controllers');
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir('../apps/api/views/');
            return $view;
        });
    }
}