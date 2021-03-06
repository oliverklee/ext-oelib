<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Configuration;

use OliverKlee\Oelib\Interfaces\Configuration as ConfigurationInterface;

/**
 * This configuration provides fallback functionality to a secondary configuration
 * if the requested data in the primary configuration is empty.
 */
class FallbackConfiguration implements ConfigurationInterface
{
    /**
     * @var ConfigurationInterface
     */
    private $primary;

    /**
     * @var ConfigurationInterface
     */
    private $secondary;

    public function __construct(ConfigurationInterface $primary, ConfigurationInterface $secondary)
    {
        $this->primary = $primary;
        $this->secondary = $secondary;
    }

    public function getAsString(string $key): string
    {
        return $this->primary->hasString($key)
            ? $this->primary->getAsString($key) : $this->secondary->getAsString($key);
    }

    public function hasString(string $key): bool
    {
        return $this->primary->hasString($key) || $this->secondary->hasString($key);
    }

    public function getAsInteger(string $key): int
    {
        return $this->primary->hasInteger($key)
            ? $this->primary->getAsInteger($key) : $this->secondary->getAsInteger($key);
    }

    public function hasInteger(string $key): bool
    {
        return $this->primary->hasInteger($key) || $this->secondary->hasInteger($key);
    }

    public function getAsBoolean(string $key): bool
    {
        return $this->primary->getAsBoolean($key) || $this->secondary->getAsBoolean($key);
    }
}
