<?php

namespace app\lib\repository;

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
            return $this->buildFromModel($user);
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
            return $this->buildFromModel($user);
        }
        return null;
    }

    /**
     * @param User $user
     * @return UserEntity
     */
    private function buildFromModel(User $user): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->fromArray($user->toArray());
        $userEntity->setAuthKey($user->auth_key);
        $userEntity->setAccessToken($user->access_token);

        return $userEntity;
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
            return $this->buildFromModel($user);
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

        return $this->buildFromModel($user);
    }
}