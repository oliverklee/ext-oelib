<?php

/**
 * This is mere a class used for unit tests. Don't use it for any other purpose.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
final class Tx_Oelib_Tests_Unit_Fixtures_TestingSalutationSwitcher extends \Tx_Oelib_SalutationSwitcher
{
    /**
     * @var string
     */
    public $scriptRelPath = 'Tests/Unit/Fixtures/TestingSalutationSwitcher.php';

    /**
     * @var string
     */
    public $extKey = 'oelib';

    /**
     * The constructor.
     *
     * @param array $configuration
     *        TS setup configuration, may be empty
     */
    public function __construct(array $configuration)
    {
        parent::__construct();

        $this->conf = $configuration;

        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
    }

    /**
     * Sets the current language for this plugin and loads the language files.
     *
     * @param string $language
     *        two-letter lowercase language like "en" or "de" or "default"
     *        (which is an alias for "en")
     *
     * @return void
     */
    public function setLanguage($language)
    {
        if ($this->getLanguage() !== $language) {
            // Make sure the language file are reloaded.
            $this->LOCAL_LANG_loaded = false;
            $this->LLkey = $language;
        }

        $this->pi_loadLL();
    }

    /**
     * Gets the current language.
     *
     * @return string the two-letter key of the current language like "en",
     *                "de" or "default" (which is the only non-two-letter
     *                code and an alias for "en"), will return an empty
     *                string if no language key has been set yet
     */
    public function getLanguage()
    {
        return $this->LLkey;
    }

    /**
     * Sets the salutation mode.
     *
     * @param string $salutation
     *        the salutation mode to use ("formal" or "informal")
     *
     * @return void
     */
    public function setSalutationMode($salutation)
    {
        $this->conf['salutation'] = $salutation;
    }

    /**
     * Gets the salutation mode.
     *
     * @return string the current salutation mode to use: "formal", "informal"
     *                or an empty string
     */
    public function getSalutationMode()
    {
        return $this->conf['salutation'];
    }
}
