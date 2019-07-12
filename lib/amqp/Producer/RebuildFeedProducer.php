<?php

namespace app\lib\amqp\Producer;

use app\lib\amqp\Job\Feed\RebuildFeedJob;
use app\lib\entity\UserEntity;

/**
 * Class RebuildFeedProducer
 * @package yii\queue\amqp\Producer
 */
class RebuildFeedProducer extends AbstractAMQPProducer
{
    /**
     * @param UserEntity $user
     */
    public function publish(UserEntity $user): void
    {
        $job = new RebuildFeedJob([
            'userId' => $user->getId()
        ]);
        $this->push($job);
    }
}