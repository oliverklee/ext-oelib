<?php

/**
 * Test case.
 *
 * @author Bernd Schönbach <bernd@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_PageFinderTest extends \Tx_Phpunit_TestCase
{
    /**
     * @var \Tx_Oelib_TestingFramework
     */
    private $testingFramework;

    /**
     * @var \Tx_Oelib_PageFinder
     */
    private $subject;

    protected function setUp()
    {
        $this->testingFramework = new \Tx_Oelib_TestingFramework('tx_oelib');
        $this->subject = \Tx_Oelib_PageFinder::getInstance();
    }

    protected function tearDown()
    {
        $this->testingFramework->cleanUp();
    }

    ////////////////////////////////////////////
    // Tests concerning the Singleton property
    ////////////////////////////////////////////

    /**
     * @test
     */
    public function getInstanceReturnsPageFinderInstance()
    {
        self::assertInstanceOf(
            \Tx_Oelib_PageFinder::class,
            \Tx_Oelib_PageFinder::getInstance()
        );
    }

    /**
     * @test
     */
    public function getInstanceTwoTimesReturnsSameInstance()
    {
        self::assertSame(
            \Tx_Oelib_PageFinder::getInstance(),
            \Tx_Oelib_PageFinder::getInstance()
        );
    }

    /**
     * @test
     */
    public function getInstanceAfterPurgeInstanceReturnsNewInstance()
    {
        $firstInstance = \Tx_Oelib_PageFinder::getInstance();
        \Tx_Oelib_PageFinder::purgeInstance();

        self::assertNotSame(
            $firstInstance,
            \Tx_Oelib_PageFinder::getInstance()
        );
    }

    ////////////////////////////////
    // Tests concerning getPageUid
    ////////////////////////////////

    /**
     * @test
     */
    public function getPageUidWithFrontEndPageUidReturnsFrontEndPageUid()
    {
        $pageUid = $this->testingFramework->createFakeFrontEnd();

        self::assertSame(
            $pageUid,
            $this->subject->getPageUid()
        );
    }

    /**
     * @test
     */
    public function getPageUidWithoutFrontEndAndWithBackendPageUidReturnsBackEndPageUid()
    {
        $_POST['id'] = 42;

        $pageUid = $this->subject->getPageUid();
        unset($_POST['id']);

        self::assertSame(
            42,
            $pageUid
        );
    }

    /**
     * @test
     */
    public function getPageUidWithFrontEndAndBackendPageUidReturnsFrontEndPageUid()
    {
        $frontEndPageUid = $this->testingFramework->createFakeFrontEnd();

        $_POST['id'] = $frontEndPageUid + 1;

        $pageUid = $this->subject->getPageUid();

        unset($_POST['id']);

        self::assertSame(
            $frontEndPageUid,
            $pageUid
        );
    }

    /**
     * @test
     */
    public function getPageUidForManuallySetPageUidAndSetFrontEndPageUidReturnsManuallySetPageUid()
    {
        $frontEndPageUid = $this->testingFramework->createFakeFrontEnd();
        $this->subject->setPageUid($frontEndPageUid + 1);

        self::assertSame(
            $frontEndPageUid + 1,
            $this->subject->getPageUid()
        );
    }

    ////////////////////////////////
    // tests concerning setPageUid
    ////////////////////////////////

    /**
     * @test
     */
    public function getPageUidWithSetPageUidViaSetPageUidReturnsSetPageUid()
    {
        $this->subject->setPageUid(42);

        self::assertSame(
            42,
            $this->subject->getPageUid()
        );
    }

    /**
     * @test
     */
    public function setPageUidWithZeroGivenThrowsException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The given page UID was "0". Only integer values greater than zero are allowed.'
        );

        $this->subject->setPageUid(0);
    }

    /**
     * @test
     */
    public function setPageUidWithNegativeNumberGivenThrowsException()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The given page UID was "-21". Only integer values greater than zero are allowed.'
        );

        $this->subject->setPageUid(-21);
    }

    /////////////////////////////////
    // Tests concerning forceSource
    /////////////////////////////////

    /**
     * @test
     */
    public function forceSourceWithSourceSetToFrontEndAndManuallySetPageUidReturnsFrontEndPageUid()
    {
        $this->subject->forceSource(\Tx_Oelib_PageFinder::SOURCE_FRONT_END);
        $frontEndPageUid = $this->testingFramework->createFakeFrontEnd();

        $this->subject->setPageUid($frontEndPageUid + 1);

        self::assertSame(
            $frontEndPageUid,
            $this->subject->getPageUid()
        );
    }

    /**
     * @test
     */
    public function forceSourceWithSourceSetToBackEndAndSetFrontEndUidReturnsBackEndEndPageUid()
    {
        $this->subject->forceSource(\Tx_Oelib_PageFinder::SOURCE_BACK_END);
        $this->testingFramework->createFakeFrontEnd();

        $_POST['id'] = 42;
        $pageUid = $this->subject->getPageUid();
        unset($_POST['id']);

        self::assertSame(
            42,
            $pageUid
        );
    }

    /**
     * @test
     */
    public function forceSourceWithSourceSetToFrontEndAndManuallySetPageUidButNoFrontEndUidSetReturnsZero()
    {
        $this->subject->forceSource(\Tx_Oelib_PageFinder::SOURCE_FRONT_END);

        $this->subject->setPageUid(15);

        self::assertSame(
            0,
            $this->subject->getPageUid()
        );
    }

    //////////////////////////////////////
    // Tests concerning getCurrentSource
    //////////////////////////////////////

    /**
     * @test
     */
    public function getCurrentSourceForNoSourceForcedAndNoPageUidSetReturnsNoSourceFound()
    {
        self::assertSame(
            \Tx_Oelib_PageFinder::NO_SOURCE_FOUND,
            $this->subject->getCurrentSource()
        );
    }

    /**
     * @test
     */
    public function getCurrentSourceForSourceForcedToFrontEndReturnsSourceFrontEnd()
    {
        $this->subject->forceSource(\Tx_Oelib_PageFinder::SOURCE_FRONT_END);

        self::assertSame(
            \Tx_Oelib_PageFinder::SOURCE_FRONT_END,
            $this->subject->getCurrentSource()
        );
    }

    /**
     * @test
     */
    public function getCurrentSourceForSourceForcedToBackEndReturnsSourceBackEnd()
    {
        $this->subject->forceSource(\Tx_Oelib_PageFinder::SOURCE_BACK_END);

        self::assertSame(
            \Tx_Oelib_PageFinder::SOURCE_BACK_END,
            $this->subject->getCurrentSource()
        );
    }

    /**
     * @test
     */
    public function getCurrentSourceForManuallySetPageIdReturnsSourceManual()
    {
        $this->subject->setPageUid(42);

        self::assertSame(
            \Tx_Oelib_PageFinder::SOURCE_MANUAL,
            $this->subject->getCurrentSource()
        );
    }

    /**
     * @test
     */
    public function getCurrentSourceForSetFrontEndPageUidReturnsSourceFrontEnd()
    {
        $this->testingFramework->createFakeFrontEnd();

        self::assertSame(
            \Tx_Oelib_PageFinder::SOURCE_FRONT_END,
            $this->subject->getCurrentSource()
        );
    }

    /**
     * @test
     */
    public function getCurrentSourceForSetBackEndPageUidReturnsSourceBackEnd()
    {
        $_POST['id'] = 42;
        $pageSource = $this->subject->getCurrentSource();
        unset($_POST['id']);

        self::assertSame(
            \Tx_Oelib_PageFinder::SOURCE_BACK_END,
            $pageSource
        );
    }
}
