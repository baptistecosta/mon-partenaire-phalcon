<?php

namespace MonPartenaire\Api\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MonPartenaire\Api\Models\PlaceMarkers;

class PlaceMarkersController extends Controller
{
    public function getAction()
    {
        $response = new Response();
        return $response
            ->setContentType('application/json')
            ->setJsonContent(PlaceMarkers::fetchAll());
    }
}