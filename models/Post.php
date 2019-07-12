<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Post
 * @package app\models
 */
class Post extends ActiveRecord
{
    public const
        SCENARIO_CREATE = 'create',
        SCENARIO_UPDATE = 'update';

    public static function tableName()
    {
        return '{{post}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['message', 'user_id', 'username', 'created_at'],
            self::SCENARIO_UPDATE => ['message'],
        ];
    }
}
