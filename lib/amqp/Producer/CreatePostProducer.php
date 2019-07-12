<?php

namespace app\lib\amqp\Producer;

use app\lib\amqp\Job\Post\PushFollowersFeedJob;
use app\lib\entity\PostEntity;
use app\lib\entity\UserEntity;

class CreatePostProducer extends AbstractAMQPProducer
{
    /**
     * @param UserEntity $user
     * @param PostEntity $post
     */
    public function publish(UserEntity $user, PostEntity $post): void
    {
        $job = new PushFollowersFeedJob([
            'userId' => $user->getId(),
            'postId' => $post->getId()
        ]);
        $this->push($job);
    }
}
