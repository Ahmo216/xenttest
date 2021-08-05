<?php

namespace App\Core\Http;

use erpooSystem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Player;
use Session;
use Welcome;
use Xentral\Components\EnvironmentConfig\EnvironmentConfig;

class BootLegacyApplication
{
    public function __invoke(Request $request, erpooSystem $legacyApp, EnvironmentConfig $environmentConfig)
    {
        $module = $this->sanitizeModule($request->query('module'));
        $action = $this->sanitizeAction($request->query('action'));

        $session = new Session();
        $player = new Player($legacyApp, $environmentConfig);
        $session->Check($legacyApp);

        if ($session->GetCheck() === false && $session->reason === 'PLEASE_LOGIN') {
            /** @var Welcome $welcomeModule */
            $welcomeModule = $legacyApp->loadModule('welcome');

            return response($welcomeModule->WelcomeLogin());
        }
        if ($module === 'welcome' && $action === 'passwortvergessen') {
            /** @var Welcome $welcomeModule */
            $welcomeModule = $legacyApp->loadModule('welcome');

            return response($welcomeModule->Welcomepasswortvergessen());
        }

        if ($module === 'welcome' && $action === '') {
            $action = 'start';
        }

        $view = $player->Run($module, $action);

        if ($view instanceof View) {
            return response($view);
        }

        return null;
    }

    private function sanitizeModule(?string $module): string
    {
        if ($module === null) {
            return 'welcome';
        }

        return $this->sanitize($module);
    }

    private function sanitizeAction(?string $action): string
    {
        if ($action === null) {
            return '';
        }

        return $this->sanitize($action);
    }

    private function sanitize(string $string): string
    {
        return preg_replace('/[^a-zA-Z0-9_]+/', '', $string);
    }
}
