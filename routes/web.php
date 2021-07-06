<?php

use App\Core\Http\BootLegacyApplication;
use Illuminate\Support\Facades\Route;

Route::any('/{view}', BootLegacyApplication::class)
  ->where('view', '.*')
  ->name('legacy-router');
