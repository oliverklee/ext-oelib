<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Validation;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Tests\Unit\Validation\Fixtures\TestingConfigurationDependentValidator;
use OliverKlee\Oelib\Tests\Unit\Validation\Fixtures\TestingValidatableModel;
use TYPO3\CMS\Extbase\Validation\Error;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

/**
 * @covers \OliverKlee\Oelib\Validation\AbstractConfigurationDependentValidator
 */
final class AbstractConfigurationDependentValidatorTest extends UnitTestCase
{
    /**
     * @var TestingConfigurationDependentValidator
     *
     * We can make this property private once we drop support for TYPO3 V9.
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new TestingConfigurationDependentValidator();
    }

    /**
     * @test
     */
    public function isValidator(): void
    {
        self::assertInstanceOf(ValidatorInterface::class, $this->subject);
        self::assertInstanceOf(AbstractValidator::class, $this->subject);
    }

    /**
     * @test
     */
    public function validateWithOtherObjectReturnsNoErrors(): void
    {
        $result = $this->subject->validate(new \stdClass());

        self::assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function validateWithEmptyModelForOrRequiredFieldsSettingReturnsNoErrors(): void
    {
        $result = $this->subject->validate(new TestingValidatableModel());

        self::assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function validateWithEmptyModelForEmptyRequiredFieldsReturnsNoErrors(): void
    {
        $this->subject->setSettings(['requiredFields' => '']);

        $result = $this->subject->validate(new TestingValidatableModel());

        self::assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function validateWithEmptyModelForSingleFieldRequiredAddsErrorForRequiredField(): void
    {
        $field = 'title';
        $this->subject->setSettings(['requiredFields' => $field]);

        $result = $this->subject->validate(new TestingValidatableModel());

        self::assertTrue($result->hasErrors());
        $forProperty = $result->forProperty($field);
        self::assertCount(1, $forProperty->getErrors());
        $firstError = $forProperty->getFirstError();
        self::assertInstanceOf(Error::class, $firstError);
        self::assertSame('validationError.fillInField', $firstError->getMessage());
    }

    /**
     * @test
     */
    public function validateModelWithFullModelForAllFieldsRequiredReturnsNoErrors(): void
    {
        $this->subject->setSettings(['requiredFields' => 'title']);

        $result = $this->subject->validate(new TestingValidatableModel('banana'));

        self::assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function validateModelWithFullModelForNoFieldsRequiredReturnsNoErrors(): void
    {
        $this->subject->setSettings(['requiredFields' => '']);

        $result = $this->subject->validate(new TestingValidatableModel('banana'));

        self::assertFalse($result->hasErrors());
    }
}
