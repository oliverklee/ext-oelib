<?php

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_Domain_Model_GermanZipCodeTest extends \Tx_Phpunit_TestCase
{
    /**
     * @var \Tx_Oelib_Domain_Model_GermanZipCode
     */
    private $subject = null;

    protected function setUp()
    {
        $this->subject = new \Tx_Oelib_Domain_Model_GermanZipCode();
    }

    /**
     * @test
     */
    public function isAbstractEntity()
    {
        static::assertInstanceOf('Tx_Extbase_DomainObject_AbstractEntity', $this->subject);
    }

    /**
     * @test
     */
    public function isGeo()
    {
        static::assertInstanceOf('tx_oelib_Interface_Geo', $this->subject);
    }

    /**
     * @test
     */
    public function getZipCodeInitiallyReturnsEmptyString()
    {
        static::assertSame('', $this->subject->getZipCode());
    }

    /**
     * @test
     */
    public function setZipCodeSetsZipCode()
    {
        $value = '01234';
        $this->subject->setZipCode($value);

        static::assertSame($value, $this->subject->getZipCode());
    }

    /**
     * @test
     */
    public function getCityNameInitiallyReturnsEmptyString()
    {
        static::assertSame('', $this->subject->getCityName());
    }

    /**
     * @test
     */
    public function setCityNameSetsCityName()
    {
        $value = 'Köln';
        $this->subject->setCityName($value);

        static::assertSame($value, $this->subject->getCityName());
    }

    /**
     * @test
     */
    public function getGeoAddressReturnsZipCodeAndCityAndGermany()
    {
        $this->subject->setZipCode('53173');
        $this->subject->setCityName('Bonn');

        static::assertSame('53173 Bonn, DE', $this->subject->getGeoAddress());
    }

    /**
     * @test
     */
    public function hasGeoAddressWithEmptyDataReturnsTrue()
    {
        static::assertTrue($this->subject->hasGeoAddress());
    }

    /**
     * @test
     */
    public function hasGeoAddressWithZipCodeReturnsTrue()
    {
        $this->subject->setZipCode('53173');

        static::assertTrue($this->subject->hasGeoAddress());
    }

    /**
     * @test
     */
    public function hasGeoAddressWithCityNameReturnsTrue()
    {
        $this->subject->setCityName('Bonn');

        static::assertTrue($this->subject->hasGeoAddress());
    }

    /**
     * @test
     */
    public function getLongitudeInitiallyReturnsZero()
    {
        static::assertSame(0.0, $this->subject->getLongitude());
    }

    /**
     * @test
     */
    public function setLongitudeSetsLongitude()
    {
        $value = 1234.56;
        $this->subject->setLongitude($value);

        static::assertSame($value, $this->subject->getLongitude());
    }

    /**
     * @test
     */
    public function getLatitudeInitiallyReturnsZero()
    {
        static::assertSame(0.0, $this->subject->getLatitude());
    }

    /**
     * @test
     */
    public function setLatitudeSetsLatitude()
    {
        $value = 1234.56;
        $this->subject->setLatitude($value);

        static::assertSame($value, $this->subject->getLatitude());
    }

    /**
     * @test
     */
    public function getGeoCoordinatesReturnsCoordinates()
    {
        $latitude = 12.234;
        $this->subject->setLatitude($latitude);
        $longitude = 1.235;
        $this->subject->setLongitude($longitude);

        static::assertSame(['latitude' => $latitude, 'longitude' => $longitude], $this->subject->getGeoCoordinates());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function setGeoCoordinatesAlwaysThrowsException()
    {
        $this->subject->setGeoCoordinates(['latitude' => 1.23, 'longitude' => 0.123]);
    }

    /**
     * @test
     */
    public function hasGeoCoordinatesAlwaysReturnsTrue()
    {
        static::assertTrue($this->subject->hasGeoCoordinates());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function clearGeoCoordinatesAlwaysThrowsException()
    {
        $this->subject->clearGeoCoordinates();
    }

    /**
     * @test
     */
    public function hasGeoErrorAlwaysReturnsFalse()
    {
        static::assertFalse($this->subject->hasGeoError());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function clearGeoErrorAlwaysThrowsException()
    {
        $this->subject->clearGeoError();
    }
}
