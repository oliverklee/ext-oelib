<?php

namespace OliverKlee\Oelib\Tests\Unit\Domain\Repository\Fixtures;

use OliverKlee\Oelib\Domain\Repository\Traits\ReadOnlyTrait;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Testing repository for the ReadOnly trait.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class ReadOnlyRepository extends Repository
{
    use ReadOnlyTrait;
}
