<?php

namespace TryOn\Providers;

use TryOn\Repositories\Interfaces\TryOnRepositoryInterface;
use TryOn\Repositories\TryOnRepository;
use Illuminate\Support\ServiceProvider;

class TryOnServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/try-on.php', 'try-on');
        
        $this->app->bind(TryOnRepositoryInterface::class, TryOnRepository::class);
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
