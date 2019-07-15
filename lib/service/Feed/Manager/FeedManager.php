<?php

namespace app\lib\service\Feed\Manager;

use app\lib\entity\PostEntity;
use app\lib\entity\UserEntity;
use app\lib\repository\FollowRepository;
use app\lib\service\Feed\DataProvider\FeedMongoDataProvider;
use app\lib\service\Feed\DataProvider\FeedSqlDataProvider;

class FeedManager
{
    private const FEED_MAX_COUNT = 50;

    /** @var FeedMongoDataProvider $mongoDataProvider */
    private $mongoDataProvider;
    /** @var FeedSqlDataProvider $sqlDataProvider */
    private $sqlDataProvider;

    /**
     * FeedManager constructor.
     */
    public function __construct()
    {
        $this->mongoDataProvider = \Yii::$container->get(FeedMongoDataProvider::class);
        $this->sqlDataProvider = \Yii::$container->get(FeedSqlDataProvider::class);
    }

    /**
     * @param UserEntity $user
     * @param int $offset
     * @param int $limit
     * @return PostEntity[]
     */
    public function getFeed(UserEntity $user, int $offset, int $limit): array
    {
        $posts = [];
        if ($offset < self::FEED_MAX_COUNT) {
            $data = $this->mongoDataProvider->getFeed($user->getId(), $offset);
            $data = array_splice($data, $offset, $limit);
        } else {
            $data = $this->sqlDataProvider->getFeedForUser($user->getId(), $limit, $offset);
        }
        foreach ($data as $value) {
            $posts[] = $this->buildFromArray($value);
        }
        return $posts;
    }

    /**
     * @param UserEntity $user
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\mongodb\Exception
     */
    public function rebuildFeed(UserEntity $user): void
    {
        $posts = $this->sqlDataProvider->getFeedForUser($user->getId(), self::FEED_MAX_COUNT);
        $data = [];
        foreach ($posts as $post) {
            $data[] = $post->toArray();
        }
        $this->mongoDataProvider->setFeed($user->getId(), $data);
    }

    /**
     * @param array $data
     * @return PostEntity
     */
    private function buildFromArray(array $data): PostEntity
    {
        $post = new PostEntity();
        $post->fromArray($data);
        return $post;
    }

    /**
     * @param UserEntity $user
     * @param PostEntity $post
     * @throws \MongoCursorException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function pushToFeedFollowers(UserEntity $user, PostEntity $post): void
    {
        /** @var FollowRepository $followerRepository */
        $followerRepository = \Yii::$container->get(FollowRepository::class);
        $userIds =  $followerRepository->getFollowersIds($user->getId());

        $this->mongoDataProvider->pushPostToFeeds($userIds, $post->toArray());
    }

    /**
     * @param UserEntity $user
     * @throws \yii\mongodb\Exception
     */
    public function createEmptyFeed(UserEntity $user): void
    {
        $this->mongoDataProvider->setFeed($user->getId(), []);
    }
}
