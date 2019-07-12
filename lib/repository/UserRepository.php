<?php

namespace app\lib\repository;

use app\lib\entity\builder\UserEntityBuilder;
use app\lib\entity\UserEntity;
use app\models\User;

/**
 * Class UserRepository
 * @package app\lib\repository\User
 */
class UserRepository
{
    /**
     * @param string $username
     * @return UserEntity
     */
    public function getByUsername(string $username): ?UserEntity
    {
        $user = User::find()
            ->where(['username' => $username])
            ->one();
        if ($user) {
            return UserEntityBuilder::buildFromModel($user);
        }
        return null;
    }

    /**
     * @return UserEntity|null
     */
    public function getLoggedUser(): ?UserEntity
    {
        return \Yii::$app->user->identity;
    }

    /**
     * @param int $id
     * @return UserEntity|null
     */
    public function getById(int $id): ?UserEntity
    {
        $user = User::find()
            ->where(['id' => $id])
            ->one();
        if ($user) {
            return UserEntityBuilder::buildFromModel($user);
        }
        return null;
    }

    /**
     * @param string $token
     * @return UserEntity|null
     */
    public function getByAccessToken(string $token): ?UserEntity
    {
        $user = User::find()
            ->where(['access_token' => $token])
            ->one();
        if ($user) {
            return UserEntityBuilder::buildFromModel($user);
        }
        return null;
    }

    /**
     * @param UserEntity $userEntity
     * @return UserEntity
     * @throws \Throwable
     */
    public function insert(UserEntity $userEntity): UserEntity
    {
        $user = new User();
        $user->setScenario(User::SCENARIO_CREATE);
        $user->attributes = $userEntity->toArray();
        $user->auth_key = $userEntity->getAuthKey();
        $user->access_token = $userEntity->getAccessToken();

        $user->insert();

        return UserEntityBuilder::buildFromModel($user);
    }
}