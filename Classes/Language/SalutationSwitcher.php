<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Language;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * This class provides functions for localization.
 *
 * @deprecated will be removed in oelib 6.0
 */
abstract class SalutationSwitcher
{
    /**
     * Path to the plugin class script relative to extension directory, eg. 'pi1/class.tx_newfaq_pi1.php'
     *
     * @var string
     */
    public $scriptRelPath;

    /**
     * Extension key.
     *
     * @var string
     */
    public $extKey;

    /**
     * Should normally be set in the main function with the TypoScript content passed to the method.
     *
     * $conf[LOCAL_LANG][_key_] is reserved for Local Language overrides.
     * $conf[userFunc] reserved for setting up the USER / USER_INT object. See TSref
     *
     * @var array
     */
    public $conf = [];

    /**
     * Property for accessing TypoScriptFrontendController centrally
     *
     * @var TypoScriptFrontendController
     */
    protected $frontendController;

    /**
     * Local Language content
     *
     * @var array
     */
    public $LOCAL_LANG = [];

    /**
     * Contains those LL keys, which have been set to (empty) in TypoScript.
     * This is necessary, as we cannot distinguish between a nonexisting
     * translation and a label that has been cleared by TS.
     * In both cases ['key'][0]['target'] is "".
     *
     * @var array
     */
    protected $LOCAL_LANG_UNSET = [];

    /**
     * Flag that tells if the locallang file has been fetch (or tried to
     * be fetched) already.
     *
     * @var bool
     */
    public $LOCAL_LANG_loaded = false;

    /**
     * Pointer to the language to use.
     *
     * @var string
     */
    public $LLkey = 'default';

    /**
     * Pointer to alternative fall-back language to use.
     *
     * @var string
     */
    public $altLLkey = '';

    /**
     * You can set this during development to some value that makes it
     * easy for you to spot all labels that ARe delivered by the getLL function.
     *
     * @var string
     */
    public $LLtestPrefix = '';

    /**
     * Save as LLtestPrefix, but additional prefix for the alternative value
     * in getLL() function calls
     *
     * @var string
     */
    public $LLtestPrefixAlt = '';

    /**
     * A list of language keys for which the localizations have been loaded
     * (or NULL if the list has not been compiled yet).
     *
     * @var array<string>|null
     */
    private $availableLanguages;

    /**
     * An ordered list of language label suffixes that should be tried to get
     * localizations in the preferred order of formality (or NULL if the list
     * has not been compiled yet).
     *
     * @var list<'_formal'|'_informal'|''>|null
     */
    private $suffixesToTry;

    /**
     * @var array<non-empty-string, string>
     */
    protected $translationCache = [];

    public function __construct()
    {
        $this->frontendController = $GLOBALS['TSFE'] ?? null;

        $this->LLkey = $this->frontendController->getLanguage()->getTypo3Language();

        $locales = GeneralUtility::makeInstance(Locales::class);
        if (in_array($this->LLkey, $locales->getLocales(), true)) {
            foreach ($locales->getLocaleDependencies($this->LLkey) as $language) {
                $this->altLLkey .= $language . ',';
            }
            $this->altLLkey = rtrim($this->altLLkey, ',');
        }
    }

    /**
     * Makes this object serializable.
     *
     * @return list<non-empty-string>
     */
    public function __sleep(): array
    {
        $propertyNames = [];
        foreach ((new \ReflectionClass($this))->getProperties() as $property) {
            $propertyName = $property->getName();
            if ($propertyName === 'frontendController') {
                continue;
            }
            $propertyNames[] = $property->isPrivate() ? (static::class . ':' . $propertyName) : $propertyName;
        }

        return $propertyNames;
    }

    /**
     * Restores data that got lost during the serialization.
     */
    public function __wakeup(): void
    {
        $controller = $this->getFrontEndController();
        if ($controller instanceof TypoScriptFrontendController) {
            $this->frontendController = $controller;
        }
    }

