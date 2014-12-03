<?php

namespace MyTennisPal\Api\Model\DataMapper;

/**
 * Class UserDataMapperAccessorTrait
 * @package MyTennisPal\Api\Model\DataMapper
 */
trait UserDataMapperAccessorTrait
{
    /**
     * @var UserDataMapper
     */
    protected $userDataMapper;

    /**
     * @param UserDataMapper $userDataMapper
     * @return $this
     */
    public function setUserDataMapper(UserDataMapper $userDataMapper)
    {
        $this->userDataMapper = $userDataMapper;
        return $this;
    }

    /**
     * @return UserDataMapper
     */
    public function getUserDataMapper()
    {
        return $this->userDataMapper;
    }
}