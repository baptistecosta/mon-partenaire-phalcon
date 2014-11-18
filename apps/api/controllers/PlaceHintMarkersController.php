<?php

namespace MonPartenaire\Api\Controllers;

use Phalcon\Filter;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MonPartenaire\Api\Model\Aggregate\Marker\PlaceHint as PlaceHintMarker;

class PlaceHintMarkersController extends Controller
{
    public function getAction()
    {
        /** @var Filter $filter */
        $filter = $this->di->get('Filter\\Geolocation');
        $southWestBound = $filter->sanitize($this->request->getQuery('south-west-bound'), 'geolocation');
        $northEastBound = $filter->sanitize($this->request->getQuery('north-east-bound'), 'geolocation');

        $foo = $this->request->getQuery('south-west-bound', 'geolocation');

        $response = new Response();
        return $response
            ->setContentType('application/json')
            ->setJsonContent(PlaceHintMarker::fetchAll([
                'latSouth' => $southWestBound['latitude'],
                'latNorth' => $northEastBound['latitude'],
                'lngWest' => $southWestBound['longitude'],
                'lngEast' => $northEastBound['longitude']
            ]));
    }
}