<?php

namespace app\lib\amqp\Producer;

use app\lib\amqp\Job\Feed\CreateEmptyFeedJob;
use app\lib\entity\UserEntity;

/**
 * Class CreateEmptyFeedProducer
 * @package yii\queue\amqp\Producer
 */
class CreateEmptyFeedProducer extends AbstractAMQPProducer
{
    /**
     * @param UserEntity $user
     */
    public function publish(UserEntity $user): void
    {
        $job = new CreateEmptyFeedJob([
            'userId' => $user->getId()
        ]);
        $this->push($job);
    }
}