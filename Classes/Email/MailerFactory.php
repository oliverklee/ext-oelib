<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Email;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class returns either an instance of the RealMailer which sends
 * e-mails or an instance of the EmailCollector.
 *
 * @deprecated will be removed in oelib 4.0
 */
class MailerFactory implements SingletonInterface
{
    /**
     * @var bool whether the test mode is set
     */
    private $isTestMode = false;

    /**
     * @var AbstractMailer the mailer
     */
    private $mailer = null;

    /**
     * Cleans up (if necessary).
     *
     * @return void
     */
    public function cleanUp()
    {
        if ($this->mailer instanceof AbstractMailer) {
            $this->mailer->cleanUp();
        }
    }

    /**
     * Retrieves the singleton mailer instance. Depending on the mode, this
     * instance is either an e-mail collector or a real mailer.
     *
     * @return AbstractMailer|RealMailer|EmailCollector the singleton mailer object
     */
    public function getMailer()
    {
        $className = $this->isTestMode ? EmailCollector::class : RealMailer::class;
        if (!$this->mailer instanceof $className) {
            $this->mailer = GeneralUtility::makeInstance($className);
        }

        return $this->mailer;
    }

    /**
     * Enables the test mode.
     *
     * @return void
     */
    public function enableTestMode()
    {
        $this->isTestMode = true;
    }
}
