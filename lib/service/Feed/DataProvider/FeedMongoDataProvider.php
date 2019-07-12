<?php

namespace app\lib\service\Feed\DataProvider;

use app\lib\common\BaseMongoObject;

class FeedMongoDataProvider extends BaseMongoObject
{
    /**
     * @param int $userId
     * @return array
     */
    public function getFeed(int $userId): array
    {
        $collection = $this->mongo->getCollection('feed');
        $data = $collection->find(['user_id' => $userId], ['posts' => true])->toArray();
        if ($data) {
            return $data[0]['posts'];
        }
        return [];
    }

    /**
     * @param int $userId
     * @param array $data
     * @throws \yii\mongodb\Exception
     */
    public function setFeed(int $userId, array $data): void
    {
        /** @var  $collection */
        $collection = $this->mongo->getCollection('feed');
        $collection->update(
            [
                'user_id' => $userId
            ],
            [
                'posts' => $data
            ],
            [
                'upsert' => true
            ]
        );
    }

    /**
     * @param array $userIds
     * @param array $data
     * @throws \MongoCursorException
     */
    public function pushPostToFeeds(array $userIds, array $data): void
    {
        /** @var  \MongoCollection $collection */
        $collection = $this->mongo->getCollection('feed');
        $result = $collection->update(
            [
                'user_id' => ['$in' => $userIds]
            ],
            [
                '$push' => [
                    'posts' => [
                        '$each' => [$data],
                        '$position' => 0,
                        '$slice' => 50
                    ]
                ]
            ]
        );
    }
}
