<?php

namespace app\lib\service\Follow;

use app\lib\amqp\Producer\RebuildFeedProducer;
use app\lib\entity\UserEntity;
use app\lib\service\Follow\Manager\FollowManager;

/**
 * Class FollowService
 * @package app\lib\service\Follow
 */
class FollowService
{
    /**
     * @param UserEntity $userFrom
     * @param UserEntity $userTo
     * @return bool
     * @throws \Throwable
     */
    public function subscribe(UserEntity $userFrom, UserEntity $userTo): bool
    {
        $manager = $this->getManager();
        $manager->subscribe($userFrom, $userTo);

        $producer = new RebuildFeedProducer();
//        $producer->publish($userFrom);

        return true;
    }

    /**
     * @param UserEntity $userFrom
     * @param UserEntity $userTo
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function unsubscribe(UserEntity $userFrom, UserEntity $userTo): bool
    {
        $manager = $this->getManager();
        $manager->unsubscribe($userFrom, $userTo);

        $producer = new RebuildFeedProducer();
        $producer->publish($userFrom);

        return true;
    }

    /**
     * @param UserEntity $userFrom
     * @param UserEntity $userTo
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(UserEntity $userFrom, UserEntity $userTo): bool
    {
        $manager = $this->getManager();
        $manager->delete($userFrom, $userTo);

        $producer = new RebuildFeedProducer();
        $producer->publish($userFrom);

        return true;
    }

    /**
     * @return FollowManager
     */
    private function getManager(): FollowManager
    {
        return \Yii::$container->get(FollowManager::class);
    }

    /**
     * @param UserEntity $loggedUser
     * @param UserEntity $user
     * @return bool
     */
    public function isSubscribe(UserEntity $loggedUser, UserEntity $user): bool
    {
        $manager = $this->getManager();
        return $manager->isSubscribe($loggedUser, $user);
    }
}
