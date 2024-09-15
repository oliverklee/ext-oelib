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
     * @var array<string, array<string, class-string<VariableFrontend>|class-string<TransientMemoryBackend>|mixed[]>|array<string, class-string<VariableFrontend>|class-string<NullBackend>|mixed[]|string[]>|array<string, class-string<VariableFrontend>|class-string<PhpFrontend>|class-string<SimpleFileBackend>|mixed[]|string[]>>[]
     */
    private const CACHE_CONFIGURATIONS = [
        11 => [
            'core' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => PhpFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'hash' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'pages' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'pagesection' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'runtime' => [
                'backend' => TransientMemoryBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => [],
                'options' => [],
            ],
            'rootline' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'imagesizes' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['lowlevel'],
                'options' => [],
            ],
            'assets' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'l10n' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'fluid_template' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => FluidTemplateCache::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'extbase' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'ratelimiter' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
        ],
        12 => [
            'core' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => PhpFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'hash' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'pages' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'runtime' => [
                'backend' => TransientMemoryBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => [],
                'options' => [],
            ],
            'rootline' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['pages'],
                'options' => [],
            ],
            'imagesizes' => [
                'backend' => NullBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['lowlevel'],
                'options' => [],
            ],
            'assets' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'l10n' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'fluid_template' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => FluidTemplateCache::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'extbase' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'ratelimiter' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'typoscript' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
            ],
            'database_schema' => [
                'backend' => SimpleFileBackend::class,
                'frontend' => VariableFrontend::class,
                'groups' => ['system'],
                'options' => [],
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
            throw new \UnexpectedValueException('Unsupported TYPO3 version: ' . $typo3Version, 1_702_811_886);
        }

        $this->getCacheManager()->setCacheConfigurations(self::CACHE_CONFIGURATIONS[$typo3Version]);
    }

    private function getCacheManager(): CacheManager
    {
        return GeneralUtility::makeInstance(CacheManager::class);
    }
}
