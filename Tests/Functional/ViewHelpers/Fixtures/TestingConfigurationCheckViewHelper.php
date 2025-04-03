<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\ViewHelpers\Fixtures;

use OliverKlee\Oelib\ViewHelpers\AbstractConfigurationCheckViewHelper;

/**
 * @extends AbstractConfigurationCheckViewHelper<TestingConfigurationCheck>
 */
final class TestingConfigurationCheckViewHelper extends AbstractConfigurationCheckViewHelper
{
    protected static function getExtensionKey(): string
    {
        return 'oelib';
    }

    protected static function getConfigurationCheckClassName(): string
    {
        return TestingConfigurationCheck::class;
    }
}
