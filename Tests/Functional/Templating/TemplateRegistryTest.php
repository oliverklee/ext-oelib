<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Templating;

use OliverKlee\Oelib\Templating\Template;
use OliverKlee\Oelib\Templating\TemplateRegistry;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\Oelib\Templating\TemplateRegistry
 */
final class TemplateRegistryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    protected bool $initializeDatabase = false;

    // Tests concerning the Singleton property

    /**
     * @test
     */
    public function getForExistingTemplateFileNameReturnsTemplate(): void
    {
        self::assertInstanceOf(
            Template::class,
            TemplateRegistry::get('EXT:oelib/Tests/Functional/Templating/Fixtures/Template.html')
        );
    }

    /**
     * @test
     */
    public function getForExistingTemplateFileNameCalledTwoTimesReturnsNewInstance(): void
    {
        self::assertNotSame(
            TemplateRegistry::get('EXT:oelib/Tests/Functional/Templating/Fixtures/Template.html'),
            TemplateRegistry::get('EXT:oelib/Tests/Functional/Templating/Fixtures/Template.html')
        );
    }

    /**
     * @test
     */
    public function getForExistingTemplateFileNameReturnsProcessedTemplate(): void
    {
        $template = TemplateRegistry::get('EXT:oelib/Tests/Functional/Templating/Fixtures/Template.html');

        self::assertSame(
            "Hello world!\n",
            $template->getSubpart()
        );
    }
}
