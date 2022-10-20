<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Domain\Fixtures;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * Testing model for 1azily-loaded properties.
 */
final class LazyLoadingModel extends AbstractEntity
{
    /**
     * @var EmptyModel
     * @phpstan-var EmptyModel|LazyLoadingProxy
     */
    protected $lazyProperty;

    public function getLazyProperty(): EmptyModel
    {
        // @phpstan-ignore-next-line This variable property access is okay.
        $propertyValue = $this->lazyProperty;
        if ($propertyValue instanceof LazyLoadingProxy) {
            // @phpstan-ignore-next-line This variable property access is okay.
            $this->lazyProperty = $propertyValue->_loadRealInstance();
        }
        
        /** @var EmptyModel $property */
        $property = $this->lazyProperty;

        return $property;
    }

    /**
     * @param EmptyModel|LazyLoadingProxy $property
     */
    public function setLazyProperty($property): void
    {
        $this->lazyProperty = $property;
    }
}
