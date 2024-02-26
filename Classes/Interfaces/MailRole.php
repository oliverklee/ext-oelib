<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Interfaces;

/**
 * This interfaces represents an email role, e.g. a sender or a recipient.
 */
interface MailRole
{
    /**
     * Returns the real name of the email role.
     *
     * @return string the real name of the email role, might be empty
     */
    public function getName(): string;

    /**
     * Returns the email address of the email role.
     *
     * @return string the email address of the email role, might be empty
     */
    public function getEmailAddress(): string;
}
