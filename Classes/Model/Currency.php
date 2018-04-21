<?php

/**
 * This class represents a currency.
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 */
class Tx_Oelib_Model_Currency extends \Tx_Oelib_Model
{
    /**
     * @var bool whether this model is read-only
     */
    protected $readOnly = true;

    /**
     * Returns the ISO 4217 alpha-3 code for this currency.
     *
     * @return string the ISO 4217 alpha-3 code of this currency, will not be
     *                empty
     */
    public function getIsoAlpha3Code()
    {
        return $this->getAsString('cu_iso_3');
    }

    /**
     * Returns whether this currency has a left symbol.
     *
     * @return bool TRUE if this currency has a left symbol, FALSE otherwise
     */
    public function hasLeftSymbol()
    {
        return $this->hasString('cu_symbol_left');
    }

    /**
     * Returns the left currency symbol.
     *
     * @return string the left currency symbol, will be empty if this currency
     *                has no left symbol
     */
    public function getLeftSymbol()
    {
        return $this->getAsString('cu_symbol_left');
    }

    /**
     * Returns whether this currency has a right symbol.
     *
     * @return bool TRUE if this currency has a right symbol, FALSE otherwise
     */
    public function hasRightSymbol()
    {
        return $this->hasString('cu_symbol_right');
    }

    /**
     * Returns the right currency symbol.
     *
     * @return string the right currency symbol, will be empty if this currency
     *                has no right symbol
     */
    public function getRightSymbol()
    {
        return $this->getAsString('cu_symbol_right');
    }

    /**
     * Returns the thousands separator.
     *
     * @return string the thousands separator, will not be empty
     */
    public function getThousandsSeparator()
    {
        return $this->getAsString('cu_thousands_point');
    }

    /**
     * Returns the decimal separator.
     *
     * @return string the decimal separator, will not be empty
     */
    public function getDecimalSeparator()
    {
        return $this->getAsString('cu_decimal_point');
    }

    /**
     * Returns the number of decimal digits.
     *
     * @return int the number of decimal digits, will be >= 0
     */
    public function getDecimalDigits()
    {
        return $this->getAsInteger('cu_decimal_digits');
    }
}
