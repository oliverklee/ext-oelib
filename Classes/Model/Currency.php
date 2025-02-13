<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Model;

/**
 * This class represents a currency.
 *
 * @deprecated #1573 will be removed in version 7.0
 */
class Currency extends AbstractModel
{
    protected bool $readOnly = true;

    /**
     * Returns the ISO 4217 alpha-3 code for this currency.
     *
     * @return string the ISO 4217 alpha-3 code of this currency, will not be
     *                empty
     */
    public function getIsoAlpha3Code(): string
    {
        return $this->getAsString('cu_iso_3');
    }

    /**
     * Returns whether this currency has a left symbol.
     *
     * @return bool TRUE if this currency has a left symbol, FALSE otherwise
     */
    public function hasLeftSymbol(): bool
    {
        return $this->hasString('cu_symbol_left');
    }

    /**
     * Returns the left currency symbol.
     *
     * @return string the left currency symbol, will be empty if this currency
     *                has no left symbol
     */
    public function getLeftSymbol(): string
    {
        return $this->getAsString('cu_symbol_left');
    }

    /**
     * Returns whether this currency has a right symbol.
     *
     * @return bool TRUE if this currency has a right symbol, FALSE otherwise
     */
    public function hasRightSymbol(): bool
    {
        return $this->hasString('cu_symbol_right');
    }

    /**
     * Returns the right currency symbol.
     *
     * @return string the right currency symbol, will be empty if this currency
     *                has no right symbol
     */
    public function getRightSymbol(): string
    {
        return $this->getAsString('cu_symbol_right');
    }

    /**
     * Returns the thousands separator.
     *
     * @return string the thousands separator, will not be empty
     */
    public function getThousandsSeparator(): string
    {
        return $this->getAsString('cu_thousands_point');
    }

    /**
     * Returns the decimal separator.
     *
     * @return string the decimal separator, will not be empty
     */
    public function getDecimalSeparator(): string
    {
        return $this->getAsString('cu_decimal_point');
    }

    /**
     * Returns the number of decimal digits.
     *
     * @return int<0, max>
     */
    public function getDecimalDigits(): int
    {
        return $this->getAsNonNegativeInteger('cu_decimal_digits');
    }
}
