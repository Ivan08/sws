<?php

namespace app\lib\amqp\Job\Post;

use app\lib\repository\PostRepository;
use app\lib\repository\UserRepository;
use app\lib\service\Feed\FeedService;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class PushFollowersFeedJob
 * @package lib\amqp\Job\Post
 */
class PushFollowersFeedJob extends BaseObject implements JobInterface
{
    public $userId;
    public $postId;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getById($this->userId);

        /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);
        $post = $postRepository->getById($this->postId);

        $service = \Yii::$container->get(FeedService::class);
        $service->pushToFeedFollowers($user, $post);
    }
}
