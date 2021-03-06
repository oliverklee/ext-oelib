<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\DataStructures;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\DataStructures\Collection;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingChildModel;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingModel;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \OliverKlee\Oelib\DataStructures\Collection
 */
final class CollectionTest extends UnitTestCase
{
    /**
     * @var Collection
     */
    private $subject = null;

    protected function setUp()
    {
        $this->subject = new Collection();
    }

    /**
     * @param TestingModel $firstModel
     * @param TestingModel $secondModel
     *
     * @return int
     */
    public function sortByTitleAscending(TestingModel $firstModel, TestingModel $secondModel): int
    {
        return strcmp($firstModel->getTitle(), $secondModel->getTitle());
    }

    /**
     * @param TestingModel $firstModel
     * @param TestingModel $secondModel
     *
     * @return int
     */
    public function sortByTitleDescending(TestingModel $firstModel, TestingModel $secondModel): int
    {
        return strcmp($secondModel->getTitle(), $firstModel->getTitle());
    }

    /**
     * Adds models with the given titles to the subject, one for each title
     * given in $titles.
     *
     * @param string[] $titles
     *        the titles for the models, must not be empty
     *
     * @return void
     */
    private function addModelsToFixture(array $titles = [''])
    {
        foreach ($titles as $title) {
            $model = new TestingModel();
            $model->setTitle($title);
            $this->subject->add($model);
        }
    }

    /**
     * @test
     */
    public function sortByTitleAscendingForFirstModelTitleAlphaAndSecondModelTitleBetaReturnsMinusOne()
    {
        $firstModel = new TestingModel();
        $firstModel->setTitle('alpha');
        $secondModel = new TestingModel();
        $secondModel->setTitle('beta');

        self::assertSame(
            -1,
            $this->sortByTitleAscending($firstModel, $secondModel)
        );
    }

    /**
     * @test
     */
    public function sortByTitleAscendingForFirstModelTitleBetaAndSecondModelTitleAlphaReturnsOne()
    {
        $firstModel = new TestingModel();
        $firstModel->setTitle('beta');
        $secondModel = new TestingModel();
        $secondModel->setTitle('alpha');

        self::assertSame(
            1,
            $this->sortByTitleAscending($firstModel, $secondModel)
        );
    }

    /**
     * @test
     */
    public function sortByTitleAscendingForFirstAndSecondModelTitleSameReturnsZero()
    {
        $firstModel = new TestingModel();
        $firstModel->setTitle('alpha');
        $secondModel = new TestingModel();
        $secondModel->setTitle('alpha');

        self::assertSame(
            0,
            $this->sortByTitleAscending($firstModel, $secondModel)
        );
    }

    ///////////////////////////////////////////
    // Tests concerning sortByTitleDescending
    ///////////////////////////////////////////

    /**
     * @test
     */
    public function sortByTitleDescendingForFirstModelTitleAlphaAndSecondModelTitleBetaReturnsOne()
    {
        $firstModel = new TestingModel();
        $firstModel->setTitle('alpha');
        $secondModel = new TestingModel();
        $secondModel->setTitle('beta');

        self::assertSame(
            1,
            $this->sortByTitleDescending($firstModel, $secondModel)
        );
    }

    /**
     * @test
     */
    public function sortByTitleDescendingForFirstModelTitleBetaAndSecondModelTitleAlphaReturnsMinusOne()
    {
        $firstModel = new TestingModel();
        $firstModel->setTitle('beta');
        $secondModel = new TestingModel();
        $secondModel->setTitle('alpha');

        self::assertSame(
            -1,
            $this->sortByTitleDescending($firstModel, $secondModel)
        );
    }

    /**
     * @test
     */
    public function sortByTitleDescendingForFirstAndSecondModelTitleSameReturnsZero()
    {
        $firstModel = new TestingModel();
        $firstModel->setTitle('alpha');
        $secondModel = new TestingModel();
        $secondModel->setTitle('alpha');

        self::assertSame(
            0,
            $this->sortByTitleDescending($firstModel, $secondModel)
        );
    }

