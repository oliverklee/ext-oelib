<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Domain\Fixtures;

use OliverKlee\Oelib\Domain\Model\Traits\CachedAssociationCount;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Testing model for 1:n associations.
 */
class ParentModel extends AbstractEntity
{
    use CachedAssociationCount;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\OliverKlee\Oelib\Tests\Unit\Domain\Fixtures\ParentModel>
     * @Lazy
     * @lazy
     */
    protected $children = null;

    public function __construct()
    {
        $this->children = new ObjectStorage();
    }

    /**
     * @return ObjectStorage
     */
    public function getChildren(): ObjectStorage
    {
        return $this->children;
    }

    /**
     * @param ObjectStorage $children
     *
     * @return void
     */
    public function setChildren(ObjectStorage $children)
    {
        $this->children = $children;
    }

    /**
     * @return int
     */
    public function getChildrenCount(): int
    {
        return $this->getCachedRelationCount('children');
    }
}
