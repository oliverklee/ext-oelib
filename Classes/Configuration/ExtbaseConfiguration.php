<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Configuration;

use OliverKlee\Oelib\DataStructures\AbstractReadOnlyObjectWithPublicAccessors;
use OliverKlee\Oelib\Interfaces\Configuration;

/**
 * Configuration that is intended to be used with Extbase.
 */
final class ExtbaseConfiguration extends AbstractReadOnlyObjectWithPublicAccessors implements Configuration
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Gets the value of the data item for the given key.
     *
     * @param non-empty-string $key
     *
     * @return mixed the data for the given key, will be null if the key has not been set yet
     */
    protected function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Returns the name of the configuration source, e.g., "TypoScript setup" or "Flexforms".
     *
     * This name may also contain HTML.
     *
     * @return non-empty-string
     */
    public function getSourceName(): string
    {
        return 'in the plugin Flexforms or in your TypoScript template';
    }
}