    /**
     * Retrieves the localized string for the local language key $key.
     *
     * This function checks whether the FE or BE localization functions are
     * available and then uses the appropriate method.
     *
     * In $this->conf['salutation'], a suffix to the key may be set (which may
     * be either 'formal' or 'informal'). If a corresponding key exists, the
     * formal/informal localized string is used instead.
     * If the formal/informal key doesn't exist, this function just uses the
     * regular string.
     *
     * Example: key = 'greeting', suffix = 'informal'. If the key
     * 'greeting_informal' exists, that string is used.
     * If it doesn't exist, this functions tries to use the string with the key
     * 'greeting'.
     *
     * @param non-empty-string $key the local language key for which to return the value
     *
     * @return string the requested local language key, might be empty
     *
     * @deprecated will be removed in oelib 6.0
     */
    public function translate(string $key): string
    {
        // @phpstan-ignore-next-line We are explicitly checking for a contract violation here.
        if ($key === '') {
            throw new \InvalidArgumentException('$key must not be empty.', 1331489025);
        }
        if ($this->extKey === '') {
            return $key;
        }
        if (isset($this->translationCache[$key])) {
            return $this->translationCache[$key];
        }

        $this->pi_loadLL();
        if (\is_array($this->LOCAL_LANG) && $this->getFrontEndController() !== null) {
            $result = $this->translateInFrontEnd($key);
        } elseif ($this->getLanguageService() !== null) {
            $result = $this->translateInBackEnd($key);
        } else {
            $result = $key;
        }

        $this->translationCache[$key] = $result;

        return $result;
    }

    /**
     * Retrieves the localized string for the local language key $key, using the
     * BE localization methods.
     *
     * @param non-empty-string $key the local language key for which to return the value
     *
     * @return string the requested local language key, might be empty
     */
    private function translateInBackEnd(string $key): string
    {
        $languageService = $this->getLanguageService();

        if (!$languageService instanceof LanguageService) {
            throw new \RuntimeException('No initialized language service.', 1646321243);
        }

        return $languageService->getLL($key);
    }

    /**
     * Retrieves the localized string for the local language key $key, using the
     * FE localization methods.
     *
     * In $this->conf['salutation'], a suffix to the key may be set (which may
     * be either 'formal' or 'informal'). If a corresponding key exists, the
     * formal/informal localized string is used instead.
     * If the formal/informal key doesn't exist, this function just uses the
     * regular string.
     *
     * Example: key = 'greeting', suffix = 'informal'. If the key
     * 'greeting_informal' exists, that string is used.
     * If it doesn't exist, this functions tries to use the string with the key
     * 'greeting'.
     *
     * @param non-empty-string $key the local language key for which to return the value
     *
     * @return string the requested local language key, might be empty
     */
    private function translateInFrontEnd(string $key): string
    {
        $hasFoundATranslation = false;
        $result = '';

        $availableLanguages = $this->getAvailableLanguages();
        foreach ($this->getSuffixesToTry() as $suffix) {
            $completeKey = $key . $suffix;
            foreach ($availableLanguages as $language) {
                if (isset($this->LOCAL_LANG[$language][$completeKey])) {
                    $result = $this->pi_getLL($completeKey);
                    $hasFoundATranslation = true;
                    break 2;
                }
            }
        }

        if (!$hasFoundATranslation) {
            $result = $key;
        }

        return $result;
    }

    /**
     * Compiles a list of language keys for which localizations have been loaded.
     *
     * @return array<string> a list of language keys (might be empty)
     */
    private function getAvailableLanguages(): array
    {
        if ($this->availableLanguages === null) {
            $this->availableLanguages = [];

            if ($this->LLkey !== '') {
                $this->availableLanguages[] = $this->LLkey;
            }
            // The key for English is "default", not "en".
            $this->availableLanguages = \str_replace('en', 'default', $this->availableLanguages);
            // Remove duplicates in case the default language is the same as the fall-back language.
            $this->availableLanguages = \array_unique($this->availableLanguages);

            // Now check that we only keep languages for which we have translations.
            foreach ($this->availableLanguages as $index => $code) {
                if (!isset($this->LOCAL_LANG[$code])) {
                    unset($this->availableLanguages[$index]);
                }
            }
        }

        return $this->availableLanguages;
    }

    /**
     * Gets an ordered list of language label suffixes that should be tried to
     * get localizations in the preferred order of formality.
     *
     * @return list<'_formal'|'_informal'|''> ordered list of suffixes, will not be empty
     */
    private function getSuffixesToTry(): array
    {
        if ($this->suffixesToTry === null) {
            $this->suffixesToTry = [];

            if (isset($this->conf['salutation'])) {
                if ($this->conf['salutation'] === 'informal') {
                    $this->suffixesToTry[] = '_informal';
                }
                $this->suffixesToTry[] = '_formal';
            }
            $this->suffixesToTry[] = '';
        }

        return $this->suffixesToTry;
    }

