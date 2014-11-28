<?php

namespace MyTennisPal\FrontEnd\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
//        $pw = $this->security->hash('s51Dz38e4cZ8');
//        die($pw);
        phpinfo();
        $timezone = date_default_timezone_get();

        date_default_timezone_set("Europe/Paris");
        $now = date('Y-m-d H:i:s');
        die($timezone);
    }
}