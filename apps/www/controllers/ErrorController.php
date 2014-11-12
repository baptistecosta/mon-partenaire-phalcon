<?php

namespace MonPartenaire\Www\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ErrorController extends Controller
{
    public function show404Action()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
}