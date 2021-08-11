<?php

namespace Xentral\Modules\Hubspot;

use ApplicationCore;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\Country\Gateway\StateGateway;
use Xentral\Modules\Hubspot\RequestQueues\HubspotRequestQueuesGateway;
use Xentral\Modules\Hubspot\RequestQueues\HubspotRequestQueuesService;
use Xentral\Modules\Hubspot\Scheduler\HubspotProcessSchedulerTask;
use Xentral\Modules\Hubspot\Scheduler\HubspotPullContactsTask;
use Xentral\Modules\Hubspot\Scheduler\HubspotPullDealsTask;
use Xentral\Modules\Hubspot\Scheduler\HubspotPullEngagementsTask;
use Xentral\Modules\Hubspot\Validators\ContactValidator;
use Xentral\Modules\Hubspot\Validators\DealValidator;
use Xentral\Modules\SubscriptionCycle\Scheduler\TaskMutexService;

final class Bootstrap
{
    public static function registerServices(): array
    {
        return [
            'HubspotContactService'           => 'onInitHubspotContactService',
            'HubspotDealService'              => 'onInitHubspotDealService',
            'HubspotClientService'            => 'onInitHubspotClientService',
            'HubspotHttpClientService'        => 'onInitHubspotHttpClientService',
            'HubspotConfigurationService'     => 'onInitHubspotConfigurationService',
            'HubspotPullContactsTask'         => 'onInitHubspotPullContactsTask',
            'HubspotContactGateway'           => 'onInitHubspotContactGateway',
            'HubspotDealGateway'              => 'onInitHubspotDealGateway',
            'HubspotContactPropertyService'   => 'onInitHubspotContactPropertyService',
            'HubspotContactPropertyGateway'   => 'onInitHubspotContactPropertyGateway',
            'HubspotPullDealsTask'            => 'onInitHubspotPullDealsTask',
            'HubspotDealPropertyService'      => 'onInitHubspotDealPropertyService',
            'HubspotProcessSchedulerTask'     => 'onInitHubspotProcessSchedulerTask',
            'HubspotRequestQueuesGateway'     => 'onInitRequestQueuesGateway',
            'HubspotRequestQueuesService'     => 'onInitRequestQueuesService',
            'HubspotEventService'             => 'onInitHubspotEventService',
            HubspotEngagementService::class   => 'onInitHubspotEngagementService',
            HubspotPullEngagementsTask::class => 'onInitHubspotPullEngagementsTask',
        ];
    }

    public static function onInitHubspotContactService(ContainerInterface $container): HubspotContactService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotContactService(
            $container->get('HubspotClientService'),
            new HubspotMetaService($app->erp->GetTMP()),
            new ContactValidator(),
            $container->get('HubspotConfigurationService')
        );
    }

    public static function onInitHubspotClientService(ContainerInterface $container): HubspotClientService
    {
        return new HubspotClientService(
            $container->get('HubspotHttpClientService'),
            $container->get('HubspotConfigurationService')
        );
    }

    public static function onInitHubspotHttpClientService(): HubspotHttpClientService
    {
        return new HubspotHttpClientService(30);
    }

    public static function onInitHubspotConfigurationService(ContainerInterface $container): HubspotConfigurationService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotConfigurationService(
            $app->erp,
            new HubspotMetaService($app->erp->GetTMP()),
            $container->get('HubspotContactPropertyGateway'),
            $container->get('HubspotDealGateway'),
            $container->get('CountryGateway'),
            $container->get(StateGateway::class)
        );
    }

    public static function onInitHubspotContactGateway(ContainerInterface $container): HubspotContactGateway
    {
        return new HubspotContactGateway($container->get('Database'), $container->get('HubspotConfigurationService'));
    }

    public static function onInitHubspotPullContactsTask(ContainerInterface $container): HubspotPullContactsTask
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotPullContactsTask(
            $container->get('HubspotContactService'),
            $container->get('Database'),
            new HubspotMetaService($app->erp->GetTMP()),
            $container->get('HubspotContactGateway'),
            $container->get('HubspotConfigurationService'),
            $container->get('HubspotEventService'),
            $container->get('CountryGateway'),
            new TaskMutexService($container->get('Database'))
        );
    }

    public static function onInitHubspotDealGateway(ContainerInterface $container): HubspotDealGateway
    {
        return new HubspotDealGateway($container->get('Database'));
    }

    public static function onInitHubspotDealService(ContainerInterface $container): HubspotDealService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotDealService(
            $container->get('HubspotClientService'),
            new HubspotMetaService($app->erp->GetTMP()),
            new DealValidator()
        );
    }

    public static function onInitHubspotContactPropertyService(ContainerInterface $container): HubspotContactPropertyService
    {
        return new HubspotContactPropertyService(
            $container->get('HubspotClientService'),
            $container->get('HubspotContactPropertyGateway'),
            $container->get('Database')
        );
    }

    public static function onInitHubspotContactPropertyGateway(ContainerInterface $container): HubspotContactPropertyGateway
    {
        return new HubspotContactPropertyGateway($container->get('Database'));
    }

    public static function onInitHubspotPullDealsTask(ContainerInterface $container): HubspotPullDealsTask
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotPullDealsTask(
            $container->get('HubspotDealService'),
            $container->get('Database'),
            new HubspotMetaService($app->erp->GetTMP()),
            $container->get('HubspotDealGateway'),
            $container->get('HubspotConfigurationService'),
            $container->get('HubspotEventService'),
            $container->get('HubspotContactGateway'),
            new TaskMutexService($container->get('Database'))
        );
    }

    public static function onInitHubspotDealPropertyService(ContainerInterface $container): HubspotDealPropertyService
    {
        return new HubspotDealPropertyService(
            $container->get('HubspotClientService'),
            $container->get('Database'),
            $container->get('HubspotContactPropertyGateway')
        );
    }

    public static function onInitHubspotProcessSchedulerTask(ContainerInterface $container): HubspotProcessSchedulerTask
    {
        return new HubspotProcessSchedulerTask(
            $container->get('HubspotRequestQueuesService'),
            $container->get('HubspotConfigurationService'),
            new TaskMutexService($container->get('Database'))
        );
    }

    public static function onInitRequestQueuesGateway(ContainerInterface $container): HubspotRequestQueuesGateway
    {
        return new HubspotRequestQueuesGateway($container->get('Database'));
    }

    public static function onInitRequestQueuesService(ContainerInterface $container): HubspotRequestQueuesService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotRequestQueuesService(
            $container->get('HubspotRequestQueuesGateway'),
            $container->get('Database'),
            $app,
            $container->get('HubspotEventService')
        );
    }

    public static function onInitHubspotEventService(ContainerInterface $container): HubspotEventService
    {
        return new HubspotEventService($container->get('Database'));
    }

    public static function onInitHubspotEngagementService(ContainerInterface $container): HubspotEngagementService
    {
        return new HubspotEngagementService($container->get('HubspotClientService'));
    }

    public static function onInitHubspotPullEngagementsTask(ContainerInterface $container): HubspotPullEngagementsTask
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new HubspotPullEngagementsTask(
            $container->get('Database'),
            $container->get(HubspotEngagementService::class),
            new HubspotMetaService($app->erp->GetTMP()),
            $container->get('HubspotConfigurationService'),
            $container->get('HubspotEventService'),
            $container->get('HubspotContactGateway'),
            new TaskMutexService($container->get('Database'))
        );
    }
}
