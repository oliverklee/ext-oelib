<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\DataStructures;

/**
 * This class represents an object that allows getting its data via public methods.
 */
abstract class AbstractReadOnlyObjectWithPublicAccessors extends AbstractReadOnlyObjectWithAccessors
{
    /**
     * Gets the value stored under the given key, converted to a string.
     *
     * @param non-empty-string $key
     */
    public function getAsString(string $key): string
    {
        return parent::getAsString($key);
    }

    /**
     * Checks whether a non-empty string is stored under the given key.
     *
     * @param non-empty-string $key
     */
    public function hasString(string $key): bool
    {
        return parent::hasString($key);
    }

    /**
     * Gets the value stored under the given key, converted to an integer.
     *
     * @param non-empty-string $key
     */
    public function getAsInteger(string $key): int
    {
        return parent::getAsInteger($key);
    }

    /**
     * Checks whether a non-zero integer is stored under the given key.
     *
     * @param non-empty-string $key
     */
    public function hasInteger(string $key): bool
    {
        return parent::hasInteger($key);
    }

    /**
     * Gets the value stored under the provided key, converted to an array of trimmed strings.
     *
     * @param non-empty-string $key
     *
     * @return list<non-empty-string> the array value of the given key, may be empty
     */
    public function getAsTrimmedArray(string $key): array
    {
        return parent::getAsTrimmedArray($key);
    }

    /**
     * Gets the value stored under the given key, converted to an array of integers.
     *
     * @param non-empty-string $key
     *
     * @return list<int> the array value of the given key, may be empty
     */
    public function getAsIntegerArray(string $key): array
    {
        return parent::getAsIntegerArray($key);
    }

    /**
     * Gets the value stored under the given key, converted to a boolean.
     *
     * @param non-empty-string $key
     */
    public function getAsBoolean(string $key): bool
    {
        return parent::getAsBoolean($key);
    }

    /**
     * Gets the value stored under the given key, converted to a float.
     *
     * @param non-empty-string $key
     */
    public function getAsFloat(string $key): float
    {
        return parent::getAsFloat($key);
    }

    /**
     * Checks whether a non-zero float is stored under the given key.
     *
     * @param non-empty-string $key
     */
    public function hasFloat(string $key): bool
    {
        return parent::hasFloat($key);
    }
}
