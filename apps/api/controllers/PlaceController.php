<?php

namespace MonPartenaire\Api\Controllers;

use BCosta\Validator\Validator;
use Phalcon\Filter;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Validation;

/**
 * Class PlaceController
 * @package MonPartenaire\Api\Controllers
 */
class PlaceController extends Controller
{
    public function scrapAction()
    {
        $response = new Response();
        $response->setContentType('application/json');

        /** @var \BCosta\Sanitizer\Place $sanitizer */
        $sanitizer = $this->di->get('Sanitizer\\Place');
        $sanitizedData = $sanitizer->sanitize($this->request->getPost());

        /** @var Validator $validator */
        $validator = $this->di->get('Validator\\Place');
        if (!$validator->isValid($sanitizedData)) {
            return $response->setJsonContent($validator->getMessages());
        }

        return $response->setJsonContent($sanitizedData);
    }
}