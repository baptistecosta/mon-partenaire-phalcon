<?php

namespace MyTennisPal\Api\Model\DataMapper;

use BCosta\Mvc\Model\Phalcon\User;

/**
 * Class UserDataMapper
 * @package MyTennisPal\Api\Model\DataMapper
 */
class UserDataMapper
{
    public function findFirstByAccessToken($accessToken)
    {
        $model = new User();
        $connection = $model->getReadConnection()->getInternalHandler();
        $stmt = $connection->prepare("
          SELECT u.id, u.email
          FROM user u
          INNER JOIN access_token a ON a.userId = u.id
          WHERE a.id = :accessToken
          LIMIT 1
        ");
        $stmt->execute([':accessToken' => $accessToken]);
        $record = $stmt->fetch(\PDO::FETCH_ASSOC);
        return [
            'id' => (int)$record['id'],
            'email' => $record['email']
        ];
    }
}