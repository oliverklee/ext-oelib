<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\ViewHelpers;

use OliverKlee\Oelib\Exception\NotFoundException;
use OliverKlee\Oelib\Mapper\CurrencyMapper;
use OliverKlee\Oelib\Mapper\MapperRegistry;
use OliverKlee\Oelib\Model\Currency;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This class represents a view helper for formatting a price.
 *
 * The value (`setValue()`) and the currency (`setCurrencyFromIsoAlpha3Code()`)
 * should be set before calling `render()`. You can use the same instance of this
 * view helper to render different values in the same currency by changing the
 * value via `setValue()`.
 *
 * @deprecated #1572 will be removed in version 7.0
 */
class PriceViewHelper extends AbstractViewHelper
{
    /**
     * @var float the value of the price to render
     */
    protected float $value = 0.0;

    protected ?Currency $currency = null;

    /**
     * Sets the value of the price to render.
     *
     * @param float $value the value of the price to render, may be negative, positive or zero
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * Sets the currency of the price to render based on the currency's ISO
     * alpha 3 code, e.g. "EUR" for Euro, "USD" for US dollars.
     *
     * @param non-empty-string $isoAlpha3Code the ISO alpha 3 code of the currency to set
     */
    public function setCurrencyFromIsoAlpha3Code(string $isoAlpha3Code): void
    {
        if (\strlen($isoAlpha3Code) !== 3) {
            $this->currency = null;
            return;
        }

        try {
            $this->currency = MapperRegistry::get(CurrencyMapper::class)->findByIsoAlpha3Code($isoAlpha3Code);
        } catch (NotFoundException $notFoundException) {
            $this->currency = null;
        }
    }

    /**
     * Renders the price based on $this->value and $this->currency.
     *
     * Please call setCurrencyFromIsoAlpha3Code() prior to calling render().
     *
     * If this function is called without setting a currency first, it will
     * use some default rendering for the price.
     *
     * @return string the rendered price
     */
    public function render(): string
    {
        $currency = $this->currency;
        if (!$currency instanceof Currency) {
            return \number_format($this->value, 2, '.', '');
        }

        $result = '';

        if ($currency->hasLeftSymbol()) {
            $result .= $currency->getLeftSymbol() . ' ';
        }

        $result .= \number_format(
            $this->value,
            $currency->getDecimalDigits(),
            $currency->getDecimalSeparator(),
            $currency->getThousandsSeparator()
        );

        if ($currency->hasRightSymbol()) {
            $result .= ' ' . $currency->getRightSymbol();
        }

        return $result;
    }
}
