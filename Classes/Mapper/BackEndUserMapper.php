<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Mapper;

use OliverKlee\Oelib\Exception\NotFoundException;
use OliverKlee\Oelib\Model\BackEndUser;

/**
 * @extends AbstractDataMapper<BackEndUser>
 */
class BackEndUserMapper extends AbstractDataMapper
{
    protected $tableName = 'be_users';

    protected $modelClassName = BackEndUser::class;

    protected $relations = [
        // @deprecated will be removed in oelib 6.0
        'usergroup' => BackEndUserGroupMapper::class,
    ];

    /**
     * @deprecated #1505 will be removed in oelib 6.0.0
     */
    protected $additionalKeys = ['username'];

    /**
     * Finds a back-end user by username. Hidden user records will be retrieved
     * as well.
     *
     * @param non-empty-string $username username, case-insensitive
     *
     * @return BackEndUser model of the back-end user with the provided username
     *
     * @throws NotFoundException if there is no back-end user with the provided username in the be_user table
     *
     * @deprecated #1505 will be removed in oelib 6.0.0
     */
    public function findByUserName(string $username): BackEndUser
    {
        /** @var BackEndUser $result */
        $result = $this->findOneByKey('username', $username);

        return $result;
    }
}
