<?php

namespace MyTennisPal\Api\Plugin;

use Phalcon\Events\Event;
use Phalcon\Exception;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * Class BodyParser
 * @package MyTennisPal\Api\Plugin
 */
class BodyParser extends Plugin
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $contentType = $this->request->getHeader('CONTENT_TYPE');
        switch ($contentType) {
            case 'application/json':
            case 'application/json;charset=UTF-8':
                $jsonRawBody = $this->request->getJsonRawBody(true);
                if ($this->request->getRawBody() && !$jsonRawBody) {
                    throw new Exception("Invalid JSON syntax");
                }
                $_POST = $jsonRawBody;
                break;
        }
    }
}