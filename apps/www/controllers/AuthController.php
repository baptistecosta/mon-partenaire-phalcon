<?php

namespace MonPartenaire\Www\Controllers;

use BCosta\Mvc\Model\Phalcon\User;
use BCosta\Security\Password;
use Phalcon\Mvc\Controller;

class AuthController extends Controller
{
    public function signInAction()
    {
        if ($this->request->isPost()) {
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

            if ($user) {
                $this->session->set('auth', [
                    'id' => $user->getId(),
                    'email' => $user->getEmail()
                ]);

                $this->flash->success('Welcome!');
                return $this->dispatcher->forward(['controller' => 'index', 'action' => 'index']);
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