<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Email;

use OliverKlee\Oelib\Email\GeneralEmailRole;
use OliverKlee\Oelib\Interfaces\MailRole;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \OliverKlee\Oelib\Email\GeneralEmailRole
 */
final class GeneralEmailRoleTest extends UnitTestCase
{
    /**
     * @test
     */
    public function implementsMailRole(): void
    {
        $subject = new GeneralEmailRole('jade@example.com');

        self::assertInstanceOf(MailRole::class, $subject);
    }

    /**
     * @test
     */
    public function usesEmailAddressFromConstructor(): void
    {
        $emailAddress = 'jade@example.com';
        $subject = new GeneralEmailRole($emailAddress);

        self::assertSame($emailAddress, $subject->getEmailAddress());
    }

    /**
     * @test
     */
    public function usesNameFromConstructor(): void
    {
        $name = 'Jade Jennings';
        $subject = new GeneralEmailRole('jade@example.com', $name);

        self::assertSame($name, $subject->getName());
    }

    /**
     * @test
     */
    public function hasEmptyNameByDefault(): void
    {
        $subject = new GeneralEmailRole('jade@example.com');

        self::assertSame('', $subject->getName());
    }
}
