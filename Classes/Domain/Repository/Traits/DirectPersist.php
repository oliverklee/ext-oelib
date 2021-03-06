<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Domain\Repository\Traits;

/**
 * Trait that adds a persistAll method to a repository. The idea is that users of this repository should not need to
 * care about the persistence manager.
 *
 * This is the default implementation of the corresponding interface.
 */
trait DirectPersist
{
    /**
     * Persists all added or updated models.
     *
     * @return void
     */
    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }
}
