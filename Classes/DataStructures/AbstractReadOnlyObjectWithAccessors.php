<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\DataStructures;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class represents an object that allows getting its data,
 * but only via protected methods so that encapsulation is retained.
 */
abstract class AbstractReadOnlyObjectWithAccessors
{
    /**
     * Gets the value of the data item for the key $key.
     *
     * @param non-empty-string $key
     *
     * @return mixed the data for the key $key, will be null or an empty string if the key has not been set yet
     */
    abstract protected function get(string $key);

    /**
     * Checks that `$key` is not empty.
     *
     * @param string $key the key to check
     *
     * @throws \InvalidArgumentException if $key is empty
     */
    protected function checkForNonEmptyKey(string $key): void
    {
        if ($key === '') {
            throw new \InvalidArgumentException('$key must not be empty.', 1_331_488_963);
        }
    }

    /**
     * Gets the value stored in under the key $key, converted to a string.
     *
     * @param non-empty-string $key
     *
     * @return string the string value of the given key, may be empty
     */
    protected function getAsString(string $key): string
    {
        $this->checkForNonEmptyKey($key);

        return trim((string)$this->get($key));
    }

    /**
     * Checks whether a non-empty string is stored under the key $key.
     *
     * @param non-empty-string $key
     *
     * @return bool true if the value for the given key is non-empty, false otherwise
     */
    protected function hasString(string $key): bool
    {
        return $this->getAsString($key) !== '';
    }

    /**
     * Gets the value stored in under the key $key, converted to an integer.
     *
     * @param non-empty-string $key
     *
     * @return int the integer value of the given key, may be positive, negative or zero
     */
    protected function getAsInteger(string $key): int
    {
        $this->checkForNonEmptyKey($key);

        return (int)$this->get($key);
    }

    /**
     * Checks whether a non-zero integer is stored under the key $key.
     *
     * @param non-empty-string $key
     *
     * @return bool true if the value for the given key is non-zero, false otherwise
     */
    protected function hasInteger(string $key): bool
    {
        return $this->getAsInteger($key) !== 0;
    }

    /**
     * Gets the value stored under the provided key, converted to an array of trimmed strings.
     *
     * @param non-empty-string $key
     *
     * @return list<non-empty-string> the array value of the given key, may be empty
     */
    protected function getAsTrimmedArray(string $key): array
    {
        return GeneralUtility::trimExplode(',', $this->getAsString($key), true);
    }

    /**
     * Gets the value stored under the key $key, converted to an array of integers.
     *
     * @param non-empty-string $key
     *
     * @return list<int> the array value of the given key, may be empty
     */
    protected function getAsIntegerArray(string $key): array
    {
        $stringValue = $this->getAsString($key);

        if ($stringValue === '') {
            return [];
        }

        /** @var list<int> $result */
        $result = GeneralUtility::intExplode(',', $stringValue, true);

        return $result;
    }

    /**
     * Gets the value stored in under the key $key, converted to a boolean.
     *
     * @param non-empty-string $key
     *
     * @return bool the boolean value of the given key
     */
    protected function getAsBoolean(string $key): bool
    {
        $this->checkForNonEmptyKey($key);

        return (bool)$this->get($key);
    }

    /**
     * Gets the value stored in under the key $key, converted to a float.
     *
     * @param non-empty-string $key
     *
     * @return float the float value of the given key, may be positive, negative or zero
     */
    protected function getAsFloat(string $key): float
    {
        $this->checkForNonEmptyKey($key);

        return (float)$this->get($key);
    }

    /**
     * Checks whether a non-zero float is stored under the key $key.
     *
     * @param non-empty-string $key
     *
     * @return bool true if the value for the given key is non-zero, false otherwise
     */
    protected function hasFloat(string $key): bool
    {
        return $this->getAsFloat($key) !== 0.00;
    }
}
