<?php

namespace Xentral\Components\Sanitizer;

use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'HtmlMailSanitizer' => 'onInitHtmlMailSanitizer',
        ];
    }

    public static function onInitHtmlMailSanitizer(ContainerInterface $container): HtmlMailSanitizer
    {
        /** @var \Application $app */
        $app = $container->get('LegacyApplication');
        $tempDir = realpath($app->erp->GetTMP()) . DIRECTORY_SEPARATOR . 'HtmlPurifier';
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777);
        }

        // Administration > Grundeinstellungen > System > Sicherheit > "Externe URL im Ticketsystem nicht laden"
        $externeurlsblockieren = (int)$app->erp->Firmendaten('externeurlsblockieren');
        $disableExternal = $externeurlsblockieren === 1;
        $disableExternalResources = $externeurlsblockieren === 1;

        $hostname = $_SERVER['HTTP_HOST']; // Hostname ohne http/https
        $redirectUrl = './index.php?module=welcome&action=redirect&url=%s';
        $moduleActionWhitelist = [
            ['module' => 'dateien', 'action' => 'send'], // Benötigt für Ticketsystem zur Anzeige von Dateianhängen
        ];

        $config = new SanitizerConfig(
            $disableExternal,
            $disableExternalResources,
            $hostname,
            $redirectUrl,
            $moduleActionWhitelist
        );

        $config->setTempDir($tempDir);

        return new HtmlMailSanitizer($config);
    }
}
