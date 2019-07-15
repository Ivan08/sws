<?php

namespace app\lib\repository;

use app\lib\entity\PostEntity;
use app\models\Post;

/**
 * Class PostRepository
 * @package app\lib\repository
 */
class PostRepository
{
    /**
     * @param int $id
     * @return PostEntity|null
     */
    public function getById(int $id): ?PostEntity
    {
        $post = Post::find()
            ->where(['id' => $id])
            ->one();

        if (!$post) {
            return null;
        }
        return $this->buildFromModel($post);
    }

    /**
     * @param PostEntity $post
     * @return PostEntity
     * @throws \Throwable
     */
    public function insert(PostEntity $post): PostEntity
    {
        $postModel = new Post();
        $postModel->setScenario(Post::SCENARIO_CREATE);
        $postModel->attributes = $post->toArray();
        $postModel->created_at = $post->getCreatedAt();

        $postModel->insert();

        return $this->buildFromModel($postModel);
    }

    /**
     * @param PostEntity $post
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(PostEntity $post): void
    {
        $postModel = Post::find()
            ->where(['id' => $post->getId()])
            ->one();
        if ($postModel) {
            $postModel->delete();
        }
    }

    /**
     * @param array $userIds
     * @param int $limit
     * @param int $offset
     * @return PostEntity[]
     */
    public function getByUserIds(array $userIds, int $limit, int $offset = 0): array
    {
        $posts = Post::find()
            ->where(['user_id' => $userIds])
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id')
            ->all();
        $result = [];
        foreach ($posts as $post) {
            $result[] = $this->buildFromModel($post);
        }
        return $result;
    }

    /**
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return PostEntity[]
     */
    public function getByUserId(int $userId, int $limit, int $offset = 0): array
    {
        $posts = Post::find()
            ->where(['user_id' => $userId])
            ->limit($limit)
            ->offset($offset)
            ->orderBy('id DESC')
            ->all();
        $result = [];
        foreach ($posts as $post) {
            $result[] = $this->buildFromModel($post);
        }
        return $result;
    }

    /**
     * @param Post $post
     * @return PostEntity
     */
    private function buildFromModel(Post $post): PostEntity
    {
        $postEntity = new PostEntity();
        $postEntity->fromArray($post->toArray());
        $postEntity->setCreatedAt($post->created_at);

        return $postEntity;
    }
}
