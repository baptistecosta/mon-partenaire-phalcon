<?php

namespace MonPartenaire\Api\Controllers
{
    use Phalcon\Mvc\Controller;
    use Phalcon\Mvc\Dispatcher;

    class IndexController extends Controller
    {
        public function indexAction()
        {
            echo json_encode(['message' => 'popo']);
        }

        public function afterExecuteRoute(Dispatcher $dispatcher)
        {
            echo __FUNCTION__;
        }
    }
}