<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Domain\Fixtures;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Testing model for 1:n associations.
 */
final class ParentModel extends AbstractEntity
{
    /**
     * @var ObjectStorage<ParentModel>
     * @Extbase\ORM\Lazy
     */
    protected $children;

    public function __construct()
    {
        /** @var ObjectStorage<ParentModel> $children */
        $children = new ObjectStorage();
        $this->children = $children;
    }

    /**
     * @return ObjectStorage<ParentModel>
     */
    public function getChildren(): ObjectStorage
    {
        return $this->children;
    }

    /**
     * @param ObjectStorage<ParentModel> $children
     */
    public function setChildren(ObjectStorage $children): void
    {
        $this->children = $children;
    }
}
