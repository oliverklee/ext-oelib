<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Mapper;

use OliverKlee\Oelib\Model\FrontEndUserGroup;

/**
 * @extends AbstractDataMapper<FrontEndUserGroup>
 *
 * @deprecated #1928 will be removed in version 7.0
 */
class FrontEndUserGroupMapper extends AbstractDataMapper
{
    protected $tableName = 'fe_groups';

    protected $modelClassName = FrontEndUserGroup::class;
}
