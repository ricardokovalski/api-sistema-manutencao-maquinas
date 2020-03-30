<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepositoryContract
 * @package App\Repositories\Contracts
 */
interface UserRepositoryContract extends RepositoryInterface
{
    public function getTechnicalManagers(array $columns = ['*']);

    public function getAllUsers(array $columns = ['*']);
}
