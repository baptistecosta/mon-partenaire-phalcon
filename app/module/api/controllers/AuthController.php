<?php

namespace MyTennisPal\Api\Controller;

use BCosta\Mvc\Model\Phalcon\User;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

/**
 * Class AuthController
 * @package MyTennisPal\auth\controller
 */
class AuthController extends Controller
{
    public function addAction()
    {
        $response = new Response();
        $response->setContentType('application/json');

        $user = new User();
        $user->email = $this->request->getPost('email', ['trim']);
        $user->password = $this->request->getPost('password', ['trim']);

        if ($user->save()) {
            return $response->setJsonContent(['id' => $user->getId()]);
        } else {
            return $response->setStatusCode(400, 'Bad request')->setJsonContent($user->getErrorMessages());
        }
    }
}