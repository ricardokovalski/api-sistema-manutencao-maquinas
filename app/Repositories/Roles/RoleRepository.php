<?php

namespace App\Repositories\Roles;

use App\Repositories\Contracts\RoleRepositoryContract;
use Spatie\Permission\Models\Role;

/**
 * Class RoleRepository
 * @package App\Repositories\Roles
 */
class RoleRepository implements RoleRepositoryContract
{
    /**
     * @var Role
     */
    protected $model;

    /**
     * RoleRepository constructor.
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Role[]
     */
    public function all(array $columns = ['*'])
    {
        return $this->model::all($columns);
    }

    /**
     * @param array $attributes
     * @return \Spatie\Permission\Contracts\Role
     */
    public function create(array $attributes = []): \Spatie\Permission\Contracts\Role
    {
        return $this->model::create($attributes);
    }

    /**
     * @param string $name
     * @return \Spatie\Permission\Contracts\Role
     */
    public function findOrCreate(string $name): \Spatie\Permission\Contracts\Role
    {
        return $this->model::findOrCreate($name);
    }

    /**
     * @param int $id
     * @param array $columns
     * @return \Spatie\Permission\Contracts\Role
     */
    public function findById(int $id, array $columns = ['*']): \Spatie\Permission\Contracts\Role
    {
        return $this->model::findById($id)
            ->take(1)
            ->get($columns)
            ->first();
    }

    /**
     * @param string $name
     * @return \Spatie\Permission\Contracts\Role
     */
    public function findByName(string $name): \Spatie\Permission\Contracts\Role
    {
        return $this->model::findByName($name);
    }
}
