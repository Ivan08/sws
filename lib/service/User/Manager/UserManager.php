<?php


namespace app\lib\service\User\Manager;


use app\lib\entity\UserEntity;
use app\lib\repository\UserRepository;

class UserManager
{
    /**
     * @param array $userData
     * @return UserEntity
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function insert(array $userData): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->fromArray($userData);

        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        return $userRepository->insert($userEntity);
    }
}