<?php

namespace MyTennisPal\Router\Group;

use Phalcon\Mvc\Router\Group;

/**
 * Class Api
 * @package MyTennisPal\Router\Group
 */
class Api extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'module' => 'api',
            'namespace' => 'MyTennisPal\Api\Controller'
        ]);
        $this->setPrefix('/api');
        $this->add('', [
            'controller' => 'index',
            'action' => 'index'
        ]);
        $this->add('/error', [
            'controller' => 'error',
            'action' => 'handleException'
        ]);
        $this->addPost('/auth', [
            'controller' => 'auth',
            'action' => 'authenticate'
        ]);
        $this->addPost('/auth/register-user', [
            'controller' => 'auth',
            'action' => 'registerUser'
        ]);
        $this->addGet('/place-markers', [
            'controller' => 'place_markers',
            'action' => 'get'
        ]);
        $this->addGet('/place-hint-markers', [
            'controller' => 'place_hint_markers',
            'action' => 'get'
        ]);
        $this->addGet('/scrapped-place-markers', [
            'controller' => 'scrapped_place_markers',
            'action' => 'get'
        ]);
        $this->addPost('/place/scrap', [
            'controller' => 'place',
            'action' => 'scrap'
        ]);
    }
}