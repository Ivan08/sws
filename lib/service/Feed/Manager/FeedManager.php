<?php

namespace app\lib\service\Feed\Manager;

use app\lib\entity\PostEntity;
use app\lib\entity\UserEntity;
use app\lib\repository\FollowRepository;
use app\lib\repository\PostRepository;
use app\lib\service\Feed\DataProvider\FeedMongoDataProvider;

class FeedManager
{
    /** @var FeedMongoDataProvider $mongoDataProvider */
    private $mongoDataProvider;

    /**
     * FeedManager constructor.
     */
    public function __construct()
    {
        $this->mongoDataProvider = \Yii::$container->get(FeedMongoDataProvider::class);
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
        $data = $this->mongoDataProvider->getFeed($user->getId(), $offset);
        $data = array_splice($data, $offset, $limit);
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
        /** @var FollowRepository $followRepository */
        $followRepository = \Yii::$container->get(FollowRepository::class);
        /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);

        $userId = $followRepository->getFollowingIds($user->getId());
        $posts = $postRepository->getByUserIds($userId, 50);
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
