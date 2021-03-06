<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Configuration;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Configuration\ConfigurationRegistry;
use OliverKlee\Oelib\Configuration\DummyConfiguration;
use OliverKlee\Oelib\Configuration\TypoScriptConfiguration;

/**
 * @covers \OliverKlee\Oelib\Configuration\ConfigurationRegistry
 */
class ConfigurationRegistryTest extends UnitTestCase
{
    /*
     *Tests concerning the Singleton property
     */

    /**
     * @test
     */
    public function getInstanceReturnsConfigurationRegistryInstance()
    {
        self::assertInstanceOf(ConfigurationRegistry::class, ConfigurationRegistry::getInstance());
    }

    /**
     * @test
     */
    public function getInstanceTwoTimesReturnsSameInstance()
    {
        self::assertSame(
            ConfigurationRegistry::getInstance(),
            ConfigurationRegistry::getInstance()
        );
    }

    /**
     * @test
     */
    public function getInstanceAfterPurgeInstanceReturnsNewInstance()
    {
        $firstInstance = ConfigurationRegistry::getInstance();
        ConfigurationRegistry::purgeInstance();

        self::assertNotSame(
            $firstInstance,
            ConfigurationRegistry::getInstance()
        );
    }

    /*
     *Test concerning get and set
     */

    /**
     * @test
     */
    public function getForEmptyNamespaceThrowsException()
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$namespace must not be empty.'
        );

        ConfigurationRegistry::get('');
    }

    /**
     * @test
     */
    public function setWithEmptyNamespaceThrowsException()
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            '$namespace must not be empty.'
        );

        ConfigurationRegistry::getInstance()->set(
            '',
            new TypoScriptConfiguration()
        );
    }

    /**
     * @test
     */
    public function getAfterSetWithTypoScriptConfigurationReturnsTheSetInstance()
    {
        $configuration = new TypoScriptConfiguration();

        ConfigurationRegistry::getInstance()->set('foo', $configuration);

        self::assertSame($configuration, ConfigurationRegistry::get('foo'));
    }

    /**
     * @test
     */
    public function getAfterSetWithDummyConfigurationReturnsTheSetInstance()
    {
        $configuration = new DummyConfiguration();

        ConfigurationRegistry::getInstance()->set('foo', $configuration);

        self::assertSame($configuration, ConfigurationRegistry::get('foo'));
    }

    /**
     * @test
     *
     * @doesNotPerformAssertions
     */
    public function setTwoTimesForTheSameNamespaceDoesNotFail()
    {
        ConfigurationRegistry::getInstance()->set(
            'foo',
            new TypoScriptConfiguration()
        );
        ConfigurationRegistry::getInstance()->set(
            'foo',
            new TypoScriptConfiguration()
        );
    }
}
