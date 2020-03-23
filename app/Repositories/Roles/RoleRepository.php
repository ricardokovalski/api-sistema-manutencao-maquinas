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
     * @return \Spatie\Permission\Contracts\Role
     */
    public function findById(int $id): \Spatie\Permission\Contracts\Role
    {
        return $this->model::findById($id);
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