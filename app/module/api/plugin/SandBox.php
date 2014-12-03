<?php

namespace MyTennisPal\Api\Plugin;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class SandBox extends Plugin
{
    public function afterInitialize(Event $event, Dispatcher $dispatcher)
    {
        //
    }
}