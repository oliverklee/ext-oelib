<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Test case.
 *
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Functional_DataMapperTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_Oelib_TestingFramework
     */
    protected $testingFramework = null;
    /**
     * @var Tx_Oelib_Tests_Unit_Fixtures_TestingMapper
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->testingFramework = new Tx_Oelib_TestingFramework('tx_oelib');

        Tx_Oelib_MapperRegistry::getInstance()->activateTestingMode($this->testingFramework);

        $this->subject = Tx_Oelib_MapperRegistry::get(\Tx_Oelib_Tests_Unit_Fixtures_TestingMapper::class);
    }

    protected function tearDown()
    {
        $this->testingFramework->cleanUp();

        Tx_Oelib_MapperRegistry::purgeInstance();
    }

    /*
     * Tests concerning load
     */

    /**
     * @test
     */
    public function loadWithModelWithExistingUidFillsModelWithData()
    {
        $title = 'Assassin of Kings';
        $uid = $this->testingFramework->createRecord(
            'tx_oelib_test',
            ['title' => $title]
        );

        $model = new Tx_Oelib_Tests_Unit_Fixtures_TestingModel();
        $model->setUid($uid);
        $this->subject->load($model);

        self::assertSame(
            $title,
            $model->getTitle()
        );
    }

    /*
     * Tests concerning find
     */

    /**
     * @test
     */
    public function findWithUidOfExistingRecordReturnsModelDataFromDatabase()
    {
        $uid = $this->testingFramework->createRecord(
            'tx_oelib_test',
            ['title' => 'foo']
        );

        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $model */
        $model = $this->subject->find($uid);
        self::assertSame(
            'foo',
            $model->getTitle()
        );
    }

    /*
     * Tests concerning n:1 association mapping
     */

    /**
     * @test
     */
    public function relatedRecordWithExistingUidReturnsRelatedRecordWithData()
    {
        $friendTitle = 'Brianna';
        $friendUid = $this->testingFramework->createRecord('tx_oelib_test', ['title' => $friendTitle]);
        $uid = $this->testingFramework->createRecord(
            'tx_oelib_test',
            ['friend' => $friendUid]
        );

        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $model */
        $model = $this->subject->find($uid);
        self::assertSame(
            $friendTitle,
            $model->getFriend()->getTitle()
        );
    }

    /*
     * Tests concerning the m:n mapping with a comma-separated list of UIDs
     */

    /**
     * @test
     */
    public function commaSeparatedRelationsWithOneUidReturnsListWithRelatedModelWithData()
    {
        $childTitle = 'Abraham';
        $childUid = $this->testingFramework->createRecord('tx_oelib_test', ['title' => $childTitle]);
        $uid = $this->testingFramework->createRecord(
            'tx_oelib_test',
            ['children' => (string)$childUid]
        );

        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $model */
        $model = $this->subject->find($uid);
        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $firstChild */
        $firstChild = $model->getChildren()->first();
        self::assertSame(
            $childTitle,
            $firstChild->getTitle()
        );
    }

    /*
     * Tests concerning the m:n mapping using an m:n table
     */

    /**
     * @test
     */
    public function mnRelationsWithOneRelatedModelReturnsListWithRelatedModelWithData()
    {
        $relatedTitle = 'Geralt of Rivia';
        $uid = $this->testingFramework->createRecord('tx_oelib_test');
        $relatedUid = $this->testingFramework->createRecord('tx_oelib_test', ['title' => $relatedTitle]);
        $this->testingFramework->createRelationAndUpdateCounter(
            'tx_oelib_test',
            $uid,
            $relatedUid,
            'related_records'
        );

        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $model */
        $model = $this->subject->find($uid);
        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $firstRelatedModel */
        $firstRelatedModel = $model->getRelatedRecords()->first();
        self::assertSame(
            $relatedTitle,
            $firstRelatedModel->getTitle()
        );
    }

    /*
     * Tests concerning the bidirectional m:n mapping using an m:n table.
     */

    /**
     * @test
     */
    public function bidirectionalMNRelationsWithOneRelatedModelReturnsListWithRelatedModelWithData()
    {
        $uid = $this->testingFramework->createRecord('tx_oelib_test');
        $relatedUid = $this->testingFramework->createRecord('tx_oelib_test');
        $this->testingFramework->createRelationAndUpdateCounter(
            'tx_oelib_test',
            $relatedUid,
            $uid,
            'bidirectional'
        );

        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $model */
        $model = $this->subject->find($relatedUid);
        self::assertSame(
            (string)$uid,
            $model->getBidirectional()->getUids()
        );
    }

    /*
     * Tests concerning the 1:n mapping using a foreign field.
     */

    /**
     * @test
     */
    public function oneToManyRelationsWithOneRelatedModelReturnsListWithRelatedModelWithData()
    {
        $relatedTitle = 'Triss Merrigold';
        $uid = $this->testingFramework->createRecord(
            'tx_oelib_test',
            ['composition' => 1]
        );
        $this->testingFramework->createRecord(
            'tx_oelib_testchild',
            ['parent' => $uid, 'title' => $relatedTitle]
        );

        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $model */
        $model = $this->subject->find($uid);
        /** @var Tx_Oelib_Tests_Unit_Fixtures_TestingModel $firstChildModel */
        $firstChildModel = $model->getComposition()->first();
        self::assertSame(
            $relatedTitle,
            $firstChildModel->getTitle()
        );
    }
}
