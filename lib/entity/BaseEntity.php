<?php

namespace app\lib\entity;

/**
 * Interface BaseEntityInterface
 * @package app\lib\entity
 */
abstract class BaseEntity
{
    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param array $data
     */
    public function fromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}