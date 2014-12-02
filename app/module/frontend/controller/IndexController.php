<?php

namespace MyTennisPal\FrontEnd\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
//        phpinfo();
        if ($this->session->has('auth')) {
            $auth = $this->session->get('auth');
        }
    }
}