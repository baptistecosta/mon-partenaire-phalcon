<?php

namespace MonPartenaire\Auth\Model;

use BCosta\Security\Password;
use Phalcon\Mvc\Model;

/**
 * Class User
 * @package MonPartenaire\Auth\model
 */
class User extends Model
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

    public function beforeSave()
    {
        if ($this->password) {
            $salt = $this->getDI()->getShared('security-salt');
            $this->password = Password::sha1($this->password, $salt);
        }
    }
}