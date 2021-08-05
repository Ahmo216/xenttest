<?php

declare(strict_types=1);

namespace App\Core\Helpers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AppVersion::class);
    }
}
