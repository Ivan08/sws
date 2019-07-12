<?php

namespace app\lib\service\Post;

use app\lib\amqp\Producer\CreatePostProducer;
use app\lib\entity\PostEntity;
use app\lib\entity\UserEntity;
use app\lib\service\Post\Manager\PostManager;

/**
 * Class PostService
 * @package app\lib\service\Post
 */
class PostService
{
    private const POST_LIMIT = 10;

    /**
     * @param UserEntity $user
     * @param array $postData
     * @return PostEntity
     * @throws \Throwable
     */
    public function add(UserEntity $user, array $postData): PostEntity
    {
        $manager = $this->getManager();
        $post = $manager->insert($user, $postData);

        $producer = new CreatePostProducer();
        $producer->publish($user, $post);

        return $post;
    }

    /**
     * @return PostManager
     */
    private function getManager(): PostManager
    {
        return \Yii::$container->get(PostManager::class);
    }

    /**
     * @param PostEntity $post
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(PostEntity $post): void
    {
        $manager = $this->getManager();
        $manager->delete($post);
    }

    /**
     * @param UserEntity $user
     * @param int $page
     * @return PostEntity[]
     */
    public function getPosts(UserEntity $user, int $page = 0): array
    {
        $offset = ($page - 1) * self::POST_LIMIT;
        $manager = $this->getManager();
        return $manager->getForUser($user, self::POST_LIMIT, $offset);
    }
}
