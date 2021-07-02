<?php

namespace App\Core\Translations;

use App\Core\Translations\Commands\DumpTranslationFilesCommand;
use Illuminate\Support\ServiceProvider;

class TranslationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([DumpTranslationFilesCommand::class]);
        }
    }
}
