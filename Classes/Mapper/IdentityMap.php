<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Mapper;

use OliverKlee\Oelib\Exception\NotFoundException;
use OliverKlee\Oelib\Model\AbstractModel;

/**
 * This class represents an identity map that stores and retrieves model instances by their UIDs.
 */
class IdentityMap
{
    /**
     * @var array<positive-int, AbstractModel> the items in this map with their UIDs as keys
     */
    protected $items = [];

    /**
     * @var int<0, max> the highest used UID
     */
    private $highestUid = 0;

    /**
     * Adds a model to the identity map.
     *
     * @param AbstractModel $model the model to add, must have a UID
     */
    public function add(AbstractModel $model): void
    {
        if (!$model->hasUid()) {
            throw new \InvalidArgumentException('Add() requires a model that has a UID.', 1331488748);
        }

        $uid = $model->getUid();
        \assert($uid > 0);
        $this->items[$uid] = $model;
        $this->highestUid = max($this->highestUid, $model->getUid());
    }

    /**
     * Retrieves a model from the map by UID.
     *
     * @param positive-int $uid the UID of the model to retrieve
     *
     * @return AbstractModel the stored model with the UID $uid
     *
     * @throws NotFoundException if this map does not have a model with that particular UID
     */
    public function get(int $uid): AbstractModel
    {
        // @phpstan-ignore-next-line We're testing for a contract violation here.
        if ($uid <= 0) {
            throw new \InvalidArgumentException('$uid must be > 0.', 1331488761);
        }

        if (!isset($this->items[$uid])) {
            throw new NotFoundException(
                'This map currently does not contain a model with the UID ' .
                $uid . '.'
            );
        }

        return $this->items[$uid];
    }

    /**
     * Gets a UID that has not been used in the map before and that is greater
     * than the greatest used UID.
     *
     * @return positive-int a new UID, will be > 0
     */
    public function getNewUid(): int
    {
        return $this->highestUid + 1;
    }
}
