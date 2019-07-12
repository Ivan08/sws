<?php

namespace app\lib\entity\builder;

use app\lib\entity\UserEntity;
use app\models\User;

/**
 * Class UserBuilder
 * @package app\lib\entity\builder
 */
class UserEntityBuilder
{
    /**
     * @param User $user
     * @return UserEntity
     */
    public static function buildFromModel(User $user): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->fromArray($user->toArray());
        $userEntity->setAuthKey($user->auth_key);
        $userEntity->setAccessToken($user->access_token);

        return $userEntity;
    }

    /**
     * @param array $data
     * @return UserEntity
     */
    public static function buildFromArray(array $data): UserEntity
    {
        $userEntity = new UserEntity();
        if (isset($data['id'])) {
            $userEntity->setId($data['id']);
        }
        if (isset($data['username'])) {
            $userEntity->setUsername($data['username']);
        }
        if (isset($data['password'])) {
            $userEntity->setPassword($data['password']);
        }
        if (isset($data['auth_key'])) {
            $userEntity->setAuthKey($data['auth_key']);
        }
        if (isset($data['access_token'])) {
            $userEntity->setAccessToken($data['access_token']);
        }

        return $userEntity;
    }
}