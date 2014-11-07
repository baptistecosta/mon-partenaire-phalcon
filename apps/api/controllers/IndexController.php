<?php

namespace MonPartenaire\Api\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class IndexController extends Controller
{
    public function indexAction()
    {
        $response = new Response();
        return $response->setJsonContent(['message' => 'MonPartenaire - API endpoint v0.1'])
            ->setContentType('application/json');
    }

    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
    }
}