<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures;

use OliverKlee\Oelib\Exception\NotFoundException;
use OliverKlee\Oelib\Mapper\AbstractDataMapper;
use OliverKlee\Oelib\Mapper\FrontEndUserMapper;
use OliverKlee\Oelib\Mapper\IdentityMap;
use OliverKlee\Oelib\Model\AbstractModel;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingModel;

/**
 * @extends AbstractDataMapper<TestingModel>
 */
class TestingMapper extends AbstractDataMapper
{
    /**
     * @var string the name of the database table for this mapper
     */
    protected $tableName = 'tx_oelib_test';

    /**
     * @var class-string<TestingModel> the model class name for this mapper, must not be empty
     */
    protected $modelClassName = TestingModel::class;

    /**
     * @var array<string, class-string<AbstractDataMapper>>
     *      the (possible) relations of the created models in the format DB column name => mapper name
     */
    protected $relations = [
        'friend' => TestingMapper::class,
        'owner' => FrontEndUserMapper::class,
        'children' => TestingMapper::class,
        'related_records' => TestingMapper::class,
        'composition' => TestingChildMapper::class,
        'composition2' => TestingChildMapper::class,
        'composition_without_sorting' => TestingChildMapper::class,
        'bidirectional' => TestingMapper::class,
    ];

    /**
     * @var array<int, string> the column names of additional string keys
     */
    protected $additionalKeys = ['title'];

    /**
     * @var string[] the column names of an additional compound key
     */
    protected $compoundKeyParts = ['title', 'header'];

    /**
     * @var AbstractModel[]
     */
    protected $cachedModels = [];

    /**
     * Gets the cached models.
     *
     * This function is intended for testing whether models have been cached.
     *
     * @return AbstractModel[]
     */
    public function getCachedModels(): array
    {
        return $this->cachedModels;
    }

    /**
     * Sets the map for this mapper.
     */
    public function setMap(IdentityMap $map): void
    {
        $this->map = $map;
    }

    /**
     * Retrieves a model based on the WHERE clause given in the parameter
     * $whereClauseParts. Hidden records will be retrieved as well.
     *
     * @param array<string, string|int> $whereClauseParts
     *        WHERE clause parts for the record to retrieve, each element must
     *        consist of a column name as key and a value to search for as value
     *        (will automatically get quoted), must not be empty
     *
     * @return AbstractModel
     *
     * @throws NotFoundException if there is no record in the DB which matches the WHERE clause
     */
    public function findSingleByWhereClause(array $whereClauseParts): AbstractModel
    {
        return parent::findSingleByWhereClause($whereClauseParts);
    }

    /**
     * Sets the model class name.
     *
     * @param class-string<TestingModel> $className model class name, must not be empty
     */
    public function setModelClassName(string $className): void
    {
        $this->modelClassName = $className;
    }

    /**
     * Processes a model's data and creates any relations that are hidden within
     * it using foreign key mapping.
     *
     *        the model data to process, might be modified
     *
     * @param TestingModel $model
     *        the model to create the relations for
     */
    public function createRelations(array &$data, AbstractModel $model): void
    {
        parent::createRelations($data, $model);
    }

    /**
     * Looks up a model in the cache by key.
     *
     * When this function reports "no match", the model could still exist in the
     * database, though.
     *
     * @param string $key an existing key, must not be empty
     * @param string $value
     *        the value for the key of the model to find, must not be empty
     *
     * @return AbstractModel the cached model
     *
     * @throws NotFoundException if there is no match in the cache yet
     */
    public function findOneByKeyFromCache(string $key, string $value): AbstractModel
    {
        return parent::findOneByKeyFromCache($key, $value);
    }

    /**
     * Caches a model by an additional compound key.
     *
     * This method needs to be overwritten in subclasses to work. However, it is recommended to use
     * cacheModelByCompoundKey instead. So this method primarily is here for backwards compatibility.
     *
     * @param AbstractModel $model the model to cache
     * @param string[] $data the data of the model as it is in the DB, may be empty
     *
     * @see cacheModelByCompoundKey
     */
    protected function cacheModelByCombinedKeys(AbstractModel $model, array $data): void
    {
        $this->cachedModels[] = $model;
    }
}
