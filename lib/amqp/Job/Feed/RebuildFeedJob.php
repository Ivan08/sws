<?php


namespace app\lib\amqp\Job\Feed;

use app\lib\repository\UserRepository;
use app\lib\service\Feed\FeedService;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\queue\JobInterface;
use yii\queue\Queue;

class RebuildFeedJob extends BaseObject implements JobInterface
{
    public $userId;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     * @throws \yii\mongodb\Exception
     */
    public function execute($queue)
    {
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getById($this->userId);

        $feedService = \Yii::$container->get(FeedService::class);
        $feedService->rebuildFeed($user);
    }
}
