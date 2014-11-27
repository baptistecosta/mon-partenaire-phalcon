<?php

namespace MyTennisPal\Api\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ErrorController extends Controller
{
    public function handleExceptionAction()
    {
        $this->response->setContentType('application/json')->setJsonContent(['message' => 'popo']);
    }
}