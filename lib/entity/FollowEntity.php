<?php

namespace app\lib\entity;

/**
 * Class FollowEntity
 * @package app\lib\entity
 */
class FollowEntity extends BaseEntity
{
    /** @var int */
    public $userFrom;
    /** @var int */
    public $userTo;
    /** @var int */
    public $createdAt;

    /**
     * @return int
     */
    public function getUserFrom(): int
    {
        return $this->userFrom;
    }

    /**
     * @param int $userFrom
     */
    public function setUserFrom(int $userFrom): void
    {
        $this->userFrom = $userFrom;
    }

    /**
     * @return int
     */
    public function getUserTo(): int
    {
        return $this->userTo;
    }

    /**
     * @param int $userTo
     */
    public function setUserTo(int $userTo): void
    {
        $this->userTo = $userTo;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}