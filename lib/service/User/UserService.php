<?php

namespace app\lib\service\User;

use app\lib\amqp\Producer\CreateEmptyFeedProducer;
use app\lib\entity\UserEntity;
use app\lib\service\User\Manager\UserManager;

/**
 * Class UserService
 * @package app\lib\service\User
 */
class UserService
{
    /**
     * @param array $userData
     * @return UserEntity
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function add(array $userData): UserEntity
    {
        $manager = $this->getManager();
        $user = $manager->insert($userData);

        $producer = new CreateEmptyFeedProducer();
        $producer->publish($user);

        return $user;
    }

    /**
     * @return UserManager
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private function getManager(): UserManager
    {
        return \Yii::$container->get(UserManager::class);
    }
}