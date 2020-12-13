<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Domain\Repository;

use TYPO3\CMS\Core\SingletonInterface;

/**
 * @author Stefano Kowalke <info@arroba-it.de>
 */
class PageRepository implements SingletonInterface
{
    public static function findWithinSingleParentPage($concatenatedStartPages, int $recursionDepth = 0): string
    {
        if ($recursionDepth < 0) {
            throw new \InvalidArgumentException('$recursionDepth must be >= 0.', 1331319974);
        }

        $trimmedStartPages = \trim((string)$concatenatedStartPages);
        if ($recursionDepth === 0) {
            return $trimmedStartPages;
        }

        if ($trimmedStartPages === '') {
            return '';
        }
    }

    public static function findWithinParentPages()
    {
    }
}
