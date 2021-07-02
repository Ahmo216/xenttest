<?php

declare(strict_types=1);

namespace Xentral\Modules\Storage;

use User;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\Storage\Service\ArticleStockService;
use Xentral\Modules\Storage\Service\BatchStockService;
use Xentral\Modules\Storage\Service\BestBeforeStockService;
use Xentral\Modules\Storage\Service\ObjectStorageLinkService;
use Xentral\Modules\Storage\Service\SerialStockService;
use Xentral\Modules\Storage\Service\StorageBinRepository;
use Xentral\Modules\Storage\Service\StorageRepository;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'ArticleStockService' => 'onInitArticleStockService',
            'BatchStockService' => 'onInitBatchStockService',
            'BestBeforeStockService' => 'onInitBestBeforeStockService',
            'SerialStockService' => 'onInitSerialStockService',
            'ObjectStorageLinkService' => 'onInitObjectStorageLinkService',
            StorageRepository::class => 'onInitStorageRepository',
            StorageBinRepository::class => 'onInitStorageBinRepository',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ArticleStockService
     */
    public static function onInitArticleStockService(ContainerInterface $container): ArticleStockService
    {
        /** @var User $user */
        $user = $container->get('LegacyApplication')->User;
        $userName = $user->getUserByCache(false) ? $user->GetName() : '';

        return new ArticleStockService($container->get('Database'), $userName);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return BatchStockService
     */
    public static function onInitBatchStockService(ContainerInterface $container): BatchStockService
    {
        /** @var User $user */
        $user = $container->get('LegacyApplication')->User;
        $addressId = $user->getUserByCache(false) ? $user->GetAdresse() : 0;

        return new BatchStockService($container->get('Database'), $addressId);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return BestBeforeStockService
     */
    public static function onInitBestBeforeStockService(ContainerInterface $container): BestBeforeStockService
    {
        /** @var User $user */
        $user = $container->get('LegacyApplication')->User;
        $addressId = $user->getUserByCache(false) ? $user->GetAdresse() : 0;

        return new BestBeforeStockService($container->get('Database'), $addressId);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return SerialStockService
     */
    public static function onInitSerialStockService(ContainerInterface $container): SerialStockService
    {
        /** @var User $user */
        $user = $container->get('LegacyApplication')->User;
        $addressId = $user->getUserByCache(false) ? $user->GetAdresse() : 0;

        return new SerialStockService($container->get('Database'), $addressId);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ObjectStorageLinkService
     */
    public static function onInitObjectStorageLinkService(ContainerInterface $container): ObjectStorageLinkService
    {
        return new ObjectStorageLinkService($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return StorageRepository
     */
    public static function onInitStorageRepository(ContainerInterface $container): StorageRepository
    {
        return new StorageRepository($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return StorageBinRepository
     */
    public static function onInitStorageBinRepository(ContainerInterface $container): StorageBinRepository
    {
        return new StorageBinRepository($container->get('Database'));
    }
}
