<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

/**
 * This class provides time-related constants.
 */
interface Time
{
    /**
     * the number of seconds per minute
     *
     * @var int
     */
    public const SECONDS_PER_MINUTE = 60;

    /**
     * the number of seconds per hour
     *
     * @var int
     */
    public const SECONDS_PER_HOUR = 3600;

    /**
     * the number of seconds per day
     *
     * @var int
     */
    public const SECONDS_PER_DAY = 86400;

    /**
     * the number of seconds per week
     *
     * @var int
     */
    public const SECONDS_PER_WEEK = 604800;

    /**
     * the number of seconds per year (only for non-leap years), use with caution
     *
     * @var int
     */
    public const SECONDS_PER_YEAR = self::SECONDS_PER_DAY * 365;
}
