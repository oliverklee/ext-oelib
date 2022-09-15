<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Domain\Model\Traits;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Domain\Model\Interfaces\CreationDate;
use OliverKlee\Oelib\Tests\Unit\Domain\Fixtures\CreatedModel;

/**
 * @covers \OliverKlee\Oelib\Domain\Model\Traits\CreationDate
 */
final class CreationDateTest extends UnitTestCase
{
    /**
     * @var CreatedModel
     */
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new CreatedModel();
    }

    /**
     * @test
     */
    public function implementsCreationDate(): void
    {
        self::assertInstanceOf(CreationDate::class, $this->subject);
    }

    /**
     * @test
     */
    public function getCreationDateInitiallyReturnsNull(): void
    {
        self::assertNull($this->subject->getCreationDate());
    }

    /**
     * @test
     */
    public function setCreationDateSetsCreationDate(): void
    {
        $date = new \DateTimeImmutable();
        $this->subject->setCreationDate($date);

        self::assertSame($date, $this->subject->getCreationDate());
    }
}
