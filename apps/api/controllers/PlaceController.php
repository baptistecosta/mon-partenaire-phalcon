<?php

namespace MonPartenaire\Api\Controllers;

use Phalcon\Filter;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

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

        $post = $this->request->getPost();

        /** @var Filter $filter */
        $filter = $this->di['place-filter'];
        $inputs['geolocation'] = $filter->sanitize($post['geolocation'], 'geolocation');

        $validation = new Validation();
        $validation->add('geolocation', new PresenceOf([
            'message' => 'The geolocation is required'
        ]));
        $messages = $validation->validate($inputs);

        if (count($messages)) {
            return $response->setJsonContent($messages);
        }

        return $response->setJsonContent($inputs);
    }
}