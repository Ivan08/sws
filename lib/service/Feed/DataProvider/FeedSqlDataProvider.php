<?php

namespace app\lib\service\Feed\DataProvider;

use yii\db\Query;

/**
 * Class FeedSqlDataProvider
 * @package app\lib\service\Feed\DataProvider
 */
class FeedSqlDataProvider
{
    public function getFeedForUser(int $userId, int $limit, int $offset)
    {
        $data = (new Query())
            ->from('post')
            ->innerJoin('follow', '`post`.`user_id` = `follow`.`user_to`') //ToDo: Add criteria
            ->where(['`follow`.`user_from`' => $userId])
            ->orderBy('`post`.`id` DESC')
            ->limit($limit)
            ->offset($offset)
            ->all();

        return $data;
    }
}
