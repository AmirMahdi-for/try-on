<?php

namespace TryOn\Providers;

use Illuminate\Support\ServiceProvider;
use TryOn\Repositories\v1\Interfaces\TryOnRepositoryInterface;
use TryOn\Repositories\v1\TryOnRepository;
use TryOn\Repositories\v2\Interfaces\TryOnRepositoryInterface as TryOnRepositoryInterfaceV2;
use TryOn\Repositories\v2\TryOnRepository as TryOnRepositoryV2;

class TryOnServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/try-on.php', 'try-on');
        
        $this->app->bind(TryOnRepositoryInterface::class, TryOnRepository::class);
        $this->app->bind(TryOnRepositoryInterfaceV2::class, TryOnRepositoryV2::class);

    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/try-on.php' => config_path('try-on.php'),
        ], 'config');
        
        // $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
