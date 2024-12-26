Using the price view helper
^^^^^^^^^^^^^^^^^^^^^^^^^^^

#. Create a price view helper object:

   ::

      $priceViewHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(PriceViewHelper::class);

#. Set the currency of the price using the ISO 4217 alpha-3 code:

   ::

      $priceViewHelper->setCurrencyFromIsoAlpha3Code('EUR');

#. Set the value of the price:

   ::

      $priceViewHelper->setValue(1234.567);

#. Render the price in the currency and the format as defined in ISO
   4217:

   ::

      // Returns “€ 1.234,57”.
      $priceViewHelper->render();

