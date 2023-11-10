<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Model;

use OliverKlee\Oelib\DataStructures\Collection;
use OliverKlee\Oelib\Mapper\BackEndUserGroupMapper;
use OliverKlee\Oelib\Mapper\MapperRegistry;
use OliverKlee\Oelib\Model\BackEndUser;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\Oelib\Model\BackEndUser
 */
final class BackEndUserTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    protected $initializeDatabase = false;

    /**
     * @var BackEndUser
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new BackEndUser();
    }

    //////////////////////////////////
    // Tests concerning getAllGroups
    //////////////////////////////////

    /**
     * @test
     */
    public function getAllGroupsForNoGroupsReturnsList(): void
    {
        $this->subject->setData(['usergroup' => new Collection()]);

        self::assertInstanceOf(
            Collection::class,
            $this->subject->getAllGroups()
        );
    }

    /**
     * @test
     */
    public function getAllGroupsForNoGroupsReturnsEmptyList(): void
    {
        $this->subject->setData(['usergroup' => new Collection()]);

        self::assertTrue(
            $this->subject->getAllGroups()->isEmpty()
        );
    }

    /**
     * @test
     */
    public function getAllGroupsForOneGroupReturnsListWithThatGroup(): void
    {
        $group = MapperRegistry::get(BackEndUserGroupMapper::class)->getLoadedTestingModel([]);
        $groups = new Collection();
        $groups->add($group);
        $this->subject->setData(['usergroup' => $groups]);

        self::assertSame(
            $group,
            $this->subject->getAllGroups()->first()
        );
    }

    /**
     * @test
     */
    public function getAllGroupsForTwoGroupsReturnsBothGroups(): void
    {
        $group1 = MapperRegistry::get(BackEndUserGroupMapper::class)->getLoadedTestingModel([]);
        $group1Uid = $group1->getUid();
        \assert($group1Uid > 0);
        $group2 = MapperRegistry::get(BackEndUserGroupMapper::class)->getLoadedTestingModel([]);
        $group2Uid = $group2->getUid();
        \assert($group2Uid > 0);
        $groups = new Collection();
        $groups->add($group1);
        $groups->add($group2);
        $this->subject->setData(['usergroup' => $groups]);

        self::assertTrue(
            $this->subject->getAllGroups()->hasUid($group1Uid)
        );
        self::assertTrue(
            $this->subject->getAllGroups()->hasUid($group2Uid)
        );
    }

    /**
     * @test
     */
    public function getAllGroupsForGroupWithSubgroupReturnsBothGroups(): void
    {
        $subgroup = MapperRegistry::get(BackEndUserGroupMapper::class)->getLoadedTestingModel([]);
        $subgroupUid = $subgroup->getUid();
        \assert($subgroupUid > 0);
        $group = MapperRegistry::get(BackEndUserGroupMapper::class)
            ->getLoadedTestingModel(['subgroup' => $subgroupUid]);
        $groupUid = $group->getUid();
        \assert($groupUid > 0);
        $groups = new Collection();
        $groups->add($group);
        $this->subject->setData(['usergroup' => $groups]);

        self::assertTrue(
            $this->subject->getAllGroups()->hasUid($groupUid)
        );
        self::assertTrue(
            $this->subject->getAllGroups()->hasUid($subgroupUid)
        );
    }

    /**
     * @test
     */
    public function getAllGroupsForGroupWithSubsubgroupContainsSubsubgroup(): void
    {
        $subSubGroup = MapperRegistry::get(BackEndUserGroupMapper::class)
            ->getLoadedTestingModel([]);
        $subgroup = MapperRegistry::get(BackEndUserGroupMapper::class)
            ->getLoadedTestingModel(['subgroup' => $subSubGroup->getUid()]);
        $group = MapperRegistry::get(BackEndUserGroupMapper::class)
            ->getLoadedTestingModel(['subgroup' => $subgroup->getUid()]);
        $groups = new Collection();
        $groups->add($group);
        $this->subject->setData(['usergroup' => $groups]);

        $subSubGroupUid = $subSubGroup->getUid();
        \assert($subSubGroupUid > 0);
        self::assertTrue(
            $this->subject->getAllGroups()->hasUid($subSubGroupUid)
        );
    }

    /**
     * @test
     */
    public function getAllGroupsForGroupWithSubgroupSelfReferenceReturnsOnlyOneGroup(): void
    {
        $group = MapperRegistry::get(BackEndUserGroupMapper::class)->getNewGhost();
        $subgroups = new Collection();
        $subgroups->add($group);
        $group->setData(['subgroup' => $subgroups]);

        $groups = new Collection();
        $groups->add($group);
        $this->subject->setData(['usergroup' => $groups]);

        self::assertCount(1, $this->subject->getAllGroups());
    }

    /**
     * @test
     */
    public function getAllGroupsForGroupWithSubgroupCycleReturnsBothGroups(): void
    {
        $group1 = MapperRegistry::get(BackEndUserGroupMapper::class)->getNewGhost();
        $group2 = MapperRegistry::get(BackEndUserGroupMapper::class)->getNewGhost();

        $subgroups1 = new Collection();
        $subgroups1->add($group2);
        $group1->setData(['subgroup' => $subgroups1]);

        $subgroups2 = new Collection();
        $subgroups2->add($group1);
        $group2->setData(['subgroup' => $subgroups2]);

        $groups = new Collection();
        $groups->add($group1);
        $this->subject->setData(['usergroup' => $groups]);

        self::assertCount(2, $this->subject->getAllGroups());
    }
}
