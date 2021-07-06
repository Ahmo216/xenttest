<?php

declare(strict_types=1);

namespace App\Core\Logging;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class LoggingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind('sentry.context', SentryContext::class);
    }
}
