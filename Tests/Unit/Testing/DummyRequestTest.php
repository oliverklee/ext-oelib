<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Testing;

use OliverKlee\Oelib\Testing\DummyRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \OliverKlee\Oelib\Testing\DummyRequest
 */
final class DummyRequestTest extends UnitTestCase
{
    /**
     * @var DummyRequest
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new DummyRequest();
    }

    /**
     * @test
     */
    public function implementsRequestInterface(): void
    {
        self::assertInstanceOf(RequestInterface::class, $this->subject);
    }

    /**
     * @test
     */
    public function getProtocolVersionReturns11(): void
    {
        self::assertSame('1.1', $this->subject->getProtocolVersion());
    }

    /**
     * @test
     */
    public function withProtocolVersionReturnsClone(): void
    {
        $clone = $this->subject->withProtocolVersion('1.0');

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function getHeadersReturnsEmptyArray(): void
    {
        self::assertSame([], $this->subject->getHeaders());
    }

    /**
     * @test
     */
    public function hasHeaderReturnsFalse(): void
    {
        self::assertFalse($this->subject->hasHeader('foo'));
    }

    /**
     * @test
     */
    public function getHeaderReturnsEmptyArray(): void
    {
        self::assertSame([], $this->subject->getHeader('foo'));
    }

    /**
     * @test
     */
    public function getHeaderLineReturnsEmptyString(): void
    {
        self::assertSame('', $this->subject->getHeaderLine('foo'));
    }

    /**
     * @test
     */
    public function withHeaderReturnsClone(): void
    {
        $clone = $this->subject->withHeader('foo', 'bar');

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function withAddedHeaderReturnsClone(): void
    {
        $clone = $this->subject->withAddedHeader('foo', 'bar');

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function withoutHeaderReturnsClone(): void
    {
        $clone = $this->subject->withoutHeader('foo');

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function getBodyReturnsEmptyStream(): void
    {
        $stream = $this->subject->getBody();

        self::assertInstanceOf(StreamInterface::class, $stream);
        self::assertSame(0, $stream->getSize());
        self::assertSame('', $stream->getContents());
    }

    /**
     * @test
     */
    public function withBodyReturnsClone(): void
    {
        $stream = $this->createStub(StreamInterface::class);

        $clone = $this->subject->withBody($stream);

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function getRequestTargetReturnsRootPath(): void
    {
        self::assertSame('/', $this->subject->getRequestTarget());
    }

    /**
     * @test
     */
    public function withRequestTargetReturnsClone(): void
    {
        $clone = $this->subject->withRequestTarget('/foo');

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function getMethodReturnsGet(): void
    {
        self::assertSame('GET', $this->subject->getMethod());
    }

    /**
     * @test
     */
    public function withMethodReturnsClone(): void
    {
        $clone = $this->subject->withMethod('POST');

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }

    /**
     * @test
     */
    public function getUriReturnsUri(): void
    {
        $uri = $this->subject->getUri();

        self::assertInstanceOf(UriInterface::class, $uri);
    }

    /**
     * @test
     */
    public function withUriReturnsClone(): void
    {
        $uri = $this->createStub(UriInterface::class);

        $clone = $this->subject->withUri($uri);

        self::assertInstanceOf(DummyRequest::class, $clone);
        self::assertNotSame($this->subject, $clone);
    }
}
