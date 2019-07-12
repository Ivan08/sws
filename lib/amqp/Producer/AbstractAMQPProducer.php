<?php

namespace app\lib\amqp\Producer;

use yii\queue\JobInterface;

/**
 * Class AbstractAMQPProducer
 * @package yii\queue\amqp\Producer
 */
abstract class AbstractAMQPProducer
{
    /**
     * @param JobInterface $job
     * @return mixed
     */
    protected function push(JobInterface $job)
    {
        return \Yii::$app->queue->push($job);
    }
}