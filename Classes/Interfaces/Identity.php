<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

/**
 * This interface represents something that has an identity, i.e., a UID.
 *
 * @internal
 */
interface Identity
{
    /**
     * Gets this object's UID.
     *
     * @return int<0, max> this object's UID, will be zero if this object does not have a UID yet
     */
    public function getUid(): int;

    /**
     * Checks whether this object has a UID.
     *
     * @return bool TRUE if this object has a non-zero UID, FALSE otherwise
     */
    public function hasUid(): bool;

    /**
     * Sets this object's UID.
     *
     * This function may only be called on objects that do not have a UID yet.
     *
     * @param positive-int $uid the UID to set
     */
    public function setUid(int $uid): void;
}
