<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Email;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Email\Attachment;

class AttachmentTest extends UnitTestCase
{
    /**
     * @var Attachment
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new Attachment();
    }

    ///////////////////////////////////////////////////////
    // Tests regarding setting and getting the file name.
    ///////////////////////////////////////////////////////

    /**
     * @test
     */
    public function getFileNameInitiallyReturnsAnEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getFileName()
        );
    }

    /**
     * @test
     */
    public function getFileNameWithFileNameSetReturnsFileName()
    {
        $this->subject->setFileName('test.txt');

        self::assertSame(
            'test.txt',
            $this->subject->getFileName()
        );
    }

    /**
     * @test
     */
    public function setFileNameWithEmptyFileNameThrowsException()
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$fileName must not be empty.'
        );

        $this->subject->setFileName('');
    }

    //////////////////////////////////////////////////////////
    // Tests regarding setting and getting the content type.
    //////////////////////////////////////////////////////////

    /**
     * @test
     */
    public function getContentTypeInitiallyReturnsAnEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getContentType()
        );
    }

    /**
     * @test
     */
    public function getContentTypeWithContentTypeSetReturnsContentType()
    {
        $this->subject->setContentType('text/plain');

        self::assertSame(
            'text/plain',
            $this->subject->getContentType()
        );
    }

    /**
     * @test
     */
    public function setContentTypeWithEmptyContentTypeThrowsException()
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$contentType must not be empty.'
        );

        $this->subject->setContentType('');
    }

    /////////////////////////////////////////////////////
    // Tests regarding setting and getting the content.
    /////////////////////////////////////////////////////

    /**
     * @test
     */
    public function getContentInitiallyReturnsAnEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getContent()
        );
    }

    /**
     * @test
     */
    public function getContentWithContentSetReturnsContent()
    {
        $this->subject->setContent('test content');

        self::assertSame(
            'test content',
            $this->subject->getContent()
        );
    }
}
