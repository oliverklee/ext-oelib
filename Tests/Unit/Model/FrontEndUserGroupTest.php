<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Model;

use OliverKlee\Oelib\Model\AbstractModel;
use OliverKlee\Oelib\Model\FrontEndUserGroup;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \OliverKlee\Oelib\Model\FrontEndUserGroup
 */
final class FrontEndUserGroupTest extends UnitTestCase
{
    private FrontEndUserGroup $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new FrontEndUserGroup();
    }

    /**
     * @test
     */
    public function isModel(): void
    {
        self::assertInstanceOf(AbstractModel::class, $this->subject);
    }
}
