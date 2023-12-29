<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Templating;

use OliverKlee\Oelib\Templating\Template;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\Oelib\Templating\Template
 */
final class TemplateTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    protected bool $initializeDatabase = false;

    private Template $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Template();
    }

    // Tests for reading the HTML from a file.

    /**
     * @test
     */
    public function processTemplateFromFileProcessesTemplateFromFile(): void
    {
        $this->subject->processTemplateFromFile('EXT:oelib/Tests/Functional/Templating/Fixtures/Template.html');

        self::assertSame("Hello world!\n", $this->subject->render());
    }
}
