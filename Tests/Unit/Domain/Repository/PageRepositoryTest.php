<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Domain\Repository;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Domain\Repository\PageRepository;
use Prophecy\Prophecy\ProphecySubjectInterface;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * Test case.
 *
 * @author Stefano Kowalke <info@arroba-it.de>
 */
class PageRepositoryTest extends UnitTestCase
{
    /**
     * @var PageRepository
     */
    private $subject = null;

    protected function setUp()
    {
        /** @var ObjectManagerInterface|ProphecySubjectInterface $objectManagerStub */
        $objectManagerStub = $this->prophesize(ObjectManagerInterface::class)->reveal();
        $this->subject = new PageRepository($objectManagerStub);
    }

    /**
     * @test
     */
    public function imlementsSingletonInterface()
    {
        self::assertInstanceOf(SingletonInterface::class, $this->subject);
    }

    /**
     * @test
     */
    public function recursionDepthLowerThanZeroThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        PageRepository::findWithinSingleParentPage('', -1);
    }

    public function untrimmedStartPagesDataProvider(): array
    {
        return [
            'empty' => ['', ''],
            'nonEmpty' => [' ', ''],
            'leftSpace' => [' 1,2', '1,2'],
            'rightSpace' => ['1,2 ', '1,2'],
            'leftRightSpace' => [' 1,2 ', '1,2'],
        ];
    }

    /**
     * @test
     * @dataProvider untrimmedStartPagesDataProvider
     *
     * @param $actual
     * @param $expected
     */
    public function recursionDepthZeroReturnsTrimmedStartPages($actual, $expected): void
    {
        self::assertEquals(
            $expected,
            PageRepository::findWithinSingleParentPage($actual, 0)
        );
    }
}
