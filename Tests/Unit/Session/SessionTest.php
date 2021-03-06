<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Session;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Session\FakeSession;
use OliverKlee\Oelib\Session\Session;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class SessionTest extends UnitTestCase
{
    protected function tearDown()
    {
        $GLOBALS['TSFE'] = null;
        parent::tearDown();
    }

    private function createFakeFrontEnd()
    {
        $GLOBALS['TSFE'] = $this->prophesize(TypoScriptFrontendController::class)->reveal();
    }

    /**
     * @test
     */
    public function getInstanceThrowsExceptionWithoutFrontEnd()
    {
        $this->expectException(
            \BadMethodCallException::class
        );
        $this->expectExceptionMessage(
            'This class must not be instantiated when there is no front end.'
        );

        $GLOBALS['TSFE'] = null;

        Session::getInstance(Session::TYPE_USER);
    }

    /**
     * @test
     */
    public function getInstanceWithInvalidTypeThrowsException()
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'Only the types ::TYPE_USER and ::TYPE_TEMPORARY are allowed.'
        );

        $this->createFakeFrontEnd();

        Session::getInstance(42);
    }

    /**
     * @test
     */
    public function getInstanceWithUserTypeReturnsSessionInstance()
    {
        $this->createFakeFrontEnd();

        self::assertInstanceOf(
            Session::class,
            Session::getInstance(Session::TYPE_USER)
        );
    }

    /**
     * @test
     */
    public function getInstanceWithTemporaryTypeReturnsSessionInstance()
    {
        $this->createFakeFrontEnd();

        self::assertInstanceOf(
            Session::class,
            Session::getInstance(Session::TYPE_TEMPORARY)
        );
    }

    /**
     * @test
     */
    public function getInstanceWithSameTypeReturnsSameInstance()
    {
        $this->createFakeFrontEnd();

        self::assertSame(
            Session::getInstance(Session::TYPE_USER),
            Session::getInstance(Session::TYPE_USER)
        );
    }

    /**
     * @test
     */
    public function getInstanceWithDifferentTypesReturnsDifferentInstance()
    {
        $this->createFakeFrontEnd();

        self::assertNotSame(
            Session::getInstance(Session::TYPE_USER),
            Session::getInstance(Session::TYPE_TEMPORARY)
        );
    }

    /**
     * @test
     */
    public function getInstanceWithSameTypesAfterPurgeInstancesReturnsNewInstance()
    {
        $this->createFakeFrontEnd();
        $firstInstance = Session::getInstance(Session::TYPE_USER);
        Session::purgeInstances();

        self::assertNotSame(
            $firstInstance,
            Session::getInstance(Session::TYPE_USER)
        );
    }

    /**
     * @test
     */
    public function setInstanceWithInvalidTypeThrowsException()
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'Only the types ::TYPE_USER and ::TYPE_TEMPORARY are allowed.'
        );

        Session::setInstance(42, new FakeSession());
    }

    /**
     * @test
     */
    public function getInstanceWithUserTypeReturnsInstanceFromSetInstance()
    {
        $instance = new FakeSession();
        Session::setInstance(Session::TYPE_USER, $instance);

        self::assertSame(
            $instance,
            Session::getInstance(Session::TYPE_USER)
        );
    }

    /**
     * @test
     */
    public function getInstanceWithTemporaryTypeReturnsInstanceFromSetInstance()
    {
        $instance = new FakeSession();
        Session::setInstance(
            Session::TYPE_TEMPORARY,
            $instance
        );

        self::assertSame(
            $instance,
            Session::getInstance(Session::TYPE_TEMPORARY)
        );
    }

    /**
     * @test
     */
    public function getInstanceWithDifferentTypesReturnsDifferentInstancesSetViaSetInstance()
    {
        Session::setInstance(
            Session::TYPE_USER,
            new FakeSession()
        );
        Session::setInstance(
            Session::TYPE_TEMPORARY,
            new FakeSession()
        );

        self::assertNotSame(
            Session::getInstance(Session::TYPE_USER),
            Session::getInstance(Session::TYPE_TEMPORARY)
        );
    }
}
