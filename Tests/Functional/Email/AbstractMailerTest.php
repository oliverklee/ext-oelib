<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Email;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use OliverKlee\Oelib\Email\Attachment;
use OliverKlee\Oelib\Email\EmailCollector;
use OliverKlee\Oelib\Email\Mail;
use OliverKlee\Oelib\Tests\Unit\Email\Fixtures\TestingMailRole;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class AbstractMailerTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    /**
     * @var EmailCollector
     */
    private $subject = null;

    /**
     * @var string[]
     */
    const EMAIL = [
        'recipient' => 'any-recipient@example.com',
        'subject' => 'any subject',
        'message' => 'any message',
        'headers' => '',
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->subject = new EmailCollector();

        $message = $this->getMockBuilder(MailMessage::class)->setMethods(['send'])->getMock();
        GeneralUtility::addInstance(MailMessage::class, $message);
    }

    protected function tearDown()
    {
        // Get any surplus instances added via \TYPO3\CMS\Core\Utility\GeneralUtility::addInstance.
        GeneralUtility::makeInstance(MailMessage::class);

        parent::tearDown();
    }

    /*
     * Tests concerning send
     */

    /**
     * @test
     */
    public function sendCanAddOneAttachmentFromFile()
    {
        /** @var \Swift_Attachment $attachment */
        $attachment = \Swift_Attachment::newInstance(
            null,
            __DIR__ . '/Fixtures/test.txt',
            'text/plain'
        );

        $sender = new TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new TestingMailRole('John Doe', self::EMAIL['recipient']);
        $eMail = new Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject(self::EMAIL['subject']);
        $eMail->setMessage(self::EMAIL['message']);

        $eMail->addAttachment($attachment);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var \Swift_Mime_Attachment $firstChild */
        $firstChild = $children[0];

        self::assertSame(
            'some text',
            $firstChild->getBody()
        );
        self::assertSame(
            'text/plain',
            $firstChild->getContentType()
        );
    }

    /**
     * @test
     */
    public function sendCanAddOneAttachmentFromContent()
    {
        $content = '<p>Hello world!</p>';
        /** @var \Swift_Attachment $attachment */
        $attachment = \Swift_Attachment::newInstance(
            $content,
            null,
            'text/html'
        );

        $sender = new TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new TestingMailRole('John Doe', self::EMAIL['recipient']);
        $eMail = new Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject(self::EMAIL['subject']);
        $eMail->setMessage(self::EMAIL['message']);

        $eMail->addAttachment($attachment);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var \Swift_Mime_Attachment $firstChild */
        $firstChild = $children[0];

        self::assertSame(
            $content,
            $firstChild->getBody()
        );
        self::assertSame(
            'text/html',
            $firstChild->getContentType()
        );
    }

    /**
     * @test
     */
    public function sendCanAddOneAttachmentWithFilenameFromContent()
    {
        $content = '<p>Hello world!</p>';
        $fileName = 'greetings.html';
        /** @var \Swift_Attachment $attachment */
        $attachment = \Swift_Attachment::newInstance(
            $content,
            $fileName,
            'text/html'
        );

        $sender = new TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new TestingMailRole('John Doe', self::EMAIL['recipient']);
        $eMail = new Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject(self::EMAIL['subject']);
        $eMail->setMessage(self::EMAIL['message']);

        $eMail->addAttachment($attachment);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var \Swift_Mime_Attachment $firstChild */
        $firstChild = $children[0];

        self::assertSame(
            $content,
            $firstChild->getBody()
        );
        self::assertSame(
            $fileName,
            $firstChild->getFilename()
        );
        self::assertSame(
            'text/html',
            $firstChild->getContentType()
        );
    }

    /**
     * @test
     */
    public function sendCanAddTwoAttachments()
    {
        $sender = new TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new TestingMailRole('John Doe', self::EMAIL['recipient']);
        $eMail = new Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject(self::EMAIL['subject']);
        $eMail->setMessage(self::EMAIL['message']);

        /** @var \Swift_Attachment $attachment1 */
        $attachment1 = \Swift_Attachment::newInstance(
            'Test',
            __DIR__ . '/Fixtures/test.txt',
            'text/plain'
        );
        $eMail->addAttachment($attachment1);

        /** @var \Swift_Attachment $attachment2 */
        $attachment2 = \Swift_Attachment::newInstance(
            'Test',
            __DIR__ . '/Fixtures/test_2.css',
            'text/css'
        );
        $eMail->addAttachment($attachment2);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();

        self::assertCount(
            2,
            $children
        );
    }
}
