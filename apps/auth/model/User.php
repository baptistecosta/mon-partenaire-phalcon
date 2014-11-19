<?php

namespace MonPartenaire\Auth\Model;

use BCosta\Mvc\Model\PhalconModel;
use BCosta\Security\Password;

use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Class User
 * @package MonPartenaire\Auth\model
 */
class User extends PhalconModel
{
    protected $id;

    protected $email;

    protected $password;

    public function initialize()
    {
        $this->setConnectionService('db-auth');
    }

    public function getSource()
    {
        return 'user';
    }

    public function validation()
    {
        $this->validate(new Uniqueness([
            'field' => 'email',
            'message' => "Cet email existe déjà en base de données"
        ]));

        $this->validate(new StringLength([
            'field' => 'password',
            'max' => 16,
            'min' => 4,
            'messageMaximum' => "Le mot de passe est trop long, 16 caractères maximum",
            'messageMinimum' => "Le mot de passe est trop court, 4 caractères minimum"
        ]));

        return $this->validationHasFailed() != true;
    }

    public function beforeSave()
    {
        if ($this->password) {
            $salt = $this->getDI()->getShared('security-salt');
            $this->password = Password::sha1($this->password, $salt);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}