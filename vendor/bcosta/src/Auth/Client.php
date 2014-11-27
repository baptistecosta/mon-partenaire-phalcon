<?php

class Client
{
    protected $requestedResource;

    protected $clientId;

    protected $clientPassword;

    protected $clientScopes;

    protected $publicResources;

    protected $scopesResources;

    public function isPublicResource()
    {
        return in_array($this->requestedResource, $this->publicResources);
    }

    /**
     * Client authentication process
     *
     * @return bool
     */
    public function authenticateClient(Callable $func)
    {
        $hashedClientSecret = $this->clientDataMapper->getClientSecret();
        return $func($this->clientPassword, $hashedClientSecret);
    }

    /**
     * Client authorization process
     *
     * @return bool
     */
    public function authorizeClient()
    {
        $clientScopes = explode(' ', $this->clientScopes);
        foreach ($clientScopes as $clientScope) {
            foreach ($this->scopesResources as $scope => $resources) {
                if ($scope === $clientScope && in_array($this->requestedResource, $resources)) {
                    return true;
                }
            }
        }
        return false;
    }
}