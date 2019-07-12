<?php

namespace app\lib\common;

use yii\mongodb\Connection;

/**
 * Class BaseMongoObject
 * @package app\lib\common
 */
class BaseMongoObject
{
    /** @var Connection */
    protected $mongo;

    /**
     * BaseMongoObject constructor.
     */
    public function __construct()
    {
        $this->mongo = \Yii::$app->mongodb;
    }
}