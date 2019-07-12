<?php

namespace app\lib\form;

use yii\base\Model;

class PostForm extends Model
{
    public $message;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            ['message', 'string'],
        ];
    }
}
