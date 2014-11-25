<?php

namespace MyTennisPal\Api\Controller;

use BCosta\Mvc\Model\Phalcon\AccessToken;
use BCosta\Mvc\Model\Phalcon\User;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

/**
 * Class AuthController
 * @package MyTennisPal\Api\Controller
 */
class AuthController extends Controller
{
    public function registerUserAction()
    {
        $response = new Response();
        $response->setContentType('application/json');

        $user = new User();
        $user->email = $this->request->getPost('email', ['trim']);
        $user->password = $this->security->hash($this->request->getPost('password', ['trim']));

        if ($user->save()) {
            return $response->setJsonContent(['id' => $user->getId()]);
        } else {
            return $response->setStatusCode(400, 'Bad request')->setJsonContent($user->getErrorMessages());
        }
    }

    public function requestTokenAction()
    {
        $email = $this->request->getPost('email', 'trim');
        $password = $this->request->getPost('password', null, '');

        $user = User::findFirstByEmail($email);

        $response = new Response();
        $response->setContentType('application/json');

        if (!$user) {
            return $response->setStatusCode(400, 'Bad request')->setJsonContent(['message' => "Email doesn't exist"]);
        }

        if (!$this->security->checkHash($password, $user->getPassword())) {
            return $response->setStatusCode(401, "Unauthorized");
        }
        $accessToken = new AccessToken();
        $accessToken->setId(sha1(uniqid(mt_rand(), true)))
            ->setUserId($user->getId())
            ->setExpires(date('Y-m-d H:i:s', strtotime('+24 hours')));

        if (!$accessToken->save()) {
            return $response->setStatusCode(500, 'Server error');
        }
        return $response->setJsonContent($accessToken->toArray());
    }
}