<?php

namespace Pro1\Changelog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Pro1\Changelog\Http\Middleware\WhatsNewMid;

class ChangelogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load package routes
        // $this->loadRoutesFrom(__DIR__.'/routes/web.php');


        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'changelogs');

        // Publish assets
        $this->publishes([
            __DIR__.'/../public/' => public_path('vendor/pro1/changelog'),
        ], 'public');


        Route::middleware('api')
        ->prefix('api')
        ->group(__DIR__.'/../src/routes/api.php');

        Route::middleware(['web', 'auth'])
        ->group(__DIR__.'/../src/routes/web.php');

        $this->publishes([
            __DIR__.'/../database/seeders/ChangeTypeSeeder.php' => database_path('seeders/ChangeTypeSeeder.php'),
            __DIR__.'/../database/seeders/PriorityLevelSeeder.php' => database_path('seeders/PriorityLevelSeeder.php'),
            __DIR__.'/../database/seeders/ReleaseTypeSeeder.php' => database_path('seeders/ReleaseTypeSeeder.php'),
            __DIR__.'/../database/seeders/StatusSeeder.php' => database_path('seeders/StatusSeeder.php'),
        ], 'changelog-seeders');


        $this->app->make(Router::class)
        ->pushMiddlewareToGroup('web', WhatsNewMid::class);

        // Publish config, views, assets
        // $this->publishes([
        //     __DIR__.'/../config/changelog.php' => config_path('changelog.php'),
        // ], 'config');
    }

    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/changelog.php',
            'changelog'
        );
    }
}
