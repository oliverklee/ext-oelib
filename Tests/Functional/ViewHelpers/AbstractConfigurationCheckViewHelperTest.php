<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\ViewHelpers;

use OliverKlee\Oelib\Configuration\ConfigurationProxy;
use OliverKlee\Oelib\Configuration\DummyConfiguration;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;

/**
 * @covers \OliverKlee\Oelib\ViewHelpers\AbstractConfigurationCheckViewHelper
 */
final class AbstractConfigurationCheckViewHelperTest extends FunctionalTestCase
{
    protected bool $initializeDatabase = false;

    protected array $testExtensionsToLoad = ['oliverklee/oelib'];

    private StandardVariableProvider $variableProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->variableProvider = new StandardVariableProvider();
    }

    private function renderViewHelper(): string
    {
        $view = new StandaloneView();

        $renderingContext = $view->getRenderingContext();
        $renderingContext->setVariableProvider($this->variableProvider);
        $view->setRenderingContext($renderingContext);

        $html = '<oelib:testingConfigurationCheck />';
        $view->setTemplateSource($this->embedInHtmlWithNamespace($html));

        return $view->render();
    }

    private function embedInHtmlWithNamespace(string $html): string
    {
        return '<html xmlns:oelib="OliverKlee\Oelib\Tests\Functional\ViewHelpers\Fixtures" '
            . 'data-namespace-typo3-fluid="true">' .
            $html . '</html>';
    }

    /**
     * @test
     */
    public function renderForConfigurationCheckDisabledReturnsEmptyString(): void
    {
        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => false]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        $result = $this->renderViewHelper();

        self::assertSame('', $result);
    }

    /**
     * @test
     */
    public function renderForMissingSettingsInArgumentsThrowsException(): void
    {
        $this->expectExceptionCode(\UnexpectedValueException::class);
        $this->expectExceptionMessage('No settings in the variable container found.');
        $this->expectExceptionCode(1_651_153_736);

        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => true]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        $result = $this->renderViewHelper();

        self::assertSame('This is a configuration check warning.', $result);
    }

    /**
     * @test
     */
    public function renderForConfigurationCheckEnabledReturnsMessageFromConfigurationCheck(): void
    {
        $extensionKey = 'oelib';
        $extensionConfiguration = new DummyConfiguration(['enableConfigCheck' => true]);
        ConfigurationProxy::setInstance($extensionKey, $extensionConfiguration);
        $this->variableProvider->add('settings', []);

        $adminUserStub = $this->createStub(BackendUserAuthentication::class);
        $adminUserStub->method('isAdmin')->willReturn(true);
        $GLOBALS['BE_USER'] = $adminUserStub;

        $result = $this->renderViewHelper();

        self::assertStringContainsString('This is a configuration check warning.', $result);
    }
}
