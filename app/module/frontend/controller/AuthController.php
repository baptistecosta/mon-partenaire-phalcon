<?php

namespace MyTennisPal\FrontEnd\Controller;

use BCosta\Mvc\Model\Phalcon\User;
use MyTennisPal\FrontEnd\Model\AccessToken\AccessTokenDataMapper;
use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    protected $accessTokenDataMapper;

    public function initialize()
    {
        $this->accessTokenDataMapper = new AccessTokenDataMapper();
    }

    public function signInAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'trim');
            $plainPassword = $this->request->getPost('password');

            $response = $this->accessTokenDataMapper->request($email, $plainPassword);
            if (!empty($response['message']) && $response['message'] === 'success') {
                $accessToken = $response['accessToken'];
                if ($user = User::findFirstById($accessToken['userId'])) {
                    $this->session->set('accessToken', $accessToken);
                    $this->session->set('user', $user->toArray());

                    $this->flash->success('Welcome!');
                    return $this->dispatcher->forward(['controller' => 'index', 'action' => 'index']);
                }
            }

            $this->flash->error('Wrong email/password');
            $this->view->setVar('email', $email);
        }
    }

    public function signOutAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->dispatcher->forward(['controller' => 'index', 'action' => 'index']);
    }
}