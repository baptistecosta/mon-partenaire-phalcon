<?php

namespace MyTennisPal\Api\Model\DataMapper;

use BCosta\Mvc\Model\Phalcon\AccessToken;

/**
 * Class AccessTokenDataMapper
 * @package MonPartenaire\Module\Api\Model\DataMapper
 */
class AccessTokenDataMapper
{
    /**
     * @param int $accessTokenId
     * @return AccessToken
     */
    public function findFirstById($accessTokenId)
    {
        $model = new AccessToken();
        $connection = $model->getReadConnection()->getInternalHandler();
        $stmt = $connection->prepare("SELECT clientId, expires FROM access_token WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $accessTokenId);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}