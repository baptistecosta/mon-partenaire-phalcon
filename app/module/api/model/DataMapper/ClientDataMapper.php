<?php

namespace MyTennisPal\Api\Model\DataMapper;

use MyTennisPal\Api\Model\Client as ClientModel;
use Phalcon\Mvc\Model\CriteriaInterface;

/**
 * Class ClientDataMapper
 * @package MyTennisPal\Api\Model\DataMapper
 */
class ClientDataMapper
{
    public function getScopes($clientId)
    {
        $result = ClientModel::findFirst([
            'columns' => ['scopes'],
            'conditions' => 'clientId = ?1',
            'bind' => [1 => $clientId]
        ]);

        return empty($result) ? '' : $result->scopes;
    }

    /** @deprecated */
    public function getScopesFromAccessToken($accessToken)
    {
        /** @var CriteriaInterface $criteria */
        $criteria = ClientModel::query()
            ->columns(['scopes'])
            ->innerJoin(
                'BCosta\Mvc\Model\Phalcon\AccessToken',
                'MyTennisPal\Api\Model\Client.clientId = AccessToken.clientId',
                'AccessToken'
            )
            ->where('id = :id:')
            ->andWhere("expires >= :expires:")
            ->limit(1)
            ->bind([
                'id' => $accessToken,
                'expires' => date('Y-m-d H:i:s')
            ]);

        $results = $criteria->execute();
        $client = $results->getFirst();

        return empty($client->scopes) ? '' : $client->scopes;
    }
}