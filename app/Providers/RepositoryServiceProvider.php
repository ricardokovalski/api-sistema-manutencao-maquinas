<?php

namespace App\Providers;

use App\Repositories\Audit\AuditRepository;
use App\Repositories\Contracts\AuditRepositoryContract;
use App\Repositories\Contracts\FileRepositoryContract;
use App\Repositories\Contracts\MachineRepositoryContract;
use App\Repositories\Contracts\MaintenanceRepositoryContract;
use App\Repositories\Contracts\PeaceRepositoryContract;
use App\Repositories\Contracts\PermissionRepositoryContract;
use App\Repositories\Contracts\ReviewTypeRepositoryContract;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Files\FileRepository;
use App\Repositories\Machines\MachineRepository;
use App\Repositories\Maintenance\MaintenanceRepository;
use App\Repositories\Peaces\PeaceRepository;
use App\Repositories\Permissions\PermissionRepository;
use App\Repositories\ReviewTypes\ReviewTypeRepository;
use App\Repositories\Roles\RoleRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(AuditRepositoryContract::class, AuditRepository::class);

        $this->app->bind(FileRepositoryContract::class, FileRepository::class);

        $this->app->bind(MachineRepositoryContract::class, MachineRepository::class);

        $this->app->bind(MaintenanceRepositoryContract::class, MaintenanceRepository::class);

        $this->app->bind(PeaceRepositoryContract::class, PeaceRepository::class);

        $this->app->bind(PermissionRepositoryContract::class, PermissionRepository::class);

        $this->app->bind(ReviewTypeRepositoryContract::class, ReviewTypeRepository::class);

        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);

        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }
}