    /**
     * @test
     */
    public function addModelsToFixtureForOneGivenTitleAddsOneModelToFixture()
    {
        $this->addModelsToFixture(['foo']);

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function addModelsToFixtureForOneGivenTitleAddsModelWithTitleGiven()
    {
        $this->addModelsToFixture(['foo']);

        /** @var TestingModel $firstItem */
        $firstItem = $this->subject->first();
        self::assertSame(
            'foo',
            $firstItem->getTitle()
        );
    }

    /**
     * @test
     */
    public function addModelsToFixtureForTwoGivenTitlesAddsTwoModelsToFixture()
    {
        $this->addModelsToFixture(['foo', 'bar']);

        self::assertSame(
            2,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function addModelsToFixtureForTwoGivenTitlesAddsFirstTitleToFirstModelFixture()
    {
        $this->addModelsToFixture(['bar', 'foo']);

        /** @var TestingModel $firstItem */
        $firstItem = $this->subject->first();
        self::assertSame(
            'bar',
            $firstItem->getTitle()
        );
    }

    /**
     * @test
     */
    public function addModelsToFixtureForThreeGivenTitlesAddsThreeModelsToFixture()
    {
        $this->addModelsToFixture(['foo', 'bar', 'fooBar']);

        self::assertSame(
            3,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function isEmptyForEmptyListReturnsTrue()
    {
        self::assertTrue(
            $this->subject->isEmpty()
        );
    }

    /**
     * @test
     */
    public function isEmptyAfterAddingModelReturnsFalse()
    {
        $this->addModelsToFixture();

        self::assertFalse(
            $this->subject->isEmpty()
        );
    }

    /**
     * @test
     */
    public function countForEmptyListReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function countWithOneModelWithoutUidReturnsOne()
    {
        $this->addModelsToFixture();

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function countWithOneModelWithUidReturnsOne()
    {
        $model = new TestingModel();
        $model->setUid(1);
        $this->subject->add($model);

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function countWithTwoDifferentModelsReturnsTwo()
    {
        $this->addModelsToFixture(['', '']);

        self::assertSame(
            2,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function countAfterAddingTheSameModelTwiceReturnsOne()
    {
        $model = new TestingModel();
        $this->subject->add($model);
        $this->subject->add($model);

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function currentForEmptyListReturnsNull()
    {
        self::assertNull(
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function currentWithOneItemReturnsThatItem()
    {
        $model = new TestingModel();
        $this->subject->add($model);

        self::assertSame(
            $model,
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function currentWithTwoItemsInitiallyReturnsTheFirstItem()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $this->subject->add($model2);

        self::assertSame(
            $model1,
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function keyInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->key()
        );
    }

    /**
     * @test
     */
    public function keyAfterNextInListWithOneElementReturnsOne()
    {
        $this->addModelsToFixture();
        $this->subject->next();

        self::assertSame(
            1,
            $this->subject->key()
        );
    }

    /**
     * @test
     */
    public function currentWithOneItemAfterNextReturnsNull()
    {
        $this->addModelsToFixture();

        $this->subject->next();

        self::assertNull(
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function currentWithTwoItemsAfterNextReturnsTheSecondItem()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $this->subject->add($model2);

        $this->subject->next();

        self::assertSame(
            $model2,
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function rewindAfterNextResetsKeyToZero()
    {
        $this->subject->next();
        $this->subject->rewind();

        self::assertSame(
            0,
            $this->subject->key()
        );
    }

    /**
     * @test
     */
    public function rewindAfterNextForOneItemsResetsCurrentToTheOnlyItem()
    {
        $model = new TestingModel();
        $this->subject->add($model);

        $this->subject->next();
        $this->subject->rewind();

        self::assertSame(
            $model,
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function firstForEmptyListReturnsNull()
    {
        self::assertNull(
            $this->subject->first()
        );
    }

    /**
     * @test
     */
    public function firstForListWithOneItemReturnsThatItem()
    {
        $model = new TestingModel();
        $this->subject->add($model);

        self::assertSame(
            $model,
            $this->subject->first()
        );
    }

    /**
     * @test
     */
    public function firstWithTwoItemsReturnsTheFirstItem()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $this->subject->add($model2);

        self::assertSame(
            $model1,
            $this->subject->first()
        );
    }

    /**
     * @test
     */
    public function firstWithTwoItemsAfterNextReturnsTheFirstItem()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $this->subject->add($model2);

        $this->subject->next();

        self::assertSame(
            $model1,
            $this->subject->first()
        );
    }

    /**
     * @test
     */
    public function validForEmptyListReturnsFalse()
    {
        self::assertFalse(
            $this->subject->valid()
        );
    }

    /**
     * @test
     */
    public function validForOneElementInitiallyReturnsTrue()
    {
        $this->addModelsToFixture();

        self::assertTrue(
            $this->subject->valid()
        );
    }

    /**
     * @test
     */
    public function validForOneElementAfterNextReturnsFalse()
    {
        $this->addModelsToFixture();

        $this->subject->next();

        self::assertFalse(
            $this->subject->valid()
        );
    }

    /**
     * @test
     */
    public function validForOneElementAfterNextAndRewindReturnsTrue()
    {
        $this->addModelsToFixture();

        $this->subject->next();
        $this->subject->rewind();

        self::assertTrue(
            $this->subject->valid()
        );
    }

    /**
     * @test
     */
    public function isIterator()
    {
        self::assertInstanceOf(\Iterator::class, $this->subject);
    }

    /**
     * @test
     */
    public function getUidsForEmptyListReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function getUidsForOneItemsWithoutUidReturnsEmptyString()
    {
        $this->addModelsToFixture();

        self::assertSame(
            '',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function getUidsForOneItemsWithUidReturnsThatUid()
    {
        $model = new TestingModel();
        $model->setUid(1);
        $this->subject->add($model);

        self::assertSame(
            '1',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function getUidsForTwoItemsWithUidReturnsCommaSeparatedItems()
    {
        $model1 = new TestingModel();
        $model1->setUid(1);
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $model2->setUid(42);
        $this->subject->add($model2);

        self::assertSame(
            '1,42',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function getUidsForTwoItemsWithDecreasingUidReturnsItemsInOrdnerOfInsertion()
    {
        $model1 = new TestingModel();
        $model1->setUid(42);
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $model2->setUid(1);
        $this->subject->add($model2);

        self::assertSame(
            '42,1',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function getUidsForDuplicateUidsReturnsUidsInOrdnerOfFirstInsertion()
    {
        $model1 = new TestingModel();
        $model1->setUid(1);
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $model2->setUid(2);
        $this->subject->add($model2);

        $this->subject->add($model1);

        self::assertSame(
            '1,2',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function getUidsForElementThatGotItsUidAfterAddingItReturnsItsUid()
    {
        $model = new TestingModel();
        $this->subject->add($model);
        $model->setUid(42);

        self::assertSame(
            '42',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function hasUidForInexistentUidReturnsFalse()
    {
        self::assertFalse(
            $this->subject->hasUid(42)
        );
    }

    /**
     * @test
     */
    public function hasUidForExistingUidReturnsTrue()
    {
        $model = new TestingModel();
        $model->setUid(42);
        $this->subject->add($model);

        self::assertTrue(
            $this->subject->hasUid(42)
        );
    }

    /**
     * @test
     */
    public function hasUidForElementThatGotItsUidAfterAddingItReturnsTrue()
    {
        $model = new TestingModel();
        $this->subject->add($model);
        $model->setUid(42);

        self::assertTrue(
            $this->subject->hasUid(42)
        );
    }

    /**
     * @test
     */
    public function sortWithTwoModelsAndSortByTitleAscendingFunctionSortsModelsByTitleAscending()
    {
        $this->addModelsToFixture(['Beta', 'Alpha']);
        $this->subject->sort([$this, 'sortByTitleAscending']);

        /** @var TestingModel $firstItem */
        $firstItem = $this->subject->first();
        self::assertSame(
            'Alpha',
            $firstItem->getTitle()
        );
    }

    /**
     * @test
     */
    public function sortWithThreeModelsAndSortByTitleAscendingFunctionSortsModelsByTitleAscending()
    {
        $this->addModelsToFixture(['Zeta', 'Beta', 'Alpha']);
        $this->subject->sort([$this, 'sortByTitleAscending']);

        /** @var TestingModel $firstItem */
        $firstItem = $this->subject->first();
        self::assertSame(
            'Alpha',
            $firstItem->getTitle()
        );
    }

    /**
     * @test
     */
    public function sortWithTwoModelsAndSortByTitleDescendingFunctionSortsModelsByTitleDescending()
    {
        $this->addModelsToFixture(['Alpha', 'Beta']);
        $this->subject->sort([$this, 'sortByTitleDescending']);

        /** @var TestingModel $firstItem */
        $firstItem = $this->subject->first();
        self::assertSame(
            'Beta',
            $firstItem->getTitle()
        );
    }

    /**
     * @test
     */
    public function sortMakesListDirty()
    {
        /** @var Collection|MockObject $subject */
        $subject = $this->createPartialMock(Collection::class, ['markAsDirty']);
        $subject->expects(self::once())->method('markAsDirty');

        $subject->sort([$this, 'sortByTitleAscending']);
    }

    /**
     * @test
     */
    public function appendEmptyListToEmptyListMakesEmptyList()
    {
        $otherList = new Collection();
        $this->subject->append($otherList);

        self::assertTrue(
            $this->subject->isEmpty()
        );
    }

    /**
     * @test
     */
    public function appendTwoItemListToEmptyListMakesTwoItemList()
    {
        $otherList = new Collection();
        $model1 = new TestingModel();
        $otherList->add($model1);
        $model2 = new TestingModel();
        $otherList->add($model2);

        $this->subject->append($otherList);

        self::assertSame(
            2,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function appendEmptyListToTwoItemListMakesTwoItemList()
    {
        $this->addModelsToFixture(['First', 'Second']);

        $otherList = new Collection();
        $this->subject->append($otherList);

        self::assertSame(
            2,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function appendOneItemListToOneItemListWithTheSameItemMakesOneItemList()
    {
        $model = new TestingModel();
        $model->setUid(42);
        $this->subject->add($model);

        $otherList = new Collection();
        $otherList->add($model);

        $this->subject->append($otherList);

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function appendTwoItemListKeepsOrderOfAppendedItems()
    {
        $otherList = new Collection();
        $model1 = new TestingModel();
        $otherList->add($model1);
        $model2 = new TestingModel();
        $otherList->add($model2);

        $this->subject->append($otherList);

        self::assertSame(
            $model1,
            $this->subject->first()
        );
    }

    /**
     * @test
     */
    public function appendAppendsItemAfterExistingItems()
    {
        $model = new TestingModel();
        $this->subject->add($model);

        $otherList = new Collection();
        $otherModel = new TestingModel();
        $otherList->add($otherModel);

        $this->subject->append($otherList);

        self::assertSame(
            $model,
            $this->subject->first()
        );
    }

    /**
     * @test
     *
     * @doesNotPerformAssertions
     */
    public function purgeCurrentWithEmptyListDoesNotFail()
    {
        $this->subject->purgeCurrent();
    }

    /**
     * @test
     */
    public function purgeCurrentWithRewoundOneElementListMakesListEmpty()
    {
        $this->addModelsToFixture();

        $this->subject->rewind();
        $this->subject->purgeCurrent();

        self::assertTrue(
            $this->subject->isEmpty()
        );
    }

    /**
     * @test
     */
    public function purgeCurrentWithRewoundOneElementListMakesPointerInvalid()
    {
        $this->addModelsToFixture();

        $this->subject->rewind();
        $this->subject->purgeCurrent();

        self::assertFalse(
            $this->subject->valid()
        );
    }

    /**
     * @test
     */
    public function purgeCurrentWithOneElementListAndPointerAfterLastItemLeavesListUntouched()
    {
        $this->addModelsToFixture();

        $this->subject->rewind();
        $this->subject->next();
        $this->subject->purgeCurrent();

        self::assertFalse(
            $this->subject->isEmpty()
        );
    }

    /**
     * @test
     */
    public function purgeCurrentForFirstOfTwoElementsMakesOneItemList()
    {
        $this->addModelsToFixture(['', '']);

        $this->subject->rewind();
        $this->subject->purgeCurrent();

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function purgeCurrentForSecondOfTwoElementsMakesOneItemList()
    {
        $this->addModelsToFixture(['', '']);

        $this->subject->rewind();
        $this->subject->next();
        $this->subject->purgeCurrent();

        self::assertSame(
            1,
            $this->subject->count()
        );
    }

    /**
     * @test
     */
    public function purgeCurrentForFirstOfTwoElementsSetsPointerToFormerSecondElement()
    {
        $this->addModelsToFixture();

        $model = new TestingModel();
        $this->subject->add($model);

        $this->subject->rewind();
        $this->subject->purgeCurrent();

        self::assertSame(
            $model,
            $this->subject->current()
        );
    }

    /**
     * @test
     */
    public function purgeCurrentForSecondOfTwoElementsInWhileLoopDoesNotChangeNumberOfIterations()
    {
        $this->addModelsToFixture(['', '']);

        $completedIterations = 0;

        while ($this->subject->valid()) {
            if ($completedIterations === 1) {
                $this->subject->purgeCurrent();
            }

            $completedIterations++;
            $this->subject->next();
        }

        self::assertSame(
            2,
            $completedIterations
        );
    }

    /**
     * @test
     */
    public function purgeCurrentForModelWithUidRemovesModelFromGetUids()
    {
        $model = new TestingModel();
        $model->setUid(1);
        $this->subject->add($model);

        $this->subject->rewind();
        $this->subject->purgeCurrent();

        self::assertSame(
            '',
            $this->subject->getUids()
        );
    }

    /**
     * @test
     */
    public function sortBySortingMovesItemWithHigherSortingValueAfterItemWithLowerSortingValue()
    {
        $model1 = new TestingChildModel();
        $model1->setSorting(2);
        $this->subject->add($model1);

        $model2 = new TestingChildModel();
        $model2->setSorting(1);
        $this->subject->add($model2);

        $this->subject->sortBySorting();

        self::assertSame(
            $model2,
            $this->subject->first()
        );
    }

    /**
     * @test
     */
    public function atForNegativePositionThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subject->at(-1);
    }

    /**
     * @test
     */
    public function atForPositionZeroWithEmptyListReturnsNull()
    {
        self::assertNull(
            $this->subject->at(0)
        );
    }

    /**
     * @test
     */
    public function atForPositionOneWithEmptyListReturnsNull()
    {
        self::assertNull(
            $this->subject->at(1)
        );
    }

    /**
     * @test
     */
    public function atForPositionZeroWithOneItemListReturnsItem()
    {
        $model = new TestingModel();
        $this->subject->add($model);

        self::assertSame(
            $model,
            $this->subject->at(0)
        );
    }

    /**
     * @test
     */
    public function atForPositionOneWithOneItemListReturnsNull()
    {
        $this->subject->add(new TestingModel());

        self::assertNull(
            $this->subject->at(1)
        );
    }

    /**
     * @test
     */
    public function atForPositionZeroWithTwoItemListReturnsFirstItem()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $this->subject->add(new TestingModel());

        self::assertSame(
            $model1,
            $this->subject->at(0)
        );
    }

    /**
     * @test
     */
    public function atForPositionOneWithTwoItemListReturnsSecondItem()
    {
        $this->subject->add(new TestingModel());
        $model2 = new TestingModel();
        $this->subject->add($model2);

        self::assertSame(
            $model2,
            $this->subject->at(1)
        );
    }

    /**
     * @test
     */
    public function atForPositionTwoWithTwoItemListReturnsNull()
    {
        $this->subject->add(new TestingModel());
        $this->subject->add(new TestingModel());

        self::assertNull(
            $this->subject->at(2)
        );
    }

    /**
     * @test
     */
    public function inRangeWithNegativeStartThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subject->inRange(-1, 1);
    }

    /**
     * @test
     */
    public function inRangeWithNegativeLengthThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->subject->inRange(1, -1);
    }

    /**
     * @test
     */
    public function inRangeWithZeroLengthReturnsEmptyList()
    {
        $this->subject->add(new TestingModel());
        $this->subject->add(new TestingModel());

        self::assertTrue(
            $this->subject->inRange(1, 0)->isEmpty()
        );
    }

    /**
     * @test
     */
    public function inRangeCanReturnOneElementFromStartOfList()
    {
        $model = new TestingModel();
        $this->subject->add($model);
        $this->subject->add(new TestingModel());

        $result = $this->subject->inRange(0, 1);
        self::assertSame(
            1,
            $result->count()
        );
        self::assertSame(
            $model,
            $result->first()
        );
    }

    /**
     * @test
     */
    public function inRangeCanReturnOneElementAfterStartOfList()
    {
        $model = new TestingModel();
        $this->subject->add(new TestingModel());
        $this->subject->add($model);

        $result = $this->subject->inRange(1, 1);
        self::assertSame(
            1,
            $result->count()
        );
        self::assertSame(
            $model,
            $result->first()
        );
    }

    /**
     * @test
     */
    public function inRangeCanReturnTwoElementsFromStartOfList()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $this->subject->add($model2);

        self::assertSame(
            2,
            $this->subject->inRange(0, 2)->count()
        );
    }

    /**
     * @test
     */
    public function inRangeWithStartAfterListEndReturnsEmptyList()
    {
        $this->subject->add(new TestingModel());

        self::assertTrue(
            $this->subject->inRange(1, 1)->isEmpty()
        );
    }

    /**
     * @test
     */
    public function inRangeWithRangeCrossingListEndReturnsElementUpToListEnd()
    {
        $this->subject->add(new TestingModel());
        $model = new TestingModel();
        $this->subject->add($model);

        $result = $this->subject->inRange(1, 2);

        self::assertSame(
            1,
            $result->count()
        );
        self::assertSame(
            $model,
            $result->first()
        );
    }

    /**
     * @test
     */
    public function toArrayForNoElementsReturnsEmptyArray()
    {
        self::assertSame(
            [],
            $this->subject->toArray()
        );
    }

    /**
     * @test
     */
    public function toArrayWithOneElementReturnsArrayWithElement()
    {
        $model = new TestingModel();
        $this->subject->add($model);

        self::assertSame(
            [$model],
            $this->subject->toArray()
        );
    }

    /**
     * @test
     */
    public function toArrayWithTwoElementsReturnsArrayWithBothElementsInAddingOrder()
    {
        $model1 = new TestingModel();
        $this->subject->add($model1);
        $model2 = new TestingModel();
        $this->subject->add($model2);

        self::assertSame(
            [$model1, $model2],
            $this->subject->toArray()
        );
    }

    /**
     * @test
     */
    public function parentModelByDefaultIsNull()
    {
        self::assertNull($this->subject->getParentModel());
    }

    /**
     * @test
     */
    public function setParentModelSetsParentModel()
    {
        $model = new TestingModel();
        $this->subject->setParentModel($model);

        self::assertSame(
            $model,
            $this->subject->getParentModel()
        );
    }

    /**
     * @test
     *
     * @doesNotPerformAssertions
     */
    public function addWithoutParentModelIsNoProblem()
    {
        $model = new TestingModel();
        $this->subject->add($model);
    }

    /**
     * @test
     */
    public function addWithoutParentModelMarksParentModelAsDirty()
    {
        $parentModel = new TestingModel();
        self::assertFalse($parentModel->isDirty());
        $this->subject->setParentModel($parentModel);

        $model = new TestingModel();
        $this->subject->add($model);

        self::assertTrue($parentModel->isDirty());
    }

    /**
     * @test
     */
    public function isRelationOwnedByParentByDefaultIsFalse()
    {
        self::assertFalse($this->subject->isRelationOwnedByParent());
    }

    /**
     * @test
     */
    public function isRelationOwnedByParentCanBeSetToTrue()
    {
        $this->subject->markAsOwnedByParent();

        self::assertTrue($this->subject->isRelationOwnedByParent());
    }
}
