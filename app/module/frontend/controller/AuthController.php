<?php

namespace MyTennisPal\FrontEnd\Controller;

use MyTennisPal\Frontend\Model\AccessTokenDataMapper;
use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    public function signInAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'trim');
            $plainPassword = $this->request->getPost('password');


            $accessTokenDataMapper = new AccessTokenDataMapper();
            $response = $accessTokenDataMapper->request($email, $plainPassword);

/*            $user = User::findFirstByEmail($email);

            if ($user && $this->security->checkHash($plainPassword, $user->getPassword())) {
                $this->session->set('auth', [
                    'id' => $user->getId(),
                    'email' => $user->getEmail()
                ]);

                $this->flash->success('Welcome!');
                return $this->dispatcher->forward(['controller' => 'index', 'action' => 'index']);
            }

            $this->flash->error('Wrong email/password');

            $this->view->setVar('email', $email);*/
        }
    }

    public function signOutAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->dispatcher->forward(['controller' => 'index', 'action' => 'index']);
    }
}