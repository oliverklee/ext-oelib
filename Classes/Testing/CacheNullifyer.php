<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Testing;

use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;
use TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\Cache\FluidTemplateCache;

/**
 * This class sets all core caches for tests.
 */
final class CacheNullifyer
{
    /**
     * @see https://github.com/TYPO3/typo3/blob/main/typo3/sysext/core/Configuration/DefaultConfiguration.php
     */
    private const CACHE_CONFIGURATIONS = [
        11 => [
            'core' => [
                'frontend' => PhpFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'hash' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'pages' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'pagesection' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'runtime' => [
                'frontend' => VariableFrontend::class,
                'backend' => TransientMemoryBackend::class,
                'options' => [],
                'groups' => [],
            ],
            'rootline' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'imagesizes' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['lowlevel'],
            ],
            'assets' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'l10n' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'fluid_template' => [
                'frontend' => FluidTemplateCache::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'extbase' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'ratelimiter' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
        ],
        12 => [
            'core' => [
                'frontend' => PhpFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'hash' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'pages' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'runtime' => [
                'frontend' => VariableFrontend::class,
                'backend' => TransientMemoryBackend::class,
                'options' => [],
                'groups' => [],
            ],
            'rootline' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['pages'],
            ],
            'imagesizes' => [
                'frontend' => VariableFrontend::class,
                'backend' => NullBackend::class,
                'options' => [],
                'groups' => ['lowlevel'],
            ],
            'assets' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'l10n' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'fluid_template' => [
                'frontend' => FluidTemplateCache::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'extbase' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'ratelimiter' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'typoscript' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
            'database_schema' => [
                'frontend' => VariableFrontend::class,
                'backend' => SimpleFileBackend::class,
                'options' => [],
                'groups' => ['system'],
            ],
        ],
    ];

    /**
     * Sets all Core caches to make testing easier, either to a null backend (for page, page section, rootline)
     * or a simple file backend.
     */
    public function setAllCoreCaches(): void
    {
        $typo3Version = (new Typo3Version())->getMajorVersion();
        if (!array_key_exists($typo3Version, self::CACHE_CONFIGURATIONS)) {
            throw new \UnexpectedValueException('Unsupported TYPO3 version: ' . $typo3Version, 1702811886);
        }
        $this->getCacheManager()->setCacheConfigurations(self::CACHE_CONFIGURATIONS[$typo3Version]);
    }

    private function getCacheManager(): CacheManager
    {
        return GeneralUtility::makeInstance(CacheManager::class);
    }
}
