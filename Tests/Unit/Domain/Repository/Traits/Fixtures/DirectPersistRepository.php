<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Domain\Repository\Traits\Fixtures;

use OliverKlee\Oelib\Domain\Repository\Interfaces\DirectPersist;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Testing repository for the `DirectPersist` trait.
 *
 * @extends Repository<EmptyModel>
 */
final class DirectPersistRepository extends Repository implements DirectPersist
{
    use \OliverKlee\Oelib\Domain\Repository\Traits\DirectPersist;
}
