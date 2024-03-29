<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Mapper;

use OliverKlee\Oelib\Exception\NotFoundException;
use OliverKlee\Oelib\Mapper\MapperRegistry;
use OliverKlee\Oelib\Model\AbstractModel;
use OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures\ColumnLessTestingMapper;
use OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures\ModelLessTestingMapper;
use OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures\TableLessTestingMapper;
use OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures\TestingChildMapper;
use OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures\TestingMapper;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingModel;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \OliverKlee\Oelib\Mapper\AbstractDataMapper
 */
final class AbstractDataMapperTest extends UnitTestCase
{
    private TestingMapper $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new TestingMapper();
    }

    ///////////////////////////////////////
    // Tests concerning the instantiation
    ///////////////////////////////////////

    /**
     * @test
     */
    public function instantiationOfSubclassWithEmptyTableNameThrowsException(): void
    {
        $this->expectException(\TypeError::class);

        new TableLessTestingMapper();
    }

    /**
     * @test
     */
    public function instantiationOfSubclassWithEmptyColumnListThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ColumnLessTestingMapper();
    }

    /**
     * @test
     */
    public function instantiationOfSubclassWithEmptyModelNameThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ModelLessTestingMapper();
    }

    //////////////////////////////
    // Tests concerning getModel
    //////////////////////////////

    /**
     * @test
     */
    public function getModelWithArrayWithoutUidElementProvidedThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$data must contain an element "uid".');

        $this->subject->getModel([]);
    }

    /**
     * @test
     */
    public function getModelWithArrayWithZeroUidThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$data["uid"] must be a positive integer.');
        $this->expectExceptionCode(1_699_655_040);

        $this->subject->getModel(['uid' => 0]);
    }

    /**
     * @test
     */
    public function getModelWithArrayWithNegativeUidThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$data["uid"] must be a positive integer.');
        $this->expectExceptionCode(1_699_655_040);

        $this->subject->getModel(['uid' => -1]);
    }

    // Tests concerning load and reload

    /**
     * @test
     */
    public function loadWithModelWithoutUidThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'load must only be called with models that already have a UID.'
        );

        $model = new TestingModel();
        $this->subject->load($model);
    }

    /**
     * @test
     */
    public function reloadForTestingOnlyGhostThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $model = $this->subject->getNewGhost();
        $this->subject->load($model);
    }

    /**
     * @test
     */
    public function reloadForModelWithoutUidThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $model = new TestingModel();
        $this->subject->load($model);
    }

    //////////////////////////////////////
    // Tests concerning the model states
    //////////////////////////////////////

    /**
     * @test
     */
    public function findInitiallyReturnsGhostModel(): void
    {
        $uid = 42;

        self::assertTrue(
            $this->subject->find($uid)->isGhost()
        );
    }

    //////////////////////////
    // Tests concerning find
    //////////////////////////

    /**
     * @test
     */
    public function findWithZeroUidThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$uid must be > 0.'
        );

        // @phpstan-ignore-next-line We're testing for a contract violation here.
        $this->subject->find(0);
    }

    /**
     * @test
     */
    public function findWithNegativeUidThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$uid must be > 0.'
        );

        // @phpstan-ignore-next-line We're testing for a contract violation here.
        $this->subject->find(-1);
    }

    /**
     * @test
     */
    public function findWithUidReturnsModelWithThatUid(): void
    {
        $uid = 42;

        self::assertSame(
            $uid,
            $this->subject->find($uid)->getUid()
        );
    }

    /**
     * @test
     */
    public function findWithUidCalledTwoTimesReturnsSameModel(): void
    {
        $uid = 42;

        self::assertSame(
            $this->subject->find($uid),
            $this->subject->find($uid)
        );
    }

    /////////////////////////////////
    // Tests concerning getNewGhost
    /////////////////////////////////

    /**
     * @test
     */
    public function getNewGhostReturnsModel(): void
    {
        self::assertInstanceOf(AbstractModel::class, $this->subject->getNewGhost());
    }

    /**
     * @test
     */
    public function getNewGhostReturnsModelSpecificToTheMapper(): void
    {
        $result = $this->subject->getNewGhost();

        self::assertInstanceOf(TestingModel::class, $result);
    }

    /**
     * @test
     */
    public function getNewGhostReturnsGhost(): void
    {
        self::assertTrue(
            $this->subject->getNewGhost()->isGhost()
        );
    }

    /**
     * @test
     */
    public function getNewGhostReturnsModelWithUid(): void
    {
        self::assertTrue(
            $this->subject->getNewGhost()->hasUid()
        );
    }

    /**
     * @test
     */
    public function getNewGhostCreatesRegisteredModel(): void
    {
        $ghost = $this->subject->getNewGhost();
        $uid = $ghost->getUid();
        \assert($uid >= 1);

        self::assertSame(
            $ghost,
            $this->subject->find($uid)
        );
    }

    /**
     * @test
     */
    public function loadingAGhostCreatedWithGetNewGhostThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'This ghost was created via getNewGhost and must not be loaded.'
        );

        $ghost = $this->subject->getNewGhost();
        $this->subject->load($ghost);
    }

    ////////////////////////////////////////////////
    // Tests concerning findSingleByWhereClause().
    ////////////////////////////////////////////////

    /**
     * @test
     */
    public function findSingleByWhereClauseWithEmptyWhereClausePartsThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The parameter $whereClauseParts must not be empty.');

        // @phpstan-ignore-next-line We are explicitly testing for a contract violation here.
        $this->subject->findSingleByWhereClause([]);
    }

    /////////////////////////////////////
    // Tests concerning additional keys
    /////////////////////////////////////

    /**
     * @test
     */
    public function findOneByKeyFromCacheForEmptyKeyThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$key must not be empty.');

        // @phpstan-ignore-next-line We are explicitly testing for a contract violation here.
        $this->subject->findOneByKeyFromCache('', 'bar');
    }

    /**
     * @test
     */
    public function findOneByKeyFromCacheForInexistentKeyThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('"foo" is not a valid key for this mapper.');

        $this->subject->findOneByKeyFromCache('foo', 'bar');
    }

    /**
     * @test
     */
    public function findOneByKeyFromCacheForEmptyValueThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$value must not be empty.');

        // @phpstan-ignore-next-line We are explicitly testing for a contract violation here.
        $this->subject->findOneByKeyFromCache('title', '');
    }

    /**
     * @test
     */
    public function findOneByKeyFromCacheForModelNotInCacheThrowsException(): void
    {
        $this->expectException(NotFoundException::class);

        $this->subject->findOneByKeyFromCache('title', 'bar');
    }

    /**
     * @test
     */
    public function findOneByKeyForEmptyKeyThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$key must not be empty.');

        // @phpstan-ignore-next-line We are explicitly testing for a contract violation here.
        $this->subject->findOneByKey('', 'bar');
    }

    /**
     * @test
     */
    public function findOneByKeyForInexistentKeyThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '"foo" is not a valid key for this mapper.'
        );

        $this->subject->findOneByKey('foo', 'bar');
    }

    /**
     * @test
     */
    public function findOneByKeyForEmptyValueThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$value must not be empty.');

        // @phpstan-ignore-next-line We are explicitly testing for a contract violation here.
        $this->subject->findOneByKey('title', '');
    }

    ///////////////////////////////////////
    // Tests concerning findAllByRelation
    ///////////////////////////////////////

    /**
     * @test
     */
    public function findAllByRelationWithModelWithoutUidThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$model must have a UID.'
        );

        $model = new TestingModel();

        MapperRegistry::get(TestingChildMapper::class)->findAllByRelation($model, 'parent');
    }

    /**
     * @test
     */
    public function getTableNameReturnsTableName(): void
    {
        self::assertSame(
            'tx_oelib_test',
            $this->subject->getTableName()
        );
    }
}
