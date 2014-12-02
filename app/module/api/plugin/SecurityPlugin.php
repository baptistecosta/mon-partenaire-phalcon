<?php

namespace MyTennisPal\Api\Plugin;

use MyTennisPal\Api\Model\DataMapper\AccessTokenDataMapper;
use MyTennisPal\Api\Model\DataMapper\ClientDataMapper;
use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;

/**
 * Class SecurityPlugin
 * @package MonPartenaire\module\api\plugin
 */
class SecurityPlugin extends Plugin
{
    /**
     * @var AccessTokenDataMapper
     */
    protected $accessTokenDataMapper;

    /**
     * @var ClientDataMapper
     */
    protected $clientDataMapper;

    /**
     * @var array
     */
    protected $publicResources;

    /**
     * @var array
     */
    protected $scopes;

    public function __construct(AccessTokenDataMapper $accessTokenDataMapper, ClientDataMapper $clientDataMapper, array $publicResources, array $scopes)
    {
        $this->accessTokenDataMapper = $accessTokenDataMapper;
        $this->clientDataMapper = $clientDataMapper;

        $this->publicResources = $publicResources;
        $this->scopes = $scopes;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $resource = $dispatcher->getControllerName() . '/' . $dispatcher->getActionName();
        if (in_array($resource, $this->publicResources)) {
            return true;
        }

        $accessTokenId = $this->request->get('access_token', null, '');
        $accessToken = $this->accessTokenDataMapper->findFirstById($accessTokenId);
        if (!$accessToken) {
            $this->onInvalidAccessToken();
            return false;
        }

        if ($this->hasAccessTokenExpired($accessToken['expires'])) {
            $this->onAccessTokenExpired();
            return false;
        }

        $clientScopes = $this->clientDataMapper->getScopes($accessToken['clientId']);
        if (!$this->isClientAuthorized($this->scopes, $clientScopes, $resource)) {
            $this->response->setStatusCode(403, 'Forbidden')->send();
            return false;
        }
        return true;
    }

    private function isClientAuthorized($scopes, $clientScopes, $resource)
    {
        if ($clientScopes === '*') {
            return true;
        }
        $clientScopes = explode(' ', $clientScopes);
        foreach ($clientScopes as $clientScope) {
            foreach ($scopes as $scope => $resources) {
                if ($scope === $clientScope && in_array($resource, (array)$resources)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function hasAccessTokenExpired($expires)
    {
        return strtotime($expires) < strtotime('now');
    }

    private function onInvalidAccessToken()
    {
        $this->response
            ->setStatusCode(401, 'Unauthorized')
            ->setJsonContent(['message' => 'Invalid access token'])
            ->setContentType('application/json')
            ->send();
    }

    private function onAccessTokenExpired()
    {
        $this->response
            ->setStatusCode(401, 'Unauthorized')
            ->setJsonContent(['message' => 'The token has expired'])
            ->setContentType('application/json')
            ->send();
    }
}
