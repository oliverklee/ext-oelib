<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Configuration;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use OliverKlee\Oelib\Configuration\ConfigurationCheck;
use OliverKlee\Oelib\Testing\TestingFramework;
use OliverKlee\Oelib\Tests\Unit\Configuration\Fixtures\DummyObjectToCheck;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ConfigurationCheckTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    /**
     * @var TestingFramework
     */
    private $testingFramework = null;

    /**
     * @var ConfigurationCheck configuration check object to be tested
     */
    private $subject = null;

    protected function setUp()
    {
        parent::setUp();

        $this->testingFramework = new TestingFramework('tx_oelib');
        $this->testingFramework->createFakeFrontEnd($this->testingFramework->createFrontEndPage());

        $objectToCheck = new DummyObjectToCheck(
            [
                'emptyString' => '',
                'nonEmptyString' => 'foo',
                'validEmail' => 'any-address@valid-email.org',
                'existingColumn' => 'title',
                'inexistentColumn' => 'does_not_exist',
            ]
        );
        $this->subject = new ConfigurationCheck($objectToCheck);
    }

    protected function tearDown()
    {
        $this->testingFramework->cleanUpWithoutDatabase();

        parent::tearDown();
    }

    /*
     * Utility functions
     */

    /**
     * Returns the current front-end instance.
     *
     * @return TypoScriptFrontendController
     */
    private function getFrontEndController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * Sets the configuration value for the locale to $localeKey.
     *
     * @param string $localeKey
     *        key for the locale, to receive a non-configured locale, provide
     *        an empty string
     *
     * @return void
     */
    private function setConfigurationForLocale(string $localeKey)
    {
        $this->getFrontEndController()->config['config']['locale_all'] = $localeKey;
    }

    /*
     * Tests for the utility functions
     */

    /**
     * @test
     */
    public function setConfigurationForLocaleToANonEmptyValue()
    {
        $this->setConfigurationForLocale('foo');

        self::assertSame('foo', $this->getFrontEndController()->config['config']['locale_all']);
    }

    /**
     * @test
     */
    public function setConfigurationForLocaleToAnEmptyString()
    {
        $this->setConfigurationForLocale('');

        self::assertSame('', $this->getFrontEndController()->config['config']['locale_all']);
    }

    /*
     * Tests concerning values to check
     */

    /**
     * @test
     */
    public function checkIfSingleInTableNotEmptyForValueNotInTableComplains()
    {
        $this->subject->checkIfSingleInTableNotEmpty(
            'inexistentColumn',
            false,
            '',
            '',
            'tx_oelib_test'
        );

        self::assertContains('inexistentColumn', $this->subject->getRawMessage());
    }

    /**
     * @test
     */
    public function checkIfSingleInTableNotEmptyForValueNotInTableNotComplains()
    {
        $this->subject->checkIfSingleInTableNotEmpty(
            'existingColumn',
            false,
            '',
            '',
            'tx_oelib_test'
        );

        self::assertSame('', $this->subject->getRawMessage());
    }
}
