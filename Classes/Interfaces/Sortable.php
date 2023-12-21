<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

/**
 * This interface represents an object that can be sorted.
 *
 * @deprecated #1501 will be removed on oelib 6.0.0
 */
interface Sortable
{
    /**
     * Returns the sorting value for this object.
     *
     * This is the sorting as used in the back end.
     *
     * @return int<0, max> the sorting value of this object
     */
    public function getSorting(): int;

    /**
     * Sets the sorting value for this object.
     *
     * This is the sorting as used in the back end.
     *
     * @param int<0, max> $sorting the sorting value of this object
     */
    public function setSorting(int $sorting): void;
}
