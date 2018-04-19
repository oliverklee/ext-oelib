<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case.
 *
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_AbstractMailerTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_Oelib_EmailCollector
     */
    private $subject = null;

    /**
     * @var MailMessage
     */
    private $message1 = null;

    /**
     * @var string[]
     */
    private $email = [
        'recipient' => 'any-recipient@email-address.org',
        'subject' => 'any subject',
        'message' => 'any message',
        'headers' => '',
    ];

    /**
     * @var bool
     */
    protected $deprecationLogEnabledBackup = false;

    protected function setUp()
    {
        $this->deprecationLogEnabledBackup = $GLOBALS['TYPO3_CONF_VARS']['SYS']['enableDeprecationLog'];

        $this->subject = new Tx_Oelib_EmailCollector();

        $this->message1 = $this->getMock(MailMessage::class, ['send', '__destruct']);
        GeneralUtility::addInstance(MailMessage::class, $this->message1);
    }

    protected function tearDown()
    {
        // Get any surplus instances added via \TYPO3\CMS\Core\Utility\GeneralUtility::addInstance.
        GeneralUtility::makeInstance(MailMessage::class);

        $this->subject->cleanUp();

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['enableDeprecationLog'] = $this->deprecationLogEnabledBackup;
    }

    /*
     * Tests concerning send
     */

    /**
     * @test
     */
    public function getSentEmailsWithoutAnyEmailReturnsEmptyArray()
    {
        self::assertSame(
            [],
            $this->subject->getSentEmails()
        );
    }

    /**
     * @test
     */
    public function getNumberOfSentEmailsWithoutAnyEmailReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getNumberOfSentEmails()
        );
    }

    /**
     * @test
     */
    public function getFirstSentEmailWithoutAnyEmailReturnsNull()
    {
        self::assertNull(
            $this->subject->getFirstSentEmail()
        );
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $email must have a sender set.
     */
    public function sendWithoutSenderThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->addRecipient($emailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The e-mail must have at least one recipient.
     */
    public function sendWithoutRecipientThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->setSender($emailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The e-mail subject must not be empty.
     */
    public function sendWithoutSubjectThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->setSender($emailRole);
        $email->addRecipient($emailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The e-mail message must not be empty.
     */
    public function sendWithoutMessageThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->setSender($emailRole);
        $email->addRecipient($emailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     */
    public function sendWithAllValidEmailAddressesNotThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->setSender($emailRole);
        $email->addRecipient($emailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     */
    public function sendWithAllValidLocalhostEmailAddressesNotThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@localhost');
        $email->setSender($emailRole);
        $email->addRecipient($emailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function sendWithEmptyFromAddressThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->addRecipient($emailRole);

        $emptyEmailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', '');
        $email->setSender($emptyEmailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function sendWithInvalidFromAddressThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->addRecipient($emailRole);

        $invalidEmailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'hkqwbeqwbasgrfa asdfa');
        $email->setSender($invalidEmailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function sendWithEmptyToAddressThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->setSender($emailRole);

        $emptyEmailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', '');
        $email->addRecipient($emptyEmailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function sendWithInvalidToAddressThrowsException()
    {
        $email = new Tx_Oelib_Mail();
        $email->setSubject('Everybody is happy!');
        $email->setMessage('That is the way it is.');

        $emailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'john@example.com');
        $email->setSender($emailRole);

        $invalidEmailRole = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'hkqwbeqwbasgrfa asdfa');
        $email->addRecipient($invalidEmailRole);

        $this->subject->send($email);
    }

    /**
     * @test
     */
    public function sendSetsSenderNameAndEmail()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        $sentEmail = $this->subject->getFirstSentEmail();
        self::assertSame(
            [$sender->getEmailAddress() => $sender->getName()],
            $sentEmail->getFrom()
        );
    }

    /**
     * @test
     */
    public function sendSetsRecipientNameAndEmail()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        $sentEmail = $this->subject->getFirstSentEmail();
        self::assertSame(
            [$recipient->getEmailAddress() => $recipient->getName()],
            $sentEmail->getTo()
        );
    }

    /**
     * @test
     */
    public function sendForTwoRecipientsSendsTwoEmails()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);
        $recipient1 = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', 'joe@example.com');
        $eMail->addRecipient($recipient1);
        $recipient2 = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('Jane Doe', 'jane@example.com');
        $eMail->addRecipient($recipient2);

        $this->subject->send($eMail);

        self::assertSame(
            2,
            $this->subject->getNumberOfSentEmails()
        );
    }

    /**
     * @test
     */
    public function sendSetsSubject()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        $sentEmail = $this->subject->getFirstSentEmail();
        self::assertSame(
            $this->email['subject'],
            $sentEmail->getSubject()
        );
    }

    /**
     * @test
     */
    public function sendingPlainTextMailUsesDefaultCharacterSet()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        self::assertSame(
            'utf-8',
            $this->subject->getFirstSentEmail()->getCharset()
        );
    }

    /**
     * @test
     */
    public function sendSetsPlainTextBody()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        $sentEmail = $this->subject->getFirstSentEmail();
        self::assertSame(
            $this->email['message'],
            $sentEmail->getBody()
        );
    }

    /**
     * @test
     */
    public function sendingPlainTextMailUsesPlainTextEncoding()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        self::assertSame(
            'text/plain',
            $this->subject->getFirstSentEmail()->getContentType()
        );
    }

    /**
     * @test
     */
    public function sendingPlainTextMailByDefaultRemovesAnyCarriageReturnFromBody()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage(
            'one long line ...........................................' . CRLF .
            'now a blank line:' . LF . LF .
            'another long line .........................................' . LF .
            'and a line with umlauts: Hörbär saß früh.'
        );

        $this->subject->send($eMail);

        self::assertNotContains(
            CR,
            $this->subject->getFirstSentEmail()->getBody()
        );
    }

    /**
     * @test
     */
    public function sendingPlainTextMailWithFormattingRemovesAnyCarriageReturnFromBody()
    {
        $this->subject->sendFormattedEmails(true);

        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage(
            'one long line ...........................................' . CRLF .
            'now a blank line:' . LF . LF .
            'another long line .........................................' . LF .
            'and a line with umlauts: Hörbär saß früh.'
        );

        $this->subject->send($eMail);

        self::assertNotContains(
            CR,
            $this->subject->getFirstSentEmail()->getBody()
        );
    }

    /**
     * @test
     */
    public function sendingPlainTextMailWithoutFormattingNotRemovesAnyCarriageReturnFromBody()
    {
        $this->subject->sendFormattedEmails(false);

        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage(
            'one long line ...........................................' . CRLF .
            'now a blank line:' . LF . LF .
            'another long line .........................................' . LF .
            'and a line with umlauts: Hörbär saß früh.'
        );

        $this->subject->send($eMail);

        self::assertContains(
            CR,
            $this->subject->getFirstSentEmail()->getBody()
        );
    }

    /**
     * @test
     */
    public function sendSetsHtmlBody()
    {
        $htmlMessage = '<h1>Very cool HTML message</h1>' . LF . '<p>Great to have HTML e-mails in oelib.</p>';
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage('This is the plain text message.');
        $eMail->setHTMLMessage($htmlMessage);

        $this->subject->send($eMail);

        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var Swift_Mime_MimeEntity $firstChild */
        $firstChild = $children[0];
        self::assertSame(
            $htmlMessage,
            $firstChild->getBody()
        );
    }

    /**
     * @test
     */
    public function sendSetsHtmlBodyWithTextHtmlContentType()
    {
        $htmlMessage = '<h1>Very cool HTML message</h1>' . LF . '<p>Great to have HTML e-mails in oelib.</p>';
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage('This is the plain text message.');
        $eMail->setHTMLMessage($htmlMessage);

        $this->subject->send($eMail);

        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var Swift_Mime_MimeEntity $firstChild */
        $firstChild = $children[0];
        self::assertSame(
            'text/html',
            $firstChild->getContentType()
        );
    }

    /**
     * @test
     */
    public function sendWithReturnPathSetsReturnPath()
    {
        $returnPath = 'return@example.com';

        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);
        $eMail->setReturnPath($returnPath);

        $this->subject->send($eMail);

        $sentEmail = $this->subject->getFirstSentEmail();
        self::assertSame(
            $returnPath,
            $sentEmail->getReturnPath()
        );
    }

    /**
     * @test
     */
    public function sendWithoutReturnPathNotSetsReturnPath()
    {
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $this->subject->send($eMail);

        $sentEmail = $this->subject->getFirstSentEmail();
        self::assertNull(
            $sentEmail->getReturnPath()
        );
    }

    /**
     * @test
     */
    public function sendCanAddOneAttachmentFromFile()
    {
        $attachment = new Tx_Oelib_Attachment();
        $attachment->setFileName(ExtensionManagementUtility::extPath('oelib', 'Tests/Unit/Fixtures/test.txt'));
        $attachment->setContentType('text/plain');

        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $eMail->addAttachment($attachment);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var Swift_Mime_Attachment $firstChild */
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
        $attachment = new Tx_Oelib_Attachment();
        $attachment->setContent($content);
        $attachment->setContentType('text/html');

        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $eMail->addAttachment($attachment);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var Swift_Mime_Attachment $firstChild */
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
        $attachment = new Tx_Oelib_Attachment();
        $attachment->setContent($content);
        $attachment->setFileName($fileName);
        $attachment->setContentType('text/html');

        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $eMail->addAttachment($attachment);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();
        /** @var Swift_Mime_Attachment $firstChild */
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
        $sender = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('', 'any-sender@email-address.org');
        $recipient = new Tx_Oelib_Tests_Unit_Fixtures_TestingMailRole('John Doe', $this->email['recipient']);
        $eMail = new Tx_Oelib_Mail();
        $eMail->setSender($sender);
        $eMail->addRecipient($recipient);
        $eMail->setSubject($this->email['subject']);
        $eMail->setMessage($this->email['message']);

        $attachment1 = new Tx_Oelib_Attachment();
        $attachment1->setFileName(ExtensionManagementUtility::extPath('oelib', 'Tests/Unit/Fixtures/test.txt'));
        $attachment1->setContentType('text/plain');
        $eMail->addAttachment($attachment1);
        $attachment2 = new Tx_Oelib_Attachment();
        $attachment2->setFileName(ExtensionManagementUtility::extPath('oelib', 'Tests/Unit/Fixtures/test_2.css'));
        $attachment2->setContentType('text/css');
        $eMail->addAttachment($attachment2);

        $this->subject->send($eMail);
        $children = $this->subject->getFirstSentEmail()->getChildren();

        self::assertCount(
            2,
            $children
        );
    }
}
