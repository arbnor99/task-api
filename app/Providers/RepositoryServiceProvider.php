<?php

namespace App\Providers;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\TaskCacheRepository;
use App\Repositories\TaskEloquentRepository;
use Illuminate\Contracts\Cache\Repository;
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
        $this->app->bind(TaskRepositoryInterface::class, function() {
                return new TaskCacheRepository(
                    $this->app->make(Repository::class),
                    new TaskEloquentRepository()
                );
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
