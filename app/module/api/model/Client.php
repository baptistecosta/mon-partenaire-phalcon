<?php

namespace MyTennisPal\Api\Model;

use BCosta\Mvc\Model\Phalcon\Model;

/**
 * Class Client
 * @package MyTennisPal\Module\Api\Model
 */
class Client extends Model
{
    protected $clientId;

    protected $clientSecret;

    protected $redirectUri;

    protected $grantTypes;

    protected $scopes;

    protected $userId;

    public function initialize()
    {
        $this->setConnectionService('db-auth');
    }

    public function getSource()
    {
        return 'client';
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return string
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

    /**
     * @return string
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}