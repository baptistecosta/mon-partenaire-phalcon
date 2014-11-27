<?php

namespace MyTennisPal\Api\Plugin;

use MyTennisPal\Api\Model\Client;
use Phalcon\Acl;
use Phalcon\Config\Adapter\Json as JsonConfig;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;

/**
 * Class SecurityPlugin
 * @package MonPartenaire\module\api\plugin
 */
class SecurityPlugin extends Plugin
{
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $requestedResource = $dispatcher->getControllerName() . '/' . $dispatcher->getActionName();
        if ($this->isPublicResource($requestedResource)) {
            return true;
        }

        $clientId = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        if (!$client = $this->authenticateClient($clientId, $password)) {
            return false;
        }
        return $this->authorizeClient($client->getScopes(), $requestedResource);
    }

    private function isPublicResource($requestedResource)
    {
        $publicResources = new JsonConfig('./../app/config/public-resource.json');
        return in_array($requestedResource, (array)$publicResources);
    }

    /**
     * Client authentication process
     *
     * @param string $user
     * @param string $password
     * @return bool
     */
    private function authenticateClient($user, $password)
    {
        if (!$user || !$password) {
            return false;
        }

        if (!$client = Client::findFirstByClientId($user)) {
            return false;
        }

        $secret = $client->getClientSecret();
        if (!$this->security->checkHash($password, $secret)) {
            return false;
        }
        return $client;
    }

    /**
     * Client authorization process
     *
     * @param $clientScopes
     * @param $requestedResource
     * @return bool
     */
    private function authorizeClient($clientScopes, $requestedResource)
    {
        if ($clientScopes === '*') {
            return true;
        }

        $scopesResources = new JsonConfig('./../app/config/scope-resources.json');
        $clientScopes = explode(' ', $clientScopes);
        foreach ($clientScopes as $clientScope) {
            foreach ($scopesResources as $scope => $resources) {
                if ($scope === $clientScope && in_array($requestedResource, (array)$resources)) {
                    return true;
                }
            }
        }
        return false;
    }
}