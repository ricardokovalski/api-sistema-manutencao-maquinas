<?php

namespace App\Repositories\Permissions;

use App\Repositories\Contracts\PermissionRepositoryContract;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRepository
 * @package App\Repositories\Permissions
 */
class PermissionRepository implements PermissionRepositoryContract
{
    /**
     * @var Permission
     */
    protected $model;

    /**
     * PermissionRepository constructor.
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function create(array $attributes = []): \Spatie\Permission\Contracts\Permission
    {
        return $this->model::create($attributes);
    }

    /**
     * @param string $name
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function findOrCreate(string $name): \Spatie\Permission\Contracts\Permission
    {
        return $this->model::findOrCreate($name);
    }

    /**
     * @param int $id
     * @param array $columns
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function findById(int $id, array $columns = ['*']): \Spatie\Permission\Contracts\Permission
    {
        return $this->model::find($id, $columns);
    }

    /**
     * @param string $name
     * @return \Spatie\Permission\Contracts\Permission
     */
    public function findByName(string $name): \Spatie\Permission\Contracts\Permission
    {
        return $this->model::findByName($name);
    }
}
