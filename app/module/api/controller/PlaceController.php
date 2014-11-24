<?php

namespace MyTennisPal\Api\Controller;

use BCosta\Validator\Validator;
use Phalcon\Filter;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Validation;

/**
 * Class PlaceController
 * @package MyTennisPal\Api\Controller
 */
class PlaceController extends Controller
{
    public function scrapAction()
    {
        $response = new Response();
        $response->setContentType('application/json');

        /** @var Validator $validator */
        $validator = $this->di->get('Validator\\Place');
        if (!$validator->isValid($this->request->getPost())) {
            return $response->setJsonContent($validator->getMessages())->setStatusCode(400, 'BadRequest');
        }

        return $response->setJsonContent([
            'geolocation' => $validator->getValue('geolocation')
        ]);
    }
}