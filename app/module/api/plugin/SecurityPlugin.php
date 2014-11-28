<?php

namespace MyTennisPal\Api\Plugin;

use MyTennisPal\Api\Model\DataMapper\Client as ClientDataMapper;
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

    public function __construct(ClientDataMapper $clientDataMapper, array $publicResources, array $scopes) {
        $this->clientDataMapper = $clientDataMapper;
        $this->publicResources = $publicResources;
        $this->scopes = $scopes;
    }

    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $resource = $dispatcher->getControllerName() . '/' . $dispatcher->getActionName();
        if (in_array($resource, $this->publicResources)) {
            return true;
        }
        $accessToken = $this->request->get('access_token', null, '');
        $clientScopes = $this->clientDataMapper->getScopesFromAccessToken($accessToken);
        if (!$this->isClientAuthorized($this->scopes, $clientScopes, $resource)) {
            $this->response->setStatusCode(401, 'Unauthorized');
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
}