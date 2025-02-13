<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\ViewHelpers;

use OliverKlee\Oelib\ViewHelpers\IsFieldEnabledViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * @covers \OliverKlee\Oelib\ViewHelpers\IsFieldEnabledViewHelper
 */
final class IsFieldEnabledViewHelperTest extends UnitTestCase
{
    /**
     * @var \Closure
     */
    private $renderChildrenClosure;

    /**
     * @var RenderingContextInterface&Stub
     */
    private $renderingContextStub;

    /**
     * @var VariableProviderInterface&MockObject
     */
    private $variableProviderMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderChildrenClosure = static fn (): string => '';
        $this->renderingContextStub = $this->createStub(RenderingContextInterface::class);
        $this->variableProviderMock = $this->createMock(VariableProviderInterface::class);
        $this->renderingContextStub->method('getVariableProvider')->willReturn($this->variableProviderMock);
    }

    /**
     * @test
     */
    public function isConditionViewHelper(): void
    {
        $subject = new IsFieldEnabledViewHelper();

        self::assertInstanceOf(AbstractConditionViewHelper::class, $subject);
    }

    /**
     * @test
     */
    public function implementsViewHelper(): void
    {
        $subject = new IsFieldEnabledViewHelper();

        self::assertInstanceOf(ViewHelperInterface::class, $subject);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function initializeArgumentsCanBeCalled(): void
    {
        $subject = new IsFieldEnabledViewHelper();

        $subject->initializeArguments();
    }

    /**
     * @test
     */
    public function escapesChildren(): void
    {
        $subject = new IsFieldEnabledViewHelper();

        self::assertTrue($subject->isChildrenEscapingEnabled());
    }

    /**
     * @test
     */
    public function doesNotEscapeOutput(): void
    {
        $subject = new IsFieldEnabledViewHelper();

        self::assertFalse($subject->isOutputEscapingEnabled());
    }

    /**
     * @test
     */
    public function renderStaticForMissingSettingsInArgumentsThrowsException(): void
    {
        $this->expectExceptionCode(\UnexpectedValueException::class);
        $this->expectExceptionMessage('No settings in the variable container found.');
        $this->expectExceptionCode(1_651_153_736);

        $this->variableProviderMock->method('get')->with('settings')->willReturn(null);

        IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );
    }

    /**
     * @test
     */
    public function renderStaticForMissingSettingNameInSettingsThrowsException(): void
    {
        $this->expectExceptionCode(\UnexpectedValueException::class);
        $this->expectExceptionMessage('No field "fieldsToShow" in settings found.');
        $this->expectExceptionCode(1_651_154_598);

        $this->variableProviderMock->method('get')->with('settings')->willReturn([]);

        IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );
    }

    /**
     * @return array<string, list<array{}|positive-int>>
     */
    public function nonStringSettingDataProvider(): array
    {
        return [
            'array' => [[]],
            'int' => [5],
        ];
    }

    /**
     * @test
     *
     * @param array{}|int $value
     *
     * @dataProvider nonStringSettingDataProvider
     */
    public function renderStaticForNonStringSettingNameInSettingsThrowsException($value): void
    {
        $this->expectExceptionCode(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The setting "fieldsToShow" needs to be a string.');
        $this->expectExceptionCode(1_651_155_151);

        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => $value]);

        IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );
    }

    /**
     * @test
     */
    public function renderStaticForMissingFieldNameThrowsException(): void
    {
        $this->expectExceptionCode(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument "fieldName" must not be empty.');
        $this->expectExceptionCode(1_651_155_957);

        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company']);

        IsFieldEnabledViewHelper::renderStatic(
            [],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );
    }

    /**
     * @test
     */
    public function renderStaticForEmptyFieldNameThrowsException(): void
    {
        $this->expectExceptionCode(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument "fieldName" must not be empty.');
        $this->expectExceptionCode(1_651_155_957);

        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company']);

        IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => ''],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );
    }

    /**
     * @test
     */
    public function renderStaticForNonStringFieldNameThrowsException(): void
    {
        $this->expectExceptionCode(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The argument "fieldName" must be a string, but was array');
        $this->expectExceptionCode(1_651_496_544);

        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company']);

        IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => []],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );
    }

    /**
     * @test
     */
    public function renderForSingleRequestedFieldEnabledRendersThenChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('THEN', $result);
    }

    /**
     * @test
     */
    public function renderForRequestedFieldEnabledWithOtherAfterRendersThenChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company,name']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('THEN', $result);
    }

    /**
     * @test
     */
    public function renderForRequestedFieldEnabledWithOtherBeforeRendersThenChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'name,company']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('THEN', $result);
    }

    /**
     * @test
     */
    public function renderForOneOfTwoRequestedFieldsEnabledRendersThenChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company|name', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('THEN', $result);
    }

    /**
     * @test
     */
    public function renderForBothRequestedFieldsEnabledRendersThenChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company,name']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'company|name', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('THEN', $result);
    }

    /**
     * @test
     */
    public function renderForRequestedFieldNotEnabledRendersElseChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => 'company']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'name', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('ELSE', $result);
    }

    /**
     * @test
     */
    public function renderForNoFieldEnabledRendersElseChild(): void
    {
        $this->variableProviderMock->method('get')->with('settings')->willReturn(['fieldsToShow' => '']);

        $result = IsFieldEnabledViewHelper::renderStatic(
            ['fieldName' => 'name', 'then' => 'THEN', 'else' => 'ELSE'],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('ELSE', $result);
    }
}
