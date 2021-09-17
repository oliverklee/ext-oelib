<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

use OliverKlee\Oelib\Mapper\AbstractDataMapper;
use OliverKlee\Oelib\Model\AbstractModel;

/**
 * This interface represents a manager for logins, providing access to the logged-in user.
 *
 * @template T
 */
interface LoginManager
{
    /**
     * Returns an instance of this class.
     *
     * @return T the current Singleton instance
     */
    public static function getInstance();

    /**
     * Purges the current instance so that getInstance will create a new instance.
     */
    public static function purgeInstance(): void;

    /**
     * Checks whether a user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * Gets the currently logged-in user.
     *
     * @param class-string<AbstractDataMapper> $mapperName
     *        the name of the mapper to use for getting the user model, must not be empty
     *
     * @return AbstractModel|null the logged-in user, will be null if no user is logged in
     */
    public function getLoggedInUser(string $mapperName);
}
