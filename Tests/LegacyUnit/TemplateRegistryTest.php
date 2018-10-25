<?php

/**
 * Test case.
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 */
class Tx_Oelib_Tests_LegacyUnit_TemplateRegistryTest extends \Tx_Phpunit_TestCase
{
    ////////////////////////////////////////////
    // Tests concerning the Singleton property
    ////////////////////////////////////////////

    /**
     * @test
     */
    public function getInstanceReturnsTemplateRegistryInstance()
    {
        self::assertInstanceOf(
            \Tx_Oelib_TemplateRegistry::class,
            \Tx_Oelib_TemplateRegistry::getInstance()
        );
    }

    /**
     * @test
     */
    public function getInstanceTwoTimesReturnsSameInstance()
    {
        self::assertSame(
            \Tx_Oelib_TemplateRegistry::getInstance(),
            \Tx_Oelib_TemplateRegistry::getInstance()
        );
    }

    /**
     * @test
     */
    public function getInstanceAfterPurgeInstanceReturnsNewInstance()
    {
        $firstInstance = \Tx_Oelib_TemplateRegistry::getInstance();
        \Tx_Oelib_TemplateRegistry::purgeInstance();

        self::assertNotSame(
            $firstInstance,
            \Tx_Oelib_TemplateRegistry::getInstance()
        );
    }

    ///////////////////////////
    // Tests concerning get()
    ///////////////////////////

    /**
     * @test
     */
    public function getForEmptyTemplateFileNameReturnsTemplateInstance()
    {
        self::assertInstanceOf(
            \Tx_Oelib_Template::class,
            \Tx_Oelib_TemplateRegistry::get('')
        );
    }

    /**
     * @test
     */
    public function getForEmptyTemplateFileNameCalledTwoTimesReturnsNewInstance()
    {
        self::assertNotSame(
            \Tx_Oelib_TemplateRegistry::get(''),
            \Tx_Oelib_TemplateRegistry::get('')
        );
    }

    /**
     * @test
     */
    public function getForExistingTemplateFileNameReturnsTemplate()
    {
        self::assertInstanceOf(
            \Tx_Oelib_Template::class,
            \Tx_Oelib_TemplateRegistry::get('EXT:oelib/Tests/LegacyUnit/Fixtures/oelib.html')
        );
    }

    /**
     * @test
     */
    public function getForExistingTemplateFileNameCalledTwoTimesReturnsNewInstance()
    {
        self::assertNotSame(
            \Tx_Oelib_TemplateRegistry::get('EXT:oelib/Tests/LegacyUnit/Fixtures/oelib.html'),
            \Tx_Oelib_TemplateRegistry::get('EXT:oelib/Tests/LegacyUnit/Fixtures/oelib.html')
        );
    }

    /**
     * @test
     */
    public function getForExistingTemplateFileNameReturnsProcessedTemplate()
    {
        $template = \Tx_Oelib_TemplateRegistry::get('EXT:oelib/Tests/LegacyUnit/Fixtures/oelib.html');

        self::assertSame(
            'Hello world!' . LF,
            $template->getSubpart()
        );
    }
}
