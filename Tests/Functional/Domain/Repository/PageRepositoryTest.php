<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Domain\Repository;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use OliverKlee\Oelib\Database\DatabaseService;
use OliverKlee\Oelib\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Test case.
 *
 * @author Stefano Kowalke <info@arroba-it.de>
 */
class PageRepositoryTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    /**
     * @var PageRepository
     */
    private $subject = null;

    protected function setUp()
    {
        parent::setUp();

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $objectManager->get(PageRepository::class);
    }

    /*
     * Tests concerning createRecursivePageList
     */

    /**
     * @test
     */
    public function createRecursivePageListReturnsAnEmptyStringForNoPagesWithDefaultRecursion()
    {
        self::assertSame(
            '',
            PageRepository ::findWithinSingleParentPage('')
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListReturnsAnEmptyStringForNoPagesWithZeroRecursion()
    {
        self::assertSame(
            '',
            DatabaseService ::createRecursivePageList('', 0)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListReturnsAnEmptyStringForNoPagesWithNonZeroRecursion()
    {
        self::assertSame(
            '',
            DatabaseService ::createRecursivePageList('', 1)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListThrowsWithNegativeRecursion()
    {
        $this->expectException(\InvalidArgumentException::class);

        DatabaseService ::createRecursivePageList('', -1);
    }

    /**
     * @test
     */
    public function createRecursivePageListForStringPageForRecursionWithoutSubPagesReturnsOnlyTheGivenPage()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList((string)$uid, 1)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListForIntPageForRecursionWithoutSubPagesReturnsOnlyTheGivenPage()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList($uid, 1)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListForStringPageWithoutRecursionWithoutSubPagesReturnsOnlyTheGivenPage()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList((string)$uid)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListForIntPageWithoutRecursionWithoutSubPagesReturnsOnlyTheGivenPage()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList($uid)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListDoesNotContainSubPagesForOnePageWithZeroRecursion()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid]);

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList((string)$uid, 0)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListDoesNotContainSubPagesForTwoPagesWithZeroRecursion()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid1 = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid1]);
        $uid2 = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            $this->sortExplode($uid1 . ',' . $uid2),
            $this->sortExplode(
                DatabaseService ::createRecursivePageList($uid1 . ',' . $uid2, 0)
            )
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListDoesNotContainSubSubPagesForRecursionOfOne()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid]);
        $subFolderUid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $subFolderUid]);

        self::assertSame(
            $this->sortExplode($uid . ',' . $subFolderUid),
            $this->sortExplode(DatabaseService ::createRecursivePageList($uid, 1))
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListDoesNotContainUnrelatedPages()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', []);

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList($uid, 0)
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListCanContainTwoSubPagesOfOnePage()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid]);
        $subFolderUid1 = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid]);
        $subFolderUid2 = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            $this->sortExplode($uid . ',' . $subFolderUid1 . ',' . $subFolderUid2),
            $this->sortExplode(DatabaseService ::createRecursivePageList($uid, 1))
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListCanContainSubPagesOfTwoPages()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid1 = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid2 = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid1]);
        $subFolderUid1 = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid2]);
        $subFolderUid2 = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            $this->sortExplode(
                $uid1 . ',' . $uid2 . ',' . $subFolderUid1 . ',' . $subFolderUid2
            ),
            $this->sortExplode(
                DatabaseService ::createRecursivePageList($uid1 . ',' . $uid2, 1)
            )
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListHeedsIncreasingRecursionDepthOnSubsequentCalls()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid]);
        $subFolderUid = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList($uid, 0)
        );
        self::assertSame(
            $this->sortExplode($uid . ',' . $subFolderUid),
            $this->sortExplode(DatabaseService ::createRecursivePageList($uid, 1))
        );
    }

    /**
     * @test
     */
    public function createRecursivePageListHeedsDecreasingRecursionDepthOnSubsequentCalls()
    {
        $this->getDatabaseConnection()->insertArray('pages', []);
        $uid = (int)$this->getDatabaseConnection()->lastInsertId();
        $this->getDatabaseConnection()->insertArray('pages', ['pid' => $uid]);
        $subFolderUid = (int)$this->getDatabaseConnection()->lastInsertId();

        self::assertSame(
            $this->sortExplode($uid . ',' . $subFolderUid),
            $this->sortExplode(DatabaseService ::createRecursivePageList($uid, 1))
        );
        self::assertSame(
            (string)$uid,
            DatabaseService ::createRecursivePageList($uid, 0)
        );
    }
}
