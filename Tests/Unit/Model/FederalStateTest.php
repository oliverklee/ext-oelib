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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_Model_FederalStateTest extends Tx_Phpunit_TestCase
{
    protected function tearDown()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        Tx_Oelib_MapperRegistry::purgeInstance();
    }

    /*
     * Tests regarding getting the local name
     */

    /**
     * @test
     */
    public function getLocalNameReturnsLocalNameOfNorthRhineWestphalia()
    {
        /** @var Tx_Oelib_Mapper_FederalState $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FederalState::class);
        /** @var Tx_Oelib_Model_FederalState $model */
        $model = $mapper->find(88);

        self::assertSame(
            'Nordrhein-Westfalen',
            $model->getLocalName()
        );
    }

    /*
     * Tests regarding getting the English name
     */

    /**
     * @test
     */
    public function getEnglishNameReturnsLocalNameOfNorthRhineWestphalia()
    {
        /** @var Tx_Oelib_Mapper_FederalState $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FederalState::class);
        /** @var Tx_Oelib_Model_FederalState $model */
        $model = $mapper->find(88);

        self::assertSame(
            'North Rhine-Westphalia',
            $model->getEnglishName()
        );
    }

    /*
     * Tests regarding getting the ISO alpha-2 code
     */

    /**
     * @test
     */
    public function getIsoAlpha2CodeReturnsIsoAlpha2CodeOfNorthRhineWestphalia()
    {
        /** @var Tx_Oelib_Mapper_FederalState $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FederalState::class);
        /** @var Tx_Oelib_Model_FederalState $model */
        $model = $mapper->find(88);

        self::assertSame(
            'DE',
            $model->getIsoAlpha2Code()
        );
    }

    /**
     * @test
     */
    public function getIsoAlpha2ZoneCodeReturnsIsoAlpha2ZoneCodeOfNorthRhineWestphalia()
    {
        /** @var Tx_Oelib_Mapper_FederalState $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FederalState::class);
        /** @var Tx_Oelib_Model_FederalState $model */
        $model = $mapper->find(88);

        self::assertSame(
            'NW',
            $model->getIsoAlpha2ZoneCode()
        );
    }

    /*
     * Tests concerning isReadOnly
     */

    /**
     * @test
     */
    public function isReadOnlyIsTrue()
    {
        $model = new Tx_Oelib_Model_FederalState();

        self::assertTrue(
            $model->isReadOnly()
        );
    }
}
