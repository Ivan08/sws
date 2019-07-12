<?php

namespace app\lib\common;

/**
 * Class BaseRedisObject
 * @package app\lib\common
 */
class BaseRedisObject
{
    /** @var \Redis $redis */
    protected $redis;

    /**
     * PostRedisDataProvider constructor.
     */
    public function __construct()
    {
        $this->redis = \Yii::$app->redis;
    }
}