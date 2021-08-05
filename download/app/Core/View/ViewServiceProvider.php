<?php

namespace App\Core\View;

use App\Core\View\Components\LicenseNotification;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ViewServiceProvider as BaseViewServiceProvider;

class ViewServiceProvider extends BaseViewServiceProvider
{
    public function boot(BladeCompiler $compiler): void
    {
        $compiler->component(LicenseNotification::class, 'license-notification');
    }
}