    protected function getFrontEndController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'] ?? null;
    }

    /**
     * Returns $GLOBALS['LANG'].
     */
    protected function getLanguageService(): ?LanguageService
    {
        return $GLOBALS['LANG'] ?? null;
    }

    /**
     * Returns the localized label of the LOCAL_LANG key, $key
     * Notice that for debugging purposes prefixes for the output values can be set with the internal vars
     * ->LLtestPrefixAlt and ->LLtestPrefix
     *
     * @param string $key The key from the LOCAL_LANG array for which to return the value.
     * @param string $alternativeLabel Alternative string to return IF no value is found set for the key,
     *        neither for the local language nor the default.
     * @return string The value from LOCAL_LANG.
     */
    // phpcs:ignore
    public function pi_getLL(string $key, string $alternativeLabel = ''): string
    {
        $word = null;
        if (
            !empty($this->LOCAL_LANG[$this->LLkey][$key][0]['target'])
            || isset($this->LOCAL_LANG_UNSET[$this->LLkey][$key])
        ) {
            $word = $this->LOCAL_LANG[$this->LLkey][$key][0]['target'];
        } elseif ($this->altLLkey) {
            $alternativeLanguageKeys = GeneralUtility::trimExplode(',', $this->altLLkey, true);
            $alternativeLanguageKeys = array_reverse($alternativeLanguageKeys);
            foreach ($alternativeLanguageKeys as $languageKey) {
                if (
                    !empty($this->LOCAL_LANG[$languageKey][$key][0]['target'])
                    || isset($this->LOCAL_LANG_UNSET[$languageKey][$key])
                ) {
                    // Alternative language translation for key exists
                    $word = $this->LOCAL_LANG[$languageKey][$key][0]['target'];
                    break;
                }
            }
        }
        if ($word === null) {
            if (
                !empty($this->LOCAL_LANG['default'][$key][0]['target'])
                || isset($this->LOCAL_LANG_UNSET['default'][$key])
            ) {
                // Get default translation (without charset conversion, english)
                $word = $this->LOCAL_LANG['default'][$key][0]['target'];
            } else {
                // Return alternative string or empty
                $word = isset($this->LLtestPrefixAlt) ? $this->LLtestPrefixAlt . $alternativeLabel : $alternativeLabel;
            }
        }
        return isset($this->LLtestPrefix) ? $this->LLtestPrefix . $word : $word;
    }

    /**
     * Loads local-language values from the file passed as a parameter or
     * by looking for a "locallang" file in the
     * plugin class directory ($this->scriptRelPath).
     * Also locallang values set in the TypoScript property "_LOCAL_LANG" are
     * merged onto the values found in the "locallang" file.
     * Supported file extensions xlf
     *
     * @param string $languageFilePath path to the plugin language file in format EXT:....
     */
    // phpcs:ignore
    public function pi_loadLL($languageFilePath = ''): void
    {
        if ($this->LOCAL_LANG_loaded) {
            return;
        }

        if ($this->scriptRelPath !== '') {
            $languageFilePath = 'EXT:' . $this->extKey . '/'
                . PathUtility::dirname($this->scriptRelPath) . '/locallang.xlf';
        } else {
            $languageFilePath = '';
        }
        if ($languageFilePath !== '') {
            $languageFactory = GeneralUtility::makeInstance(LocalizationFactory::class);
            $this->LOCAL_LANG = $languageFactory->getParsedData($languageFilePath, $this->LLkey);
            $alternativeLanguageKeys = GeneralUtility::trimExplode(',', $this->altLLkey, true);
            foreach ($alternativeLanguageKeys as $languageKey) {
                $tempLL = $languageFactory->getParsedData($languageFilePath, $languageKey);
                if ($this->LLkey !== 'default' && isset($tempLL[$languageKey])) {
                    $this->LOCAL_LANG[$languageKey] = $tempLL[$languageKey];
                }
            }
        }
        $this->LOCAL_LANG_loaded = true;
    }
}
