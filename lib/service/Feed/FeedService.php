<?php

namespace app\lib\service\Feed;

use app\lib\entity\PostEntity;
use app\lib\entity\UserEntity;
use app\lib\service\Feed\Manager\FeedManager;

/**
 * Class FeedService
 * @package app\Feed
 */
class FeedService
{
    private const FEED_LIMIT = 10;

    /**
     * @param UserEntity $user
     * @param int $page
     * @return PostEntity[]
     */
    public function getFeed(UserEntity $user, int $page): array
    {
        $offset = ($page - 1) * self::FEED_LIMIT;
        $manager = $this->getManager();
        return $manager->getFeed($user, $offset, self::FEED_LIMIT);
    }

    /**
     * @param UserEntity $user
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \yii\mongodb\Exception
     */
    public function rebuildFeed(UserEntity $user): bool
    {
        $manager = $this->getManager();
        $manager->rebuildFeed($user);

        return true;
    }

    /**
     * @param UserEntity $user
     * @param PostEntity $post
     */
    public function pushToFeedFollowers(UserEntity $user, PostEntity $post): void
    {
        $manager = $this->getManager();
        $manager->pushToFeedFollowers($user, $post);
    }

    /**
     * @return FeedManager
     */
    private function getManager(): FeedManager
    {
        return \Yii::$container->get(FeedManager::class);
    }

    /**
     * @param UserEntity $user
     * @return bool
     * @throws \yii\mongodb\Exception
     */
    public function createEmptyFeed(UserEntity $user): bool
    {
        $manager = $this->getManager();
        $manager->createEmptyFeed($user);

        return true;
    }
}
