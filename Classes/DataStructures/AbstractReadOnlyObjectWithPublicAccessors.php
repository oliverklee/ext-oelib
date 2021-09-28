<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\DataStructures;

/**
 * This class represents an object that allows getting its data via public methods.
 */
abstract class AbstractReadOnlyObjectWithPublicAccessors extends AbstractReadOnlyObjectWithAccessors
{
    /**
     * Gets the value stored in under the key $key, converted to a string.
     *
     * @param string $key the key of the element to retrieve, must not be empty
     *
     * @return string the string value of the given key, may be empty
     */
    public function getAsString(string $key): string
    {
        return parent::getAsString($key);
    }

    /**
     * Checks whether a non-empty string is stored under the key $key.
     *
     * @param string $key the key of the element to check, must not be empty
     *
     * @return bool TRUE if the value for the given key is non-empty,
     *                 FALSE otherwise
     */
    public function hasString(string $key): bool
    {
        return parent::hasString($key);
    }

    /**
     * Gets the value stored in under the key $key, converted to an integer.
     *
     * @param string $key the key of the element to retrieve, must not be empty
     *
     * @return int the integer value of the given key, may be positive,
     *                 negative or zero
     */
    public function getAsInteger(string $key): int
    {
        return parent::getAsInteger($key);
    }

    /**
     * Checks whether a non-zero integer is stored under the key $key.
     *
     * @param string $key the key of the element to check, must not be empty
     *
     * @return bool TRUE if the value for the given key is non-zero,
     *                 FALSE otherwise
     */
    public function hasInteger(string $key): bool
    {
        return parent::hasInteger($key);
    }

    /**
     * Gets the value stored under the provided key, converted to an array of trimmed strings.
     *
     * @param string $key the key of the element to retrieve, must not be empty
     *
     * @return array<int, string> the array value of the given key, may be empty
     */
    public function getAsTrimmedArray(string $key): array
    {
        return parent::getAsTrimmedArray($key);
    }

    /**
     * Gets the value stored under the key $key, converted to an array of
     * integers.
     *
     * @param string $key the key of the element to retrieve, must not be empty
     *
     * @return array<int, int> the array value of the given key, may be empty
     */
    public function getAsIntegerArray(string $key): array
    {
        return parent::getAsIntegerArray($key);
    }

    /**
     * Gets the value stored in under the key $key, converted to a boolean.
     *
     * @param string $key the key of the element to retrieve, must not be empty
     *
     * @return bool the boolean value of the given key
     */
    public function getAsBoolean(string $key): bool
    {
        return parent::getAsBoolean($key);
    }

    /**
     * Gets the value stored in under the key $key, converted to a float.
     *
     * @param string $key the key of the element to retrieve, must not be empty
     *
     * @return float the float value of the given key, may be positive,
     *               negative or zero
     */
    public function getAsFloat(string $key): float
    {
        return parent::getAsFloat($key);
    }

    /**
     * Checks whether a non-zero float is stored under the key $key.
     *
     * @param string $key the key of the element to check, must not be empty
     *
     * @return bool TRUE if the value for the given key is non-zero,
     *                 FALSE otherwise
     */
    public function hasFloat(string $key): bool
    {
        return parent::hasFloat($key);
    }
}
