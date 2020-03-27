<?php

namespace App\Repositories\Contracts;

/**
 * Interface PermissionRepositoryContract
 * @package App\Repositories\Contracts
 */
interface PermissionRepositoryContract
{
    public function all(array $columns = ['*']);

    public function create(array $attributes = []): \Spatie\Permission\Contracts\Permission;

    public function findOrCreate(string $name): \Spatie\Permission\Contracts\Permission;

    public function findById(int $id, array $columns = ['*']): \Spatie\Permission\Contracts\Permission;

    public function findByName(string $name): \Spatie\Permission\Contracts\Permission;
}
