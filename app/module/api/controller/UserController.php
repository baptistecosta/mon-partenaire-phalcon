<?php

namespace MyTennisPal\Api\Controller;

use MyTennisPal\Api\Model\DataMapper\UserDataMapperAccessorTrait;
use Phalcon\Mvc\Controller;

class UserController extends Controller
{
    use UserDataMapperAccessorTrait;

    public function getAction()
    {
        $accessToken = $this->request->getQuery('accessToken');
        return $this->response
            ->setContentType('application/json')
            ->setJsonContent($this->getUserDataMapper()->findFirstByAccessToken($accessToken));
    }
}