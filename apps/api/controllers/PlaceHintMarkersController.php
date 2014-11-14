<?php

namespace MonPartenaire\Api\Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MonPartenaire\Api\Model\Aggregate\Marker\PlaceHint as PlaceHintMarker;

class PlaceHintMarkersController extends Controller
{
    public function getAction()
    {
        $southWestBound = explode(',', $this->request->getQuery('south-west-bound'));
        $northEastBound = explode(',', $this->request->getQuery('north-east-bound'));

        $response = new Response();
        return $response
            ->setContentType('application/json')
            ->setJsonContent(PlaceHintMarker::fetchAll([
                'latSouth' => $southWestBound[0],
                'latNorth' => $northEastBound[0],
                'lngWest' => $southWestBound[1],
                'lngEast' => $northEastBound[1]
            ]));
    }
}