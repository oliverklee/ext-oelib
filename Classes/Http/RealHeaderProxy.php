<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Http;

use OliverKlee\Oelib\Http\Interfaces\HeaderProxy;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * This class sends HTTP headers.
 *
 * Regarding the Strategy pattern, `addHeader()` represents one concrete behavior.
 */
class RealHeaderProxy implements HeaderProxy
{
    /**
     * Adds a header.
     *
     * @param non-empty-string $header HTTP header to send
     */
    public function addHeader(string $header): void
    {
        HttpUtility::setResponseCode($header);
    }
}
