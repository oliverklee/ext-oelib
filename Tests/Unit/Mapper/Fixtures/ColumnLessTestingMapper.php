<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures;

use OliverKlee\Oelib\Mapper\AbstractDataMapper;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingModel;

/**
 * This class represents a mapper that is broken because it has no columns defined.
 *
 * @extends AbstractDataMapper<TestingModel>
 */
class ColumnLessTestingMapper extends AbstractDataMapper
{
    /**
     * @var non-empty-string the name of the database table for this mapper
     */
    protected $tableName = 'tx_oelib_test';

    /**
     * @var non-empty-string a comma-separated list of DB column names to retrieve or "*" for all columns,
     *      must not be empty
     *
     * @phpstan-ignore-next-line We are explicitly testing for a contract violation here.
     */
    protected $columns = '';

    /**
     * @var class-string<TestingModel> the model class name for this mapper, must not be empty
     */
    protected $modelClassName = TestingModel::class;
}
