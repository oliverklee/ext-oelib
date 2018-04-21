<?php

/**
 * This model represents a federal state, e.g., Nordrhein-Westfalen (in Germany).
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Model_FederalState extends \Tx_Oelib_Model
{
    /**
     * @var bool
     */
    protected $readOnly = true;

    /**
     * Returns the local name, e.g., "Nordrhein-Westfalen".
     *
     * @return string the local name, will not be empty
     */
    public function getLocalName()
    {
        return $this->getAsString('zn_name_local');
    }

    /**
     * Returns the English name, e.g., "North Rhine-Westphalia".
     *
     * @return string the English name, will not be empty
     */
    public function getEnglishName()
    {
        return $this->getAsString('zn_name_en');
    }

    /**
     * Returns the ISO 3166-1 alpha-2 code, e.g., "DE".
     *
     * @return string the ISO 3166-1 alpha-2 code, will not be empty
     */
    public function getIsoAlpha2Code()
    {
        return $this->getAsString('zn_country_iso_2');
    }

    /**
     * Returns the ISO 3166-2 alpha-2 code, e.g., "NW".
     *
     * @return string the ISO 3166-2 alpha-2 code, will not be empty
     */
    public function getIsoAlpha2ZoneCode()
    {
        return $this->getAsString('zn_code');
    }
}
