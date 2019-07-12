<?php

namespace app\lib\service\Post\Manager;

use app\lib\entity\PostEntity;
use app\lib\entity\UserEntity;
use app\lib\repository\PostRepository;
use app\lib\service\Post\DataProvider\PostRedisDataProvider;

/**
 * Class PostManager
 * @package app\lib\service\Post\Manager
 */
class PostManager
{
    private const REDIS_CACHE_POSTS_LIMIT = 50;

    /** @var PostRedisDataProvider $redisDataProvider */
    private $redisDataProvider;

    /**
     * PostManager constructor.
     */
    public function __construct()
    {
        $this->redisDataProvider = \Yii::$container->get(PostRedisDataProvider::class);
    }

    /**
     * @param UserEntity $user
     * @param array $postData
     * @return PostEntity
     * @throws \Throwable
     */
    public function insert(UserEntity $user, array $postData): PostEntity
    {
        $post = $this->buildFromArray($postData);
        $post->setUserId($user->getId());
        $post->setUsername($user->getUsername());
        $post->setCreatedAt(time());

        /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);
        $post = $postRepository->insert($post);

        $this->redisDataProvider->remove($user->getId());
        return $post;
    }

    /**
     * @param PostEntity $post
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(PostEntity $post): void
    {
        /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);
        $postRepository->delete($post);

        $this->redisDataProvider->remove($post->getUserId());
    }

    /**
     * @param UserEntity $user
     * @param int $limit
     * @param int $offset
     * @return PostEntity[]
     */
    public function getForUser(UserEntity $user, int $limit, int $offset): array
    {
        if ($offset < self::REDIS_CACHE_POSTS_LIMIT) {
            $posts = $this->redisDataProvider->get($user->getId());
            if ($posts === null) {
                /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);
                $posts = $postRepository->getByUserId($user->getId(), self::REDIS_CACHE_POSTS_LIMIT);
                $this->redisDataProvider->set($user->getId(), $posts);
            }

            return array_splice($posts, $offset, $limit);
        }
        /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);
        return $postRepository->getByUserId($user->getId(), $limit, $offset);
    }

    /**
     * @param array $postData
     * @return PostEntity
     */
    private function buildFromArray(array $postData): PostEntity
    {
        $post = new PostEntity();
        $post->fromArray($postData);
        return $post;
    }
}
