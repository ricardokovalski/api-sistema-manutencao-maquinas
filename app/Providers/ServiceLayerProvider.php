<?php

namespace App\Providers;

use App\Services\AuditService;
use App\Services\Contracts\AuditServiceContract;
use App\Services\Contracts\DashboardServiceContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\Contracts\MachineServiceContract;
use App\Services\DashboardService;
use App\Services\Files\FileService;
use App\Services\Machines\MachineService;
use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
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
        $this->app->bind(AuditServiceContract::class, AuditService::class);

        $this->app->bind(DashboardServiceContract::class, DashboardService::class);

        $this->app->bind(FileServiceContract::class, FileService::class);

        $this->app->bind(MachineServiceContract::class, MachineService::class);
    }
}