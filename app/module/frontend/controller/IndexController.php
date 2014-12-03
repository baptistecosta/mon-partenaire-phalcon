<?php

namespace MyTennisPal\FrontEnd\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
//        phpinfo();
        if ($this->session->has('accessToken')) {
            $accessToken = $this->session->get('accessToken');
        }

        if ($this->session->has('user')) {
            $auth = $this->session->get('user');
        }
    }
}