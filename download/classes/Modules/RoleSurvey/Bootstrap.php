<?php
declare(strict_types=1);

namespace Xentral\Modules\RoleSurvey;


use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'SurveyGateway' => 'onInitSurveyGateway',
            'SurveyService' => 'onInitSurveyService',
        ];
    }

    public static function registerJavascript(): array
    {
        return [
            'RoleSurvey' => [
                './classes/Modules/RoleSurvey/www/js/RoleSurvey.js',
            ],
        ];
    }

   public static function registerStylesheets(): array
    {
        return [
            'RoleSurvey' => [
                './classes/Modules/RoleSurvey/www/css/RoleSurvey.css',
            ],
        ];
    }

    public static function onInitSurveyService(ContainerInterface $container): SurveyService
    {
        return new SurveyService($container->get('Database'), $container->get('SurveyGateway'));
    }

    public static function onInitSurveyGateway(ContainerInterface $container): SurveyGateway
    {
        return new SurveyGateway($container->get('Database'));
    }
}
