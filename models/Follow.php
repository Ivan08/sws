<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Follow
 * @package app\models
 */
class Follow extends ActiveRecord
{
    public const
        SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return '{{follow}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['user_from', 'user_to', 'created_at'],
        ];
    }

    public static function primaryKey(){
        return ['user_from', 'user_to'];
    }
}
