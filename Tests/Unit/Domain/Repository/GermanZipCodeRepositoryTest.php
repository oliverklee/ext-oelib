<?php

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_Domain_Repository_GermanZipCodeRepositoryTest extends \Tx_Phpunit_TestCase
{
    /**
     * @var \Tx_Oelib_Domain_Repository_GermanZipCodeRepository
     */
    private $subject = null;

    protected function setUp()
    {
        /** @var \Tx_Extbase_Object_ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject $objectManagerStub */
        $objectManagerStub = $this->getMock('Tx_Extbase_Object_ObjectManagerInterface', array());
        $this->subject = new \Tx_Oelib_Domain_Repository_GermanZipCodeRepository($objectManagerStub);
    }

    /**
     * @test
     */
    public function isRepository()
    {
        static::assertInstanceOf('Tx_Extbase_Persistence_Repository', $this->subject);
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function addThrowsException()
    {
        $this->subject->add(new \Tx_Oelib_Domain_Model_GermanZipCode());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function removeThrowsException()
    {
        $this->subject->remove(new \Tx_Oelib_Domain_Model_GermanZipCode());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function updateThrowsException()
    {
        $this->subject->update(new \Tx_Oelib_Domain_Model_GermanZipCode());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function removeAllThrowsException()
    {
        $this->subject->removeAll();
    }
}
