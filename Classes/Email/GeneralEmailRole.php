<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Email;

use OliverKlee\Oelib\Interfaces\MailRole;

/**
 * A general email subject.
 */
class GeneralEmailRole implements MailRole
{
    protected string $emailAddress;

    protected string $name;

    public function __construct(string $emailAddress, string $name = '')
    {
        $this->emailAddress = $emailAddress;
        $this->name = $name;
    }

    /**
     * Returns the email address of the email role.
     *
     * @return string the email address of the email role, might be empty
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * Returns the real name of the email role.
     *
     * @return string the real name of the email role, might be empty
     */
    public function getName(): string
    {
        return $this->name;
    }
}
