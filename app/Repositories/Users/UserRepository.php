<?php

namespace App\Repositories\Users;

use App\Entities\User;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
//use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Roles\RoleRepository;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository implements UserRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /*public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }*/

    public function getTechnicalManagers(array $columns = ['*'])
    {
        return $this->model::role(app(RoleRepository::class)->findById(3, ['name'])->name);
    }
}
