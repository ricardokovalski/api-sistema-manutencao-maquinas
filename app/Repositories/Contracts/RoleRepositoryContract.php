<?php

namespace App\Repositories\Contracts;

/**
 * Interface RoleRepositoryContract
 * @package App\Repositories\Contracts
 */
interface RoleRepositoryContract
{
    public function all(array $columns = ['*']);

    public function create(array $attributes = []): \Spatie\Permission\Contracts\Role;

    public function findOrCreate(string $name): \Spatie\Permission\Contracts\Role;

    public function findById(int $id, array $columns = ['*']): \Spatie\Permission\Contracts\Role;

    public function findByName(string $name): \Spatie\Permission\Contracts\Role;
}
