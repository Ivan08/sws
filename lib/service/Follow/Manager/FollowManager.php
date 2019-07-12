<?php

namespace app\lib\service\Follow\Manager;

use app\lib\entity\FollowEntity;
use app\lib\entity\UserEntity;
use app\lib\repository\FollowRepository;

/**
 * Class FollowManager
 * @package app\lib\service\Follow\Manager
 */
class FollowManager
{
    /** @var FollowRepository $followRepository*/
    private $followRepository;

    /**
     * FollowManager constructor.
     */
    public function __construct()
    {
        $this->followRepository = \Yii::$container->get(FollowRepository::class);
    }

    /**
     * @param UserEntity $userFrom
     * @param UserEntity $userTo
     * @return FollowEntity
     * @throws \Throwable
     */
    public function subscribe(UserEntity $userFrom, UserEntity $userTo): FollowEntity
    {
        $followEntity = new FollowEntity();
        $followEntity->setUserTo($userTo->getId());
        $followEntity->setUserFrom($userFrom->getId());
        $followEntity->setCreatedAt(time());

        return $this->followRepository->add($followEntity);
    }

    /**
     * @param UserEntity $userFrom
     * @param UserEntity $userTo
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function unsubscribe(UserEntity $userFrom, UserEntity $userTo)
    {
        $followEntity = new FollowEntity();
        $followEntity->setUserTo($userTo->getId());
        $followEntity->setUserFrom($userFrom->getId());
        $followEntity->setCreatedAt(time());

        return $this->followRepository->delete($followEntity);
    }

    /**
     * @param UserEntity $userFrom
     * @param UserEntity $userTo
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(UserEntity $userFrom, UserEntity $userTo)
    {
        $followEntity = new FollowEntity();
        $followEntity->setUserTo($userTo->getId());
        $followEntity->setUserFrom($userFrom->getId());

        return $this->followRepository->delete($followEntity);
    }

    /**
     * @param UserEntity $loggedUser
     * @param UserEntity $user
     * @return bool
     */
    public function isSubscribe(UserEntity $loggedUser, UserEntity $user): bool
    {
        $followEntity = new FollowEntity();
        $followEntity->setUserTo($user->getId());
        $followEntity->setUserFrom($loggedUser->getId());

        return $this->followRepository->exists($followEntity);
    }
}
