<?php

namespace App\Core\Http;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->booted(function () {
            if ($this->app->routesAreCached()) {
                $this->app->booted(function () {
                    require $this->app->getCachedRoutesPath();
                });
            } else {
                $this->map();

                $this->app->booted(function () {
                    $this->app['router']->getRoutes()->refreshNameLookups();
                    $this->app['router']->getRoutes()->refreshActionLookups();
                });
            }
        });
    }

    public function map(): void
    {
        // Currently disabled
        //$this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
