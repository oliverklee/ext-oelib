<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Configuration\Fixtures;

use OliverKlee\Oelib\Interfaces\ConfigurationCheckable;
use OliverKlee\Oelib\Templating\TemplateHelper;

/**
 * This is mere a class to test the configuration check class.
 */
class DummyObjectToCheck extends TemplateHelper implements ConfigurationCheckable
{
    /**
     * The constructor.
     *
     * @param array $configuration
     *        configuration for the dummy object, may be empty
     */
    public function __construct(array $configuration)
    {
        $this->init($configuration);
    }

    /**
     * Returns the prefix for the configuration to check, e.g. "plugin.tx_seminars_pi1.".
     *
     * @return string the namespace prefix, will end with a dot
     */
    public function getTypoScriptNamespace(): string
    {
        return 'plugin.tx_oelib_test.';
    }
}
