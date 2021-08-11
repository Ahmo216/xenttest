<?php

if (!is_file(__DIR__ . '/../conf/user.inc.php')){
    header('Location: ./setup/setup.php');
    exit();
}

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../xentral_autoloader.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/


$app = require_once __DIR__ . '/../bootstrap/app.php';

// The sessions needs to started before any legacy app will be instantiated.
// Because of this you find this here :)
if(!isset($_GET['module']) || $_GET['module'] != 'api')
{
    if(!(
        isset($_GET['module']) && isset($_GET['action']) && isset($_GET['cmd']) && $_GET['module'] == 'welcome' && (($_GET['action'] == 'login' && $_GET['cmd'] == 'checkrfid') || $_GET['action'] == 'adapterbox')))
        @session_start();
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
