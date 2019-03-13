<?php

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Functional_Domain_Repository_GermanZipCodeRepositoryTest extends \Tx_Phpunit_TestCase
{
    /**
     * @var \Tx_Oelib_Domain_Repository_GermanZipCodeRepository
     */
    private $subject = null;

    protected function setUp()
    {
        parent::setUp();

        /** @var \Tx_Extbase_Object_ObjectManager $objectManager */
        $objectManager = \t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
        $this->subject = $objectManager->get('Tx_Oelib_Domain_Repository_GermanZipCodeRepository');
    }

    /**
     * @test
     */
    public function mapsAllModelFields()
    {
        /** @var \Tx_Oelib_Domain_Model_GermanZipCode $result */
        $result = $this->subject->findByUid(1);

        static::assertInstanceOf('Tx_Oelib_Domain_Model_GermanZipCode', $result);
        static::assertSame('01067', $result->getZipCode());
        static::assertSame('Dresden', $result->getCityName());
        static::assertEquals(13.721068, $result->getLongitude());
        static::assertEquals(51.060036, $result->getLatitude());
    }

    /**
     * @test
     */
    public function findOneByZipCodeWithMatchReturnsMatch()
    {
        $zipCode = '01067';
        /** @var \Tx_Oelib_Domain_Model_GermanZipCode $result */
        $result = $this->subject->findOneByZipCode($zipCode);

        static::assertInstanceOf('Tx_Oelib_Domain_Model_GermanZipCode', $result);
        static::assertSame($zipCode, $result->getZipCode());
        static::assertSame('Dresden', $result->getCityName());
    }

    /**
     * @test
     */
    public function findOneByZipCodeWithMatchCalledTwoTimesReturnsTheSameModel()
    {
        $zipCode = '01067';
        $firstResult = $this->subject->findOneByZipCode($zipCode);
        $secondResult = $this->subject->findOneByZipCode($zipCode);

        static::assertSame($firstResult, $secondResult);
    }

    /**
     * @return string[][]
     */
    public function nonMatchedZipCodesDataProvider()
    {
        return array(
            '5 digits without match' => array('00000'),
            '5 letters' => array('av3sd'),
            '4 digits' => array('1233'),
            '6 digits' => array('463726'),
            'empty string' => array(''),
        );
    }

    /**
     * @test
     *
     * @param string $zipCode
     *
     * @dataProvider nonMatchedZipCodesDataProvider
     */
    public function findOneByZipCodeWithoutMatchReturnsNull($zipCode)
    {
        $result = $this->subject->findOneByZipCode($zipCode);

        static::assertNull($result);
    }
}
