<?php

namespace BCosta\Mvc\Model\Phalcon;

/**
 * Class AccessToken
 * @package BCosta\Mvc\Model\Phalcon
 */
class AccessToken extends Model
{
    protected $id;

    protected $user_id;

    protected $expires;

    public function initialize()
    {
        $this->setConnectionService('db-auth');
    }

    public function getSource()
    {
        return 'access_token';
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param mixed $expires
     * @return $this
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'expires' => $this->getExpires(),
        ];
    }
}