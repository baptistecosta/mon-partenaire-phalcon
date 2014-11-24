<?php

namespace MyTennisPal\Api\Controller;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MyTennisPal\Api\Model\Aggregate\Marker\Place as PlaceMarker;

class PlaceMarkersController extends Controller
{
    public function getAction()
    {
        $southWestBound = explode(',', $this->request->getQuery('south-west-bound'));
        $northEastBound = explode(',', $this->request->getQuery('north-east-bound'));

        $response = new Response();
        return $response
            ->setContentType('application/json')
            ->setJsonContent(PlaceMarker::fetchAll([
                'latSouth' => $southWestBound[0],
                'latNorth' => $northEastBound[0],
                'lngWest' => $southWestBound[1],
                'lngEast' => $northEastBound[1]
            ]));
    }
}