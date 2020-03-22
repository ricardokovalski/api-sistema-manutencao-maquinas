<?php

namespace App\Repositories\Roles;

use App\Repositories\Contracts\RoleRepositoryContract;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\Permission\Models\Role;

/**
 * Class RoleRepository
 * @package App\Repositories\Roles
 */
class RoleRepository extends BaseRepository implements RoleRepositoryContract
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    public function findById($id) {
        $model = $this->model();
        return $model::findById($id);
    }
}
