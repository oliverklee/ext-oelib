<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Domain\Repository\Traits;

use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;

/**
 * Trait that adds a persistAll method to a repository. The idea is that users of this repository should not need to
 * care about the persistence manager.
 *
 * This is the default implementation of the corresponding interface.
 *
 * @phpstan-require-implements RepositoryInterface
 *
 * @deprecated #1935 will be removed in version 7.0
 */
trait DirectPersist
{
    /**
     * Persists all added or updated models.
     */
    public function persistAll(): void
    {
        $this->persistenceManager->persistAll();
    }
}
