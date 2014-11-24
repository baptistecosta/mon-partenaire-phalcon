<?php

namespace MyTennisPal\Api\Controller;

use BCosta\Validator\Validator;
use Phalcon\Filter;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use MyTennisPal\Api\Model\Aggregate\Marker\PlaceHint as PlaceHintMarker;

class PlaceHintMarkersController extends Controller
{
    public function getAction()
    {
        $response = new Response();

        /** @var Validator $validator */
        $validator = $this->di->get('Validator\\PlaceHintMarker');
        if (!$validator->isValid($this->request->getQuery())) {
            return $response->setJsonContent($validator->getMessages())->setStatusCode(400, 'BadRequest');
        }

        $content = PlaceHintMarker::fetchAll([
            'zoom' => $validator->getValue('zoom'),
            'southWestBound' => $validator->getValue('south-west-bound'),
            'northEastBound' => $validator->getValue('north-east-bound'),
        ]);

        return $response
            ->setContentType('application/json')
            ->setJsonContent($content);
    }
}