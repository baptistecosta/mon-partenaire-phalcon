<?php

namespace MyTennisPal\Api\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * Class ExceptionHandler
 * @package MyTennisPal\Api\Plugin
 */
class ExceptionHandler extends Plugin
{
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $exception)
    {
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
    }
}