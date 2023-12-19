<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Testing;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Http\Uri;

/**
 * Dummy request for tests.
 *
 * @internal
 */
final class DummyRequest implements RequestInterface
{
    public function getProtocolVersion(): string
    {
        return '1.1';
    }

    public function withProtocolVersion(string $version): self
    {
        return clone $this;
    }

    /**
     * @return array<string, array<string>>
     */
    public function getHeaders(): array
    {
        return [];
    }

    public function hasHeader(string $name): bool
    {
        return false;
    }

    /**
     * @return array<string>
     */
    public function getHeader(string $name): array
    {
        return [];
    }

    public function getHeaderLine(string $name): string
    {
        return '';
    }

    /**
     * @param string|array<string> $value
     */
    public function withHeader(string $name, $value): self
    {
        return clone $this;
    }

    /**
     * @param string|array<string> $value
     */
    public function withAddedHeader(string $name, $value): self
    {
        return clone $this;
    }

    public function withoutHeader(string $name): self
    {
        return clone $this;
    }

    public function getBody(): StreamInterface
    {
        return new Stream('php://temp', 'r+');
    }

    public function withBody(StreamInterface $body): self
    {
        return clone $this;
    }

    public function getRequestTarget(): string
    {
        return '/';
    }

    public function withRequestTarget(string $requestTarget): self
    {
        return clone $this;
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function withMethod(string $method): self
    {
        return clone $this;
    }

    public function getUri(): UriInterface
    {
        return new Uri('');
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): self
    {
        return clone $this;
    }
}
