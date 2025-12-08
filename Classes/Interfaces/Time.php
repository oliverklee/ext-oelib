<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

/**
 * This class provides time-related constants.
 */
interface Time
{
    public const SECONDS_PER_MINUTE = 60;
    public const SECONDS_PER_HOUR = 3600;
    public const SECONDS_PER_DAY = 86400;
    public const SECONDS_PER_WEEK = 604800;

    /**
     * the number of seconds per year (only for non-leap years), use with caution
     */
    public const SECONDS_PER_YEAR = self::SECONDS_PER_DAY * 365;
}
