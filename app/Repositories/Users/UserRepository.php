<?php

namespace App\Repositories\Users;

use App\Entities\User;
use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param array $columns
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getTechnicalManagers(array $columns = ['*'])
    {
        return $this->withMachines($this->model::role(\App\Domain\Roles::TECHNICAL))->get($columns);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function getAllUsers(array $columns = ['*'])
    {
        return $this->model::role([
            \App\Domain\Roles::ADMINISTRATOR,
            \App\Domain\Roles::EMPLOYEE,
            \App\Domain\Roles::VISITOR
        ])->get($columns);
    }

    /**
     * @param Builder $model
     * @return Builder
     */
    private function withMachines(Builder $model)
    {
        return $model->with(['machines' => function ($query) {
            $query->select('id', 'name');
        }]);
    }
}
