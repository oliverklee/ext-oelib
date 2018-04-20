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
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_Geocoding_DummyTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_Oelib_Geocoding_Dummy
     */
    private $subject;

    protected function setUp()
    {
        $this->subject = new Tx_Oelib_Geocoding_Dummy();
    }

    /////////////////////
    // Tests for lookUp
    /////////////////////

    /**
     * @test
     */
    public function lookUpForEmptyAddressSetsCoordinatesError()
    {
        $geo = $this->getMock(
            \Tx_Oelib_Tests_Unit_Fixtures_TestingGeo::class,
            ['setGeoError']
        );
        $geo->expects(self::once())->method('setGeoError');

        /* @var Tx_Oelib_Tests_Unit_Fixtures_TestingGeo $geo */
        $this->subject->lookUp($geo);
    }

    /**
     * @test
     */
    public function lookUpForAFullGermanAddressSetsCoordinatesFromSetCoordinates()
    {
        $coordinates = ['latitude' => 50.7335500, 'longitude' => 7.1014300];
        $this->subject->setCoordinates(
            $coordinates['latitude'],
            $coordinates['longitude']
        );

        $geo = new Tx_Oelib_Tests_Unit_Fixtures_TestingGeo();
        $geo->setGeoAddress('Am Hof 1, 53113 Zentrum, Bonn, DE');

        $this->subject->lookUp($geo);

        self::assertSame(
            $coordinates,
            $geo->getGeoCoordinates()
        );
    }

    /**
     * @test
     */
    public function lookUpForAFullGermanAddressWithoutSetCoordinatesSetsNoCoordinates()
    {
        $geo = new Tx_Oelib_Tests_Unit_Fixtures_TestingGeo();
        $geo->setGeoAddress('Am Hof 1, 53113 Zentrum, Bonn, DE');

        $this->subject->lookUp($geo);

        self::assertFalse(
            $geo->hasGeoCoordinates()
        );
    }

    /**
     * @test
     */
    public function lookUpForAFullGermanAddressWithoutSetCoordinatesNotClearsExistingCoordinates()
    {
        $geo = new Tx_Oelib_Tests_Unit_Fixtures_TestingGeo();
        $geo->setGeoAddress('Am Hof 1, 53113 Zentrum, Bonn, DE');

        $this->subject->lookUp($geo);

        self::assertFalse(
            $geo->hasGeoCoordinates()
        );
    }

    /**
     * @test
     */
    public function lookUpForAFullGermanAddressWithoutSetCoordinatesSetsGeoError()
    {
        $geo = new Tx_Oelib_Tests_Unit_Fixtures_TestingGeo();
        $geo->setGeoAddress('Am Hof 1, 53113 Zentrum, Bonn, DE');

        $this->subject->lookUp($geo);

        self::assertTrue(
            $geo->hasGeoError()
        );
    }

    /**
     * @test
     */
    public function lookUpForAFullGermanAddressWithCoordinatesNotOverwritesCoordinates()
    {
        $this->subject->setCoordinates(42.0, 42.0);

        $coordinates = ['latitude' => 50.7335500, 'longitude' => 7.1014300];
        $geo = new Tx_Oelib_Tests_Unit_Fixtures_TestingGeo();
        $geo->setGeoAddress('Am Hof 1, 53113 Zentrum, Bonn, DE');
        $geo->setGeoCoordinates($coordinates);

        $this->subject->lookUp($geo);

        self::assertSame(
            $coordinates,
            $geo->getGeoCoordinates()
        );
    }

    /**
     * @test
     */
    public function lookUpAfterClearCoordinatesSetsNoCoordinates()
    {
        $this->subject->setCoordinates(42.0, 42.0);
        $this->subject->clearCoordinates();

        $geo = new Tx_Oelib_Tests_Unit_Fixtures_TestingGeo();
        $geo->setGeoAddress('Am Hof 1, 53113 Zentrum, Bonn, DE');

        $this->subject->lookUp($geo);

        self::assertFalse(
            $geo->hasGeoCoordinates()
        );
    }
}
