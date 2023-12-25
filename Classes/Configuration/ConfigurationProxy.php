<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Configuration;

use OliverKlee\Oelib\DataStructures\AbstractReadOnlyObjectWithPublicAccessors;
use OliverKlee\Oelib\Interfaces\Configuration as ConfigurationInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This singleton class provides access to an extension's global configuration
 * and allows faking global configuration values for testing purposes.
 */
class ConfigurationProxy extends AbstractReadOnlyObjectWithPublicAccessors implements ConfigurationInterface
{
    /**
     * @var array<string, ConfigurationInterface> the singleton configuration proxy objects
     */
    private static $instances = [];

    /**
     * @var array<string, mixed> configuration data for each extension which currently uses the configuration proxy
     */
    private array $configuration = [];

    /**
     * @var non-empty-string key of the extension for which the EM configuration is stored
     */
    private string $extensionKey;

    /**
     * @var bool whether the configuration is already loaded
     */
    private bool $isConfigurationLoaded = false;

    /**
     * Don't call this constructor; use getInstance instead.
     *
     * @param non-empty-string $extensionKey extension key without the 'tx' prefix, used to retrieve the EM
     *        configuration and as identifier for an extension's instance of
     *        this class
     */
    private function __construct(string $extensionKey)
    {
        $this->extensionKey = $extensionKey;
    }

    /**
     * Returns the name of the configuration source, e.g., "TypoScript setup" or "Flexforms".
     *
     * This name may also contain HTML.
     *
     * @return non-empty-string
     */
    public function getSourceName(): string
    {
        return 'the extension settings in the backend (Admin Tools, Settings, Extension Configuration)';
    }

    /**
     * Retrieves the configuration for the given extension key.
     *
     * @param non-empty-string $extensionKey extension key without the 'tx' prefix, used to retrieve the EM
     *        configuration and as identifier for an extension's instance of
     *        this class
     *
     * @return ConfigurationInterface the configuration for the given extension key
     *
     * @throws \InvalidArgumentException
     */
    public static function getInstance(string $extensionKey): ConfigurationInterface
    {
        // @phpstan-ignore-next-line We're checking for a contract violation here.
        if ($extensionKey === '') {
            throw new \InvalidArgumentException('The extension key was not set.', 1_331_318_826);
        }

        if (!isset(self::$instances[$extensionKey])) {
            self::setInstance($extensionKey, new ConfigurationProxy($extensionKey));
        }

        return self::$instances[$extensionKey];
    }

    /**
     * Sets/replaces a configuration for the given extension key.
     *
     * This method is mainly intended to be used in testing.
     *
     * @throws \InvalidArgumentException
     */
    public static function setInstance(string $extensionKey, ConfigurationInterface $configuration): void
    {
        if ($extensionKey === '') {
            throw new \InvalidArgumentException('The extension key must not be empty.', 1_612_091_700);
        }

        self::$instances[$extensionKey] = $configuration;
    }

    /**
     * Purges the current instances so that getInstance will create new instances.
     */
    public static function purgeInstances(): void
    {
        self::$instances = [];
    }

    /**
     * Loads the EM configuration for the extension key passed via
     * `getInstance()` if the configuration is not yet loaded.
     */
    private function loadConfigurationLazily(): void
    {
        if (!$this->isConfigurationLoaded) {
            $this->retrieveConfiguration();
        }
    }

    /**
     * Retrieves the EM configuration for the extension key passed via `getInstance()`.
     *
     * This function is accessible for testing purposes. As lazy implementation
     * is used, this function might be useful to ensure static test conditions.
     */
    public function retrieveConfiguration(): void
    {
        if (
            isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'][$this->extensionKey])
            && \is_array($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'][$this->extensionKey])
        ) {
            $this->configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
                ->get($this->extensionKey);
        } else {
            $this->configuration = [];
        }

        $this->isConfigurationLoaded = true;
    }

    /**
     * Checks whether a certain key exists in an extension's configuration.
     *
     * @param non-empty-string $key
     *
     * @return bool whether $key occurs in the configuration array of the extension named $this->extensionKey
     */
    private function hasConfigurationValue(string $key): bool
    {
        $this->loadConfigurationLazily();

        return isset($this->configuration[$key]);
    }

    /**
     * Returns a string configuration value.
     *
     * @param non-empty-string $key
     *
     * @return mixed configuration value string, might be empty
     */
    protected function get(string $key)
    {
        $this->loadConfigurationLazily();

        return $this->hasConfigurationValue($key) ? $this->configuration[$key] : '';
    }

    /**
     * Sets a new configuration value.
     *
     * The configuration setters are intended to be used for testing purposes only.
     *
     * @param non-empty-string $key key of the value to set
     * @param mixed $value the value to set
     */
    protected function set(string $key, $value): void
    {
        $this->loadConfigurationLazily();

        $this->configuration[$key] = $value;
    }

    /**
     * Returns an extension's complete configuration.
     *
     * @return array<string, mixed> an extension's configuration, empty if the configuration was not retrieved before
     */
    public function getCompleteConfiguration(): array
    {
        $this->loadConfigurationLazily();

        return $this->configuration;
    }
}
