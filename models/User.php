<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class User
 * @package app\models
 */
class User extends ActiveRecord
{
    public const SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return '{{user}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['username', 'password', 'auth_key', 'access_token'],
        ];
    }
}
