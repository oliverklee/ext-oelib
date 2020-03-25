<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Email;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Email\EmailCollector;
use OliverKlee\Oelib\Email\MailerFactory;
use OliverKlee\Oelib\System\Typo3Version;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Test case.
 *
 * @author Saskia Metzler <saskia@merlin.owl.de>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class MailerFactoryTest extends UnitTestCase
{
    /**
     * @var MailerFactory
     */
    private $subject = null;

    protected function setUp()
    {
        if (Typo3Version::isAtLeast(10)) {
            self::markTestSkipped('These tests cannot be run in TYPO3 version 10.');
        }
        $this->subject = new MailerFactory();
    }

    /*
     * Tests concerning the basic functionality
     */

    /**
     * @test
     */
    public function factoryIsSingleton()
    {
        self::assertInstanceOf(
            SingletonInterface::class,
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getMailerInTestModeReturnsEmailCollector()
    {
        $this->subject->enableTestMode();
        self::assertInstanceOf(EmailCollector::class, $this->subject->getMailer());
    }

    /**
     * @test
     */
    public function getMailerReturnsTheSameObjectWhenTheInstanceWasNotDiscarded()
    {
        self::assertSame(
            $this->subject->getMailer(),
            $this->subject->getMailer()
        );
    }
}
