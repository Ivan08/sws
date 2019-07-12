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


//    public $id;
//    public $message;
//    public $user_id;
//    public $username;

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

    public function relations()
    {
        return [
            'user'=> [self::H, 'Post', 'author_id'],
        ];
    }
}
