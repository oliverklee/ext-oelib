<?php

/**
 * This class represents a mapper for federal state models.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Mapper_FederalState extends Tx_Oelib_DataMapper
{
    /**
     * @var string
     */
    protected $tableName = 'static_country_zones';

    /**
     * @var string
     */
    protected $modelClassName = \Tx_Oelib_Model_FederalState::class;

    /**
     * @var string[] the column names of additional combined keys
     */
    protected $compoundKeyParts = ['zn_country_iso_2', 'zn_code'];

    /**
     * Finds a federal state by its ISO 3166-1 and ISO 3166-2 code.
     *
     * @param string $isoAlpha2CountryCode
     *        the ISO 3166-1 alpha-2 country code to find, must not be empty
     * @param string $isoAlpha2ZoneCode
     *        the ISO 3166-2 code to find, must not be empty
     *
     * @return Tx_Oelib_Model_FederalState the federal state with the requested code
     */
    public function findByIsoAlpha2CountryCodeAndIsoAlpha2ZoneCode($isoAlpha2CountryCode, $isoAlpha2ZoneCode)
    {
        return $this->findOneByCompoundKey(['zn_country_iso_2' => $isoAlpha2CountryCode, 'zn_code' => $isoAlpha2ZoneCode]);
    }
}
