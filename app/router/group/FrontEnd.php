<?php

namespace MyTennisPal\Router\Group;

use Phalcon\Mvc\Router\Group;

/**
 * Class FrontEnd
 * @package MyTennisPal\Router\Group
 */
class FrontEnd extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'frontend',
            'namespace' => 'MyTennisPal\FrontEnd\Controller'
        ]);
        $this->setPrefix('/');
        $this->add('/', [
            'controller' => 'index',
            'action' => 'index'
        ]);
        $this->add('/auth/sign-in', [
            'controller' => 'auth',
            'action' => 'signIn'
        ]);
        $this->add('/auth/sign-out', [
            'controller' => 'auth',
            'action' => 'signOut'
        ]);
        $this->add('/sand-box', [
            'controller' => 'sand-box',
            'action' => 'index'
        ]);
    }
}