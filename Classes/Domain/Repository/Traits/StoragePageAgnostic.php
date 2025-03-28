<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Domain\Repository\Traits;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;

/**
 * This trait for repositories makes the repository ignore the storage page setting when fetching models.
 *
 * @phpstan-require-implements RepositoryInterface
 *
 * @deprecated #2000 will be removed in version 7.0
 */
trait StoragePageAgnostic
{
    public function initializeObject(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
