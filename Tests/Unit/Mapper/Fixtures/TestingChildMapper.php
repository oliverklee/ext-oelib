<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures;

use OliverKlee\Oelib\Mapper\AbstractDataMapper;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingChildModel;

/**
 * This class represents a mapper for a testing child model.
 *
 * @extends AbstractDataMapper<TestingChildModel>
 */
class TestingChildMapper extends AbstractDataMapper
{
    /**
     * @var string the name of the database table for this mapper
     */
    protected $tableName = 'tx_oelib_testchild';

    /**
     * @var class-string<TestingChildModel> the model class name for this mapper, must not be empty
     */
    protected $modelClassName = TestingChildModel::class;

    /**
     * @var array<non-empty-string, class-string>
     *      the (possible) relations of the created models in the format DB column name => mapper name
     */
    protected $relations = [
        'parent' => TestingMapper::class,
        'tx_oelib_parent2' => TestingMapper::class,
        'tx_oelib_parent3' => TestingMapper::class,
    ];
}
