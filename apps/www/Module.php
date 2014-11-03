<?php

namespace MonPartenaire\Www;

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
            'MonPartenaire\\Www\\Controllers' => '../apps/www/controllers/',
            'MonPartenaire\\Www\\Models' => '../apps/www/models/',
        ])->register();
    }

    public function registerServices($di)
    {
        //Registering a dispatcher
        $di->set('dispatcher', function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('MonPartenaire\\Www\\Controllers');
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir('../apps/www/views/');
            return $view;
        });
    }
}