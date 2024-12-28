<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\ViewHelpers;

use OliverKlee\Oelib\Configuration\ConfigurationProxy;
use OliverKlee\Oelib\Configuration\DummyConfiguration;
use OliverKlee\Oelib\Configuration\ExtbaseConfiguration;
use OliverKlee\Oelib\Tests\Unit\ViewHelpers\Fixtures\TestingConfigurationCheck;
use OliverKlee\Oelib\Tests\Unit\ViewHelpers\Fixtures\TestingConfigurationCheckViewHelper;
use OliverKlee\Oelib\ViewHelpers\AbstractConfigurationCheckViewHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;

/**
 * @covers \OliverKlee\Oelib\ViewHelpers\AbstractConfigurationCheckViewHelper
 */
final class AbstractConfigurationCheckViewHelperTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

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
        $this->variableProviderMock = $this->createMock(VariableProviderInterface::class);
        $this->renderingContextStub = $this->createStub(RenderingContextInterface::class);
        $this->renderingContextStub->method('getVariableProvider')->willReturn($this->variableProviderMock);
    }

    protected function tearDown(): void
    {
        ConfigurationProxy::purgeInstances();
        unset($GLOBALS['BE_USER']);

        parent::tearDown();
    }

    /**
     * @test
     */
    public function isViewHelper(): void
    {
        $subject = new TestingConfigurationCheckViewHelper();

        self::assertInstanceOf(AbstractViewHelper::class, $subject);
        self::assertInstanceOf(AbstractConfigurationCheckViewHelper::class, $subject);
    }

    /**
     * @test
     */
    public function implementsViewHelper(): void
    {
        $subject = new TestingConfigurationCheckViewHelper();

        self::assertInstanceOf(ViewHelperInterface::class, $subject);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function initializeArgumentsCanBeCalled(): void
    {
        $subject = new TestingConfigurationCheckViewHelper();

        $subject->initializeArguments();
    }

    /**
     * @test
     */
    public function escapesChildren(): void
    {
        $subject = new TestingConfigurationCheckViewHelper();

        self::assertTrue($subject->isChildrenEscapingEnabled());
    }

    /**
     * @test
     */
    public function doesNotEscapeOutput(): void
    {
        $subject = new TestingConfigurationCheckViewHelper();

        self::assertFalse($subject->isOutputEscapingEnabled());
    }

    /**
     * @test
     */
    public function renderStaticForConfigurationCheckDisabledReturnsEmptyString(): void
    {
        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => false]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        $result = TestingConfigurationCheckViewHelper::renderStatic(
            [],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('', $result);
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

        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => true]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        $result = TestingConfigurationCheckViewHelper::renderStatic(
            [],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertSame('This is a configuration check warning.', $result);
    }

    /**
     * @test
     */
    public function renderStaticForConfigurationCheckEnabledReturnsMessageFromConfigurationCheck(): void
    {
        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => true]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);
        $this->variableProviderMock->method('get')->with('settings')->willReturn([]);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        $result = TestingConfigurationCheckViewHelper::renderStatic(
            [],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        self::assertStringContainsString('This is a configuration check warning.', $result);
    }

    /**
     * @test
     */
    public function renderStaticForConfigurationCheckEnabledPassesConfigurationToConfigurationCheck(): void
    {
        $key = 'foo';
        $value = 'bar';
        $settings = [$key => $value];
        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => true]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);
        $this->variableProviderMock->method('get')->with('settings')->willReturn($settings);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        TestingConfigurationCheckViewHelper::renderStatic(
            [],
            $this->renderChildrenClosure,
            $this->renderingContextStub
        );

        $configuration = TestingConfigurationCheck::getCheckedConfiguration();
        self::assertInstanceOf(ExtbaseConfiguration::class, $configuration);
        self::assertSame($value, $configuration->getAsString($key));
    }
}
