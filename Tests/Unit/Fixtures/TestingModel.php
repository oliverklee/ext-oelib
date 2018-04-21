<?php

/**
 * This class represents a domain model for testing purposes.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
final class Tx_Oelib_Tests_Unit_Fixtures_TestingModel extends Tx_Oelib_Model
{
    /**
     * Sets the "title" data item for this model.
     *
     * @param string $value
     *        the value to set, may be empty
     *
     * @return void
     */
    public function setTitle($value)
    {
        $this->setAsString('title', $value);
    }

    /**
     * Gets the "title" data item.
     *
     * @return string the value of the "title" data item, may be empty
     */
    public function getTitle()
    {
        return $this->getAsString('title');
    }

    /**
     * Sets the "header" data item for this model.
     *
     * @param string $value
     *        the value to set, may be empty
     */
    public function setHeader($value)
    {
        $this->setAsString('header', $value);
    }

    /**
     * Gets the "header" data item.
     *
     * @return string the value of the "header" data item, may be empty
     */
    public function getHeader()
    {
        return $this->getAsString('header');
    }

    /**
     * Checks whether a data item with a certain key exists.
     *
     * @param string $key
     *        the key of the data item to check, must not be empty
     *
     * @return bool TRUE if a data item with the key $key exists, FALSE
     *                 otherwise
     */
    public function existsKey($key)
    {
        return parent::existsKey($key);
    }

    /**
     * Gets the value stored in under the key $key as a model.
     *
     * @throws UnexpectedValueException
     *         if there is a data item stored for the key $key that is not a model instance
     *
     * @param string $key
     *        the key of the element to retrieve, must not be empty
     *
     * @return Tx_Oelib_Model the data item for the given key, will be NULL if
     *                        it has not been set
     */
    public function getAsModel($key)
    {
        return parent::getAsModel($key);
    }

    /**
     * Gets the value stored in under the key $key, converted to a boolean.
     *
     * @param string $key
     *        the key of the element to retrieve, must not be empty
     *
     * @return bool the boolean value of the given key
     */
    public function getAsBoolean($key)
    {
        return parent::getAsBoolean($key);
    }

    /**
     * Gets the value stored in under the key $key, converted to an integer.
     *
     * @param string $key
     *        the key of the element to retrieve, must not be empty
     *
     * @return int the integer value of the given key, may be positive,
     *                 negative or zero
     */
    public function getAsInteger($key)
    {
        return parent::getAsInteger($key);
    }

    /**
     * Gets the "friend" data item. This is an n:1 relation.
     *
     * @return Tx_Oelib_Tests_Unit_Fixtures_TestingModel
     */
    public function getFriend()
    {
        return $this->getAsModel('friend');
    }

    /**
     * Sets the "friend" data item. This is an n:1 relation.
     *
     * @param Tx_Oelib_Tests_Unit_Fixtures_TestingModel $friend
     *
     * @return void
     */
    public function setFriend(Tx_Oelib_Tests_Unit_Fixtures_TestingModel $friend)
    {
        $this->set('friend', $friend);
    }

    /**
     * Gets the "owner" data item. This is an n:1 relation.
     *
     * @return Tx_Oelib_Model_FrontEndUser
     */
    public function getOwner()
    {
        return $this->getAsModel('owner');
    }

    /**
     * Gets the "children" data item. This is a 1:n relation.
     *
     * @return Tx_Oelib_List<Tx_Oelib_Model> the "children" data item, will be empty (but not
     *                       NULL) if this model has no children
     */
    public function getChildren()
    {
        return $this->getAsList('children');
    }

    /**
     * Gets the "related_records" data item. This is an m:n relation.
     *
     * @return Tx_Oelib_List<Tx_Oelib_Model> the "related_records" data item, will be empty (but
     *                       not NULL) if this model has no related records
     */
    public function getRelatedRecords()
    {
        return $this->getAsList('related_records');
    }

    /**
     * Adds a related record.
     *
     * @param Tx_Oelib_Tests_Unit_Fixtures_TestingModel $record
     *
     * @return void
     */
    public function addRelatedRecord(Tx_Oelib_Tests_Unit_Fixtures_TestingModel $record)
    {
        $this->getRelatedRecords()->add($record);
    }

    /**
     * Gets the "bidirectional" data item. This is an m:n relation.
     *
     * @return Tx_Oelib_List<Tx_Oelib_Model> the "bidirectional" data item, will be empty (but
     *                       not NULL) if this model has no related records
     */
    public function getBidirectional()
    {
        return $this->getAsList('bidirectional');
    }

    /**
     * Gets the "composition" data item. This is an 1:n relation.
     *
     * @return Tx_Oelib_List<Tx_Oelib_Tests_Unit_Fixtures_TestingChildModel>
     */
    public function getComposition()
    {
        return $this->getAsList('composition');
    }

    /**
     * Adds $model to the "compositon" relation.
     *
     * @param Tx_Oelib_Tests_Unit_Fixtures_TestingChildModel $model
     *
     * @return void
     */
    public function addCompositionRecord(Tx_Oelib_Tests_Unit_Fixtures_TestingChildModel $model)
    {
        $this->getComposition()->add($model);
    }

    /**
     * Sets the "composition" data item. This is an 1:n relation.
     *
     * @param Tx_Oelib_List $components Tx_Oelib_List<Tx_Oelib_Tests_Unit_Fixtures_TestingChildModel>
     *
     * @return void
     */
    public function setComposition(Tx_Oelib_List $components)
    {
        $this->set('composition', $components);
    }

    /**
     * Gets the "composition2" data item. This is an 1:n relation.
     *
     * @return Tx_Oelib_List<Tx_Oelib_Model> the "composition2" data item, will be empty (but
     *                       not NULL) if this model has no composition2
     */
    public function getComposition2()
    {
        return $this->getAsList('composition2');
    }

    /**
     * Sets the "composition2" data item. This is an 1:n relation.
     *
     * @param Tx_Oelib_List <Tx_Oelib_Model> $components
     *                      the "composition2" data to set
     *
     * @return void
     */
    public function setComposition2(Tx_Oelib_List $components)
    {
        $this->set('composition2', $components);
    }

    /**
     * Sets the deleted property via set().
     *
     * Note: This function is expected to fail.
     *
     * @return void
     */
    public function setDeletedPropertyUsingSet()
    {
        $this->setAsBoolean('deleted', true);
    }

    /**
     * Sets the dummy column to TRUE.
     *
     * @return void
     */
    public function markAsDummyModel()
    {
        $this->set('is_dummy_record', true);
    }

    /**
     * Gets the data from the "float_data" column.
     *
     * @return float the data from the "float_data" column
     */
    public function getFloatFromFloatData()
    {
        return $this->getAsFloat('float_data');
    }

    /**
     * Gets the data from the "decimal_data" column.
     *
     * @return float the data from the "decimal_data" column
     */
    public function getFloatFromDecimalData()
    {
        return $this->getAsFloat('decimal_data');
    }

    /**
     * Gets the data from the "string_data" column.
     *
     * @return float the data from the "string_data" column
     */
    public function getFloatFromStringData()
    {
        return $this->getAsFloat('string_data');
    }

    /**
     * Marks this model as read-only.
     *
     * @return void
     */
    public function markAsReadOnly()
    {
        $this->readOnly = true;
    }

    /**
     * Sets this model's load status.
     *
     * @param int $status
     *
     * @return void
     */
    public function setLoadStatus($status)
    {
        parent::setLoadStatus($status);
    }
}
