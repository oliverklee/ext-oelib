<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

/**
 * Interface for all types of configuration.
 */
interface Configuration
{
    /**
     * Returns the name of the configuration source, e.g., "TypoScript setup" or "Flexforms".
     *
     * This name may also contain HTML.
     *
     * @return non-empty-string
     */
    public function getSourceName(): string;

    /**
     * Gets the value stored under the given key, converted to a string.
     *
     * @param non-empty-string $key
     */
    public function getAsString(string $key): string;

    /**
     * Checks whether a non-empty string is stored under the given key.
     *
     * @param non-empty-string $key
     */
    public function hasString(string $key): bool;

    /**
     * Gets the value stored under the given key, converted to an integer.
     *
     * @param non-empty-string $key
     */
    public function getAsInteger(string $key): int;

    /**
     * Checks whether a non-zero integer is stored under the given key.
     *
     * @param non-empty-string $key
     */
    public function hasInteger(string $key): bool;

    /**
     * Gets the value stored under the given key, converted to an integer.
     *
     * @param non-empty-string $key
     *
     * @return int<0, max>
     *
     * @throws \UnexpectedValueException if the value is negative
     */
    public function getAsNonNegativeInteger(string $key): int;

    /**
     * Gets the value stored under the given key, converted to an integer.
     *
     * @param non-empty-string $key
     *
     * @return positive-int
     *
     * @throws \UnexpectedValueException if the value is zero or negative
     */
    public function getAsPositiveInteger(string $key): int;

    /**
     * Gets the value stored under the given key, converted to a boolean.
     *
     * @param non-empty-string $key
     */
    public function getAsBoolean(string $key): bool;

    /**
     * Gets the value stored under the provided key, converted to an array of trimmed strings.
     *
     * @param non-empty-string $key
     *
     * @return list<non-empty-string> the array value of the given key, may be empty
     */
    public function getAsTrimmedArray(string $key): array;
}
