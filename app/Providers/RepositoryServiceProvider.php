<?php

namespace App\Providers;

use App\Repositories\Contracts\MachineRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Machines\MachineRepository;
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
        $this->app->bind(MachineRepositoryContract::class, MachineRepository::class);

        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }
}