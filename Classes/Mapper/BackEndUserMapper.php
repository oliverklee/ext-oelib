<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Mapper;

use OliverKlee\Oelib\Model\BackEndUser;

/**
 * @extends AbstractDataMapper<BackEndUser>
 *
 * @deprecated #1928 will be removed in version 7.0
 */
class BackEndUserMapper extends AbstractDataMapper
{
    protected $tableName = 'be_users';

    protected $modelClassName = BackEndUser::class;
}
