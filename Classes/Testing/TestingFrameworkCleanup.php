<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Testing;

use OliverKlee\Oelib\Authentication\BackEndLoginManager;
use OliverKlee\Oelib\Authentication\FrontEndLoginManager;
use OliverKlee\Oelib\Configuration\ConfigurationProxy;
use OliverKlee\Oelib\Configuration\ConfigurationRegistry;
use OliverKlee\Oelib\Configuration\PageFinder;
use OliverKlee\Oelib\Http\HeaderProxyFactory;
use OliverKlee\Oelib\Mapper\MapperRegistry;

/**
 * This class takes care of cleaning up oelib after the testing framework.
 *
 * @internal
 */
class TestingFrameworkCleanup
{
    /**
     * Cleans up oelib after running a test.
     */
    public function cleanUp(): void
    {
        BackEndLoginManager::purgeInstance();
        ConfigurationProxy::purgeInstances();
        ConfigurationRegistry::purgeInstance();
        FrontEndLoginManager::purgeInstance();
        HeaderProxyFactory::purgeInstance();
        MapperRegistry::purgeInstance();
        PageFinder::purgeInstance();
    }
}
