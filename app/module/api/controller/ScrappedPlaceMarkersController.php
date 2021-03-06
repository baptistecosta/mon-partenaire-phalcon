<?php

namespace MyTennisPal\Api\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MyTennisPal\Api\Model\Aggregate\Marker\ScrappedPlace as ScrappedPlaceMarker;

class ScrappedPlaceMarkersController extends Controller
{
    public function getAction()
    {
        $response = new Response();
        return $response
            ->setContentType('application/json')
            ->setJsonContent(ScrappedPlaceMarker::fetchAll());
    }
}