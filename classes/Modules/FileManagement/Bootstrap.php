<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement;

use ApplicationCore;
use Xentral\Components\Filesystem\FilesystemFactory;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\FileManagement\FileSystem\FileManagerStorage;
use Xentral\Modules\FileManagement\Helper\DmsPathHelper;
use Xentral\Modules\FileManagement\Manager\FileManager;
use Xentral\Modules\FileManagement\Service\FileManagementService;
use Xentral\Modules\FileManagement\Service\FileManagerGateway;
use Xentral\Modules\FileManagement\Service\FileManagerService;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'FileManagementService' => 'onInitFileManagementService',
            FileManagementService::class => 'onInitFileManagementService',
            DmsPathHelper::class => 'onInitDmsPathHelper',
            FileManager::class => 'onInitFileManager',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return FileManagementService
     */
    public static function onInitFileManagementService(ContainerInterface $container): FileManagementService
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return new FileManagementService($app->erp, $container->get('Database'), $container->get('FilesystemFactory'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return DmsPathHelper
     */
    public static function onInitDmsPathHelper(ContainerInterface $container): DmsPathHelper
    {
        return new DmsPathHelper(self::getDmsBasePath($container));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return FileManager
     */
    public static function onInitFileManager(ContainerInterface $container): FileManager
    {
        return new FileManager(
            self::onInitFileManagerGateway($container),
            self::onInitFileManagerService($container),
            self::onInitFileManagerStorage($container)
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return FileManagerStorage
     */
    public static function onInitFileManagerStorage(ContainerInterface $container): FileManagerStorage
    {

        /** @var FilesystemFactory $factory */
        $factory = $container->get(FilesystemFactory::class);
        $fileSystem = $factory->createLocal(self::getDmsBasePath($container));

        return new FileManagerStorage($container->get(DmsPathHelper::class), $fileSystem);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return FileManagerGateway
     */
    public static function onInitFileManagerGateway(ContainerInterface $container): FileManagerGateway
    {
        return new FileManagerGateway(
            $container->get('Database'),
            $container->get(DmsPathHelper::class)
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return FileManagerService
     */
    public static function onInitFileManagerService(ContainerInterface $container): FileManagerService
    {
        return new FileManagerService($container->get('Database'));
    }

    /**
     * Gets the root directory of the DMS (=DateiManagementSystem).
     *
     * The FileManagement replaces the DMS but the path
     * stays the same for backwards-compatibility.
     *
     * By default the path is <userdata_directory>/dms/<database_name>/
     *
     *
     * @param ContainerInterface $container
     *
     * @return string
     */
    private static function getDmsBasePath(ContainerInterface $container): string
    {
        /** @var ApplicationCore $app */
        $app = $container->get('LegacyApplication');

        return $app->Conf->WFuserdata
            . DIRECTORY_SEPARATOR . 'dms'
            . DIRECTORY_SEPARATOR . $app->Conf->WFdbname;
    }
}
