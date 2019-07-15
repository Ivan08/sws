<?php

namespace app\lib\service\Post\DataProvider;

use app\lib\common\BaseRedisObject;

/**
 * Class PostRedisDataProvider
 * @package app\lib\service\Post\DataProvider
 */
class PostRedisDataProvider extends BaseRedisObject
{
    private const TTL = 60;
    /**
     * @param int $userId
     */
    public function remove(int $userId): void
    {
        $this->redis->del($this->getKey($userId));
    }

    /**
     * @param int $userId
     * @return array|null
     */
    public function get(int $userId): ?array
    {
        $data = $this->redis->get($this->getKey($userId));
        if ($data) {
            return unserialize($data);
        }
        return null;
    }

    /**
     * @param int $userId
     * @param array $data
     */
    public function set(int $userId, array $data): void
    {
        $this->redis->set($this->getKey($userId), serialize($data), self::TTL);
    }

    /**
     * @param int $userId
     * @return string
     */
    private function getKey(int $userId): string
    {
        return sprintf('posts:%s', $userId);
    }
}
