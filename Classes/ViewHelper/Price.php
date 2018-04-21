<?php

/**
 * This class represents a view helper for formatting a price.
 *
 * The value (setValue()) and the currency (setCurrencyFromIsoAlpha3Code())
 * should be set before calling render(). You can use the same instance of this
 * view helper to render different values in the same currency by changing the
 * value via setValue().
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_ViewHelper_Price
{
    /**
     * @var float the value of the price to render
     */
    protected $value = 0.000;

    /**
     * @var \Tx_Oelib_Model_Currency the currency of the price to render
     */
    protected $currency = null;

    /**
     * Sets the value of the price to render.
     *
     * @param float $value
     *        the value of the price to render, may be negative, positive or zero
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Sets the currency of the price to render based on the currency's ISO
     * alpha 3 code, e.g. "EUR" for Euro, "USD" for US dollars.
     *
     * @param string $isoAlpha3Code
     *        the ISO alpha 3 code of the currency to set, must not be empty
     *
     * @return void
     */
    public function setCurrencyFromIsoAlpha3Code($isoAlpha3Code)
    {
        try {
            /** @var \Tx_Oelib_Mapper_Currency $mapper */
            $mapper = \Tx_Oelib_MapperRegistry::get(\Tx_Oelib_Mapper_Currency::class);
            $this->currency = $mapper->findByIsoAlpha3Code($isoAlpha3Code);
        } catch (Exception $exception) {
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
    public function render()
    {
        if (!$this->currency) {
            return number_format($this->value, 2, '.', '');
        }

        $result = '';

        if ($this->currency->hasLeftSymbol()) {
            $result .= $this->currency->getLeftSymbol() . ' ';
        }

        $result .= number_format(
            $this->value,
            $this->currency->getDecimalDigits(),
            $this->currency->getDecimalSeparator(),
            $this->currency->getThousandsSeparator()
        );

        if ($this->currency->hasRightSymbol()) {
            $result .= ' ' . $this->currency->getRightSymbol();
        }

        return $result;
    }
}
