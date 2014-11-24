<?php

namespace MyTennisPal\Api\Controller;

use BCosta\Mvc\Model\Phalcon\AccessToken;
use BCosta\Mvc\Model\Phalcon\User;
use BCosta\Security\Password;
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
        $user->password = $this->request->getPost('password', ['trim']);

        if ($user->save()) {
            return $response->setJsonContent(['id' => $user->getId()]);
        } else {
            return $response->setStatusCode(400, 'Bad request')->setJsonContent($user->getErrorMessages());
        }
    }

    public function requestTokenAction()
    {
        $email = $this->request->getPost('email', 'trim');
        $plainPassword = $this->request->getPost('password');

        $salt = $this->getDI()->getShared('security-salt');
        $password = Password::sha1($plainPassword, $salt);

        $user = User::findFirst([
            'email = :email: AND password = :password: AND active = 1',
            'bind' => [
                'email' => $email,
                'password' => $password
            ]
        ]);

        $response = new Response();
        $response->setContentType('application/json');

        if (!$user) {
            return $response->setStatusCode(401, 'Unauthorized');
        }
        $accessToken = new AccessToken();
        $accessToken->setId(sha1(uniqid(mt_rand(), true)));
        $accessToken->setUserId($user->getId());
        $accessToken->setExpires(date('Y-m-d H:i:s', strtotime('+24 hours')));
        if (!$accessToken->save()) {
            return $response->setStatusCode(500, 'Server error');
        }
        return $response->setJsonContent($accessToken->toArray());
    }
}