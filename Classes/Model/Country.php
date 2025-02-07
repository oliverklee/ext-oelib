<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Model;

/**
 * This class represents a country.
 *
 * @deprecated #1559 will be removed in version 7.0
 */
class Country extends AbstractModel
{
    protected bool $readOnly = true;

    /**
     * Returns the country's local short name.
     *
     * @return string the country's local short name, will not be empty
     */
    public function getLocalShortName(): string
    {
        return $this->getAsString('cn_short_local');
    }

    /**
     * Returns the ISO 3166-1 alpha-2 code for this country.
     *
     * @return string the ISO 3166-1 alpha-2 code of this country, will not be empty
     */
    public function getIsoAlpha2Code(): string
    {
        return $this->getAsString('cn_iso_2');
    }

    /**
     * Returns the ISO 3166-1 alpha-3 code for this country.
     *
     * @return string the ISO 3166-1 alpha-3 code of this country, will not be empty
     */
    public function getIsoAlpha3Code(): string
    {
        return $this->getAsString('cn_iso_3');
    }
}
