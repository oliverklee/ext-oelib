<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Http;

use OliverKlee\Oelib\Http\Interfaces\HeaderProxy;

/**
 * This class stores HTTP header which were meant to be sent instead of really
 * sending them and provides various functions to get them for testing purposes.
 *
 * Regarding the Strategy pattern, `addHeader()` represents one concrete behavior.
 *
 * @deprecated #1526 will be removed in oelib 6.0.0
 */
class HeaderCollector implements HeaderProxy
{
    /**
     * headers which were meant to be sent
     *
     * @var list<string>
     */
    private array $headers = [];

    /**
     * Stores an HTTP header which was meant to be sent.
     *
     * @param non-empty-string $header HTTP header to send
     */
    public function addHeader(string $header): void
    {
        $this->headers[] = $header;
    }

    /**
     * Returns the last header or an empty string if there are none.
     *
     * @return string last header, will be empty if there are none
     */
    public function getLastAddedHeader(): string
    {
        if ($this->headers === []) {
            return '';
        }

        return end($this->headers);
    }

    /**
     * Returns all headers added with this instance or an empty array if there is none.
     *
     * @return list<string> all added headers, will be empty if there is none
     */
    public function getAllAddedHeaders(): array
    {
        return $this->headers;
    }
}
