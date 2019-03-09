<?php

/**
 * Repository for GermanZipCode models.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de
 */
class Tx_Oelib_Domain_Repository_GermanZipCodeRepository extends \Tx_Extbase_Persistence_Repository
{
    /**
     * @var \Tx_Oelib_Domain_Model_GermanZipCode[]|null[]
     */
    protected $cachedResults = array();

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var \Tx_Extbase_Persistence_QuerySettingsInterface $querySettings */
        $querySettings = $this->objectManager->get('Tx_Extbase_Persistence_QuerySettingsInterface');
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param string $zipCode
     *
     * @return \Tx_Oelib_Domain_Model_GermanZipCode|null
     */
    public function findOneByZipCode($zipCode)
    {
        if (!\preg_match('/^\\d{5}$/', $zipCode)) {
            return null;
        }
        if (\array_key_exists($zipCode, $this->cachedResults)) {
            return $this->cachedResults[$zipCode];
        }

        $query = $this->createQuery();
        $result = $query->matching($query->equals('zipCode', $zipCode))->setLimit(1)->execute();

        /** @var \Tx_Oelib_Domain_Model_GermanZipCode|null $firstMatch */
        $firstMatch = $result->getFirst();
        $this->cachedResults[$zipCode] = $firstMatch;

        return $firstMatch;
    }

    /**
     * Adds an object to this repository.
     *
     * @param object $object The object to add
     *
     * @return void
     *
     * @throws \BadMethodCallException
     */
    public function add($object)
    {
        $this->preventWriteOperation();
    }

    /**
     * Removes an object from this repository.
     *
     * @param object $object The object to remove
     *
     * @return void
     *
     * @throws \BadMethodCallException
     */
    public function remove($object)
    {
        $this->preventWriteOperation();
    }

    /**
     * Replaces an existing object with the same identifier by the given object.
     *
     * @param object $modifiedObject The modified object
     *
     * @return void
     *
     * @throws \BadMethodCallException
     */
    public function update($modifiedObject)
    {
        $this->preventWriteOperation();
    }

    /**
     * Removes all objects of this repository as if remove() was called for all of them.
     *
     * @return void
     *
     * @throws \BadMethodCallException
     */
    public function removeAll()
    {
        $this->preventWriteOperation();
    }

    /**
     * @return void
     *
     * @throws \BadMethodCallException
     */
    private function preventWriteOperation()
    {
        throw new \BadMethodCallException(
            'This is a read-only repository in which the removeAll method must not be called.',
            1537544385
        );
    }
}
