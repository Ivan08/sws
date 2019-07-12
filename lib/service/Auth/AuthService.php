<?php

namespace app\lib\service\Auth;

use app\lib\entity\UserEntity;

/**
 * Class AuthService
 * @package app\lib\service\Auth
 */
class AuthService
{
    private const REMEMBER_ME_TTL = 31536000;

    /**
     * @param UserEntity $user
     * @param bool $rememberMe
     * @return bool
     */
    public function login(UserEntity $user, bool $rememberMe): bool
    {
        return \Yii::$app->user->login($user, $rememberMe ? self::REMEMBER_ME_TTL : 0);
    }

    /**
     * @param UserEntity $user
     * @param string $password
     * @return bool
     */
    public function validatePassword(UserEntity $user, string $password): bool
    {
        return $user->getPassword() === $password;
    }
}