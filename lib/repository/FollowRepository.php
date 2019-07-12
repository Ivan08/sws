<?php

namespace app\lib\repository;

use app\lib\entity\builder\UserEntityBuilder;
use app\lib\entity\FollowEntity;
use app\models\Follow;
use yii\db\Query;

/**
 * Class FollowRepository
 * @package app\lib\repository
 */
class FollowRepository
{
    /**
     * @param int $userId
     * @return array
     */
    public function getFollowingIds(int $userId): array
    {
        $ids = Follow::find()
            ->select('user_to')
            ->where(['user_from' => $userId])
            ->column();
        return $ids;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getFollowersIds(int $userId): array
    {
        $ids = Follow::find()
            ->select('user_from')
            ->where(['user_to' => $userId])
            ->column();
        $ids = array_map(function ($value) {return (int)$value;}, $ids);
        return $ids;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getFollowing(int $userId): array
    {
        $result = [];
        $data = (new Query())
            ->from('follow')
            ->where(['user_from' => $userId])
            ->innerJoin('user', '`user`.`id` = `follow`.`user_to`') //ToDo: Add criteria
            ->all();
        foreach ($data as $value) {
            $result[] = UserEntityBuilder::buildFromArray($value);
        }

        return $result;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getFollowers(int $userId): array
    {
        $result = [];
        $data = (new Query())
            ->from('follow')
            ->where(['user_to' => $userId])
            ->innerJoin('user', '`user`.`id` = `follow`.`user_from`') //ToDo: Add criteria
            ->all();
        foreach ($data as $value) {
            $result[] = UserEntityBuilder::buildFromArray($value);
        }

        return $result;
    }

    /**
     * @param FollowEntity $followEntity
     * @return FollowEntity
     * @throws \Throwable
     */
    public function add(FollowEntity $followEntity): FollowEntity
    {
        $follow = new Follow();
        $follow->setScenario(Follow::SCENARIO_CREATE);
        $follow->user_from = $followEntity->getUserFrom();
        $follow->user_to = $followEntity->getUserTo();
        $follow->created_at = $followEntity->getCreatedAt();

        $follow->insert();

        return $this->buildFromModel($follow);
    }

    /**
     * @param FollowEntity $followEntity
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(FollowEntity $followEntity): void
    {
        $follow = Follow::find()
            ->where(['user_from' => $followEntity->getUserFrom()])
            ->andWhere(['user_to' => $followEntity->getUserTo()])
            ->one();

        if ($follow) {
            $follow->delete();
        }
    }

    /**
     * @param FollowEntity $followEntity
     * @return bool
     */
    public function exists(FollowEntity $followEntity): bool
    {
        return Follow::find()
            ->where(['user_from' => $followEntity->getUserFrom()])
            ->andWhere(['user_to' => $followEntity->getUserTo()])
            ->exists();
    }

    /**
     * @param Follow $follow
     * @return FollowEntity
     */
    private function buildFromModel(Follow $follow): FollowEntity
    {
        $followEntity = new FollowEntity();
        $followEntity->setUserFrom($follow->user_from);
        $followEntity->setUserTo($follow->user_to);
        $followEntity->setCreatedAt($follow->created_at);

        return $followEntity;
    }
}
