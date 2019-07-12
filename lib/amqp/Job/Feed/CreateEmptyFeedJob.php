<?php

namespace app\lib\amqp\Job\Feed;

use app\lib\repository\UserRepository;
use app\lib\service\Feed\FeedService;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class CreateEmptyFeedJob
 * @package app\lib\amqp\Job\Feed
 */
class CreateEmptyFeedJob extends BaseObject implements JobInterface
{
    public $userId;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws \yii\mongodb\Exception
     */
    public function execute($queue)
    {
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getById($this->userId);

        $feedService = \Yii::$container->get(FeedService::class);
        $feedService->createEmptyFeed($user);
    }
}
