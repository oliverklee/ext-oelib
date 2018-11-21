<?php

namespace OliverKlee\Oelib\Tests\Unit\Configuration;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Tests\Unit\Configuration\Fixtures\DummyObjectToCheck;

/**
 * Test case.
 *
 * @author Saskia Metzler <saskia@merlin.owl.de>
 */
class ConfigurationCheckTest extends UnitTestCase
{
    /**
     * @var \Tx_Oelib_ConfigCheck configuration check object to be tested
     */
    private $subject = null;

    /**
     * @var DummyObjectToCheck
     */
    private $objectToCheck = null;

    protected function setUp()
    {
        parent::setUp();

        $this->objectToCheck = new DummyObjectToCheck(
            [
                'emptyString' => '',
                'nonEmptyString' => 'foo',
                'validEmail' => 'any-address@valid-email.org',
                'existingColumn' => 'title',
                'inexistentColumn' => 'does_not_exist',
            ]
        );
        $this->subject = new \Tx_Oelib_ConfigCheck($this->objectToCheck);
    }

    /*
     * Tests concerning the basics
     */

    /**
     * @test
     */
    public function objectToCheckIsCheckable()
    {
        self::assertInstanceOf(
            \Tx_Oelib_Interface_ConfigurationCheckable::class,
            $this->objectToCheck
        );
    }

    /**
     * @test
     */
    public function checkContainsNamespaceInErrorMessage()
    {
        $this->subject->checkForNonEmptyString('', false, '', '');

        self::assertContains(
            'plugin.tx_oelib_test.',
            $this->subject->getRawMessage()
        );
    }

    /////////////////////////////////
    // Tests concerning the flavor.
    /////////////////////////////////

    /**
     * @test
     */
    public function setFlavorReturnsFlavor()
    {
        $this->subject->setFlavor('foo');

        self::assertSame(
            'foo',
            $this->subject->getFlavor()
        );
    }

    /*
     * Tests concerning values to check
     */

    /**
     * @test
     */
    public function checkForNonEmptyStringWithNonEmptyString()
    {
        $this->subject->checkForNonEmptyString('nonEmptyString', false, '', '');

        self::assertSame(
            '',
            $this->subject->getRawMessage()
        );
    }

    /**
     * @test
     */
    public function checkForNonEmptyStringWithEmptyString()
    {
        $this->subject->checkForNonEmptyString('emptyString', false, '', '');

        self::assertContains(
            'emptyString',
            $this->subject->getRawMessage()
        );
    }

    ///////////////////////////////////////////////
    // Tests concerning the e-mail address check.
    ///////////////////////////////////////////////

    /**
     * @test
     */
    public function checkIsValidEmailOrEmptyWithEmptyString()
    {
        $this->subject->checkIsValidEmailOrEmpty('emptyString', false, '', false, '');

        self::assertSame(
            '',
            $this->subject->getRawMessage()
        );
    }

    /**
     * @test
     */
    public function checkIsValidEmailOrEmptyWithValidEmail()
    {
        $this->subject->checkIsValidEmailOrEmpty('validEmail', false, '', false, '');

        self::assertSame(
            '',
            $this->subject->getRawMessage()
        );
    }

    /**
     * @test
     */
    public function checkIsValidEmailOrEmptyWithInvalidEmail()
    {
        $this->subject->checkIsValidEmailOrEmpty('nonEmptyString', false, '', false, '');

        self::assertContains(
            'nonEmptyString',
            $this->subject->getRawMessage()
        );
    }

    /**
     * @test
     */
    public function checkIsValidEmailNotEmptyWithEmptyString()
    {
        $this->subject->checkIsValidEmailNotEmpty('emptyString', false, '', false, '');

        self::assertContains(
            'emptyString',
            $this->subject->getRawMessage()
        );
    }

    /**
     * @test
     */
    public function checkIsValidEmailNotEmptyWithValidEmail()
    {
        $this->subject->checkIsValidEmailNotEmpty('validEmail', false, '', false, '');

        self::assertSame(
            '',
            $this->subject->getRawMessage()
        );
    }

    /**
     * @test
     */
    public function checkIsValidDefaultFromEmailAddressForValidAddressMarksItAsValid()
    {
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] = 'oliver@example.com';
        $this->subject->checkIsValidDefaultFromEmailAddress();

        static::assertSame('', $this->subject->getRawMessage());
    }

    /**
     * @return array[]
     */
    public function invalidEmailDataProvider()
    {
        return [
            'null' => [null],
            'empty string' => [''],
            'invalid email address' => ['bitouz6tz1432zwerds'],
        ];
    }

    /**
     * @test
     *
     * @param string $emailAddress
     * @dataProvider invalidEmailDataProvider
     */
    public function checkIsValidDefaultFromEmailAddressForInalidAddressMarksItAsInvalid($emailAddress)
    {
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] = $emailAddress;

        $this->subject->checkIsValidDefaultFromEmailAddress();

        static::assertNotSame('', $this->subject->getRawMessage());
    }
}
