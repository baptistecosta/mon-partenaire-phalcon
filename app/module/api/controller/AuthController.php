<?php

namespace MyTennisPal\Api\Controller;

use BCosta\Mvc\Model\Phalcon\AccessToken;
use BCosta\Mvc\Model\Phalcon\User;
use BCosta\Validator\Validator;
use MyTennisPal\Api\Model\Client;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Validation;

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

    public function authenticateAction()
    {
        $response = new Response();
        $response->setContentType('application/json');

        $grantType = $this->request->getPost('grantType', null, '');
        switch ($grantType) {
            case 'password':
                /** @var Validator $validator */
                $validator = $this->di->get('Validator\\Auth\\Password');
                if (!$validator->isValid($this->request->getPost())) {
                    return $response->setStatusCode(400, 'Bad request')->setJsonContent($validator->getMessages());
                }

                // Client auth
                $clientId = $validator->getValue('clientId');
                $clientSecret = $validator->getValue('clientSecret');

                /** @var Client $client */
                $client = Client::findFirstByClientId($clientId);
                if (!$client) {
                    return $response->setStatusCode(400, 'Bad request')->setJsonContent(['message' => "Client doesn't exist"]);
                }

                // If the client is "confidential", check the client secret
                if ($client->getClientSecret() && !$this->security->checkHash($clientSecret, $client->getClientSecret())) {
                    return $response->setStatusCode(401, 'Unauthorized client');
                }

                // User auth
                $email = $validator->getValue('email');
                $password = $validator->getValue('password');

                /** @var User $user */
                $user = User::findFirstByEmail($email);
                if (!$user) {
                    return $response->setStatusCode(400, 'Bad request')->setJsonContent(['message' => "User doesn't exist"]);
                }
                if (!$this->security->checkHash($password, $user->getPassword())) {
                    return $response->setStatusCode(401, 'Unauthorized user')->setJsonContent(['message' => "Invalid username/password"]);
                }

                $accessToken = new AccessToken();
                $accessToken->setId(sha1(uniqid(mt_rand(), true)))
                    ->setClientId($client->getClientId())
                    ->setUserId($user->getId())
                    ->setExpires(date('Y-m-d H:i:s', strtotime('+24 hours')));

                if (!$accessToken->save()) {
                    return $response->setStatusCode(500, 'Server error');
                }
                return $response->setJsonContent([
                    'message' => 'success',
                    'accessToken' => $accessToken->toArray()
                ]);

            default:
                return $response->setStatusCode(400, 'Bad request')->setJsonContent(['message' => 'Grant type not supported']);
        }
    }
}