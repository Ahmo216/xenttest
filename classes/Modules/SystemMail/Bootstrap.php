<?php

declare(strict_types=1);

namespace Xentral\Modules\SystemMail;

use Xentral\Components\Filesystem\FilesystemFactory;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Core\LegacyConfig\ConfigLoader;
use Xentral\Modules\FileManagement\Manager\FileManager;
use Xentral\Modules\SystemMail\EmailArchive\EmailArchive;
use Xentral\Modules\SystemMail\Repository\EmailAttachmentRepository;
use Xentral\Modules\SystemMail\Repository\EmailBackupRepository;
use Xentral\Modules\SystemMail\Service\EmailBackupService;

class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            EmailBackupRepository::class => 'onInitEmailBackupRepository',
            EmailAttachmentRepository::class => 'onInitEmailAttachmentRepository',
            EmailArchive::class => 'onInitEmailArchive',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return EmailBackupRepository
     */
    public static function onInitEmailBackupRepository(ContainerInterface $container): EmailBackupRepository
    {
        return new EmailBackupRepository(
            self::onInitEmailBackupService($container),
            self::onInitEmailAttachmentRepository($container)
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return EmailBackupService
     */
    public static function onInitEmailBackupService(ContainerInterface $container): EmailBackupService
    {
        return new EmailBackupService($container->get('Database'));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return EmailAttachmentRepository
     */
    public static function onInitEmailAttachmentRepository(ContainerInterface $container): EmailAttachmentRepository
    {
        return new EmailAttachmentRepository($container->get(FileManager::class));
    }

    /**
     * @param ContainerInterface $container
     *
     * @return EmailArchive
     */
    public static function onInitEmailArchive(ContainerInterface $container): EmailArchive
    {
        $config = ConfigLoader::load();
        $archiveRootDirectory =
            rtrim($config->WFuserdata, '/') . DIRECTORY_SEPARATOR .
            'emailbackup' . DIRECTORY_SEPARATOR .
            $config->WFdbname . DIRECTORY_SEPARATOR;

        /** @var FilesystemFactory $fileSystemFactory */
        $fileSystemFactory = $container->get('FilesystemFactory');
        $fileSystem = $fileSystemFactory->createLocal($archiveRootDirectory);

        return new EmailArchive($fileSystem);
    }
}
