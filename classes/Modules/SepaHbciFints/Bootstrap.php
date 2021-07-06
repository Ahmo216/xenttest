<?php

declare(strict_types=1);

namespace Xentral\Modules\SepaHbciFints;

use Xentral\Core\DependencyInjection\ContainerInterface;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            BankUrlRepository::class => 'onInitBankUrlRepository',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return BankUrlRepository
     */
    public static function onInitBankUrlRepository(ContainerInterface $container): BankUrlRepository
    {
        $fileSystemFactory = $container->get('FilesystemFactory');

        $baseDir =
            dirname(__DIR__, 2) . DIRECTORY_SEPARATOR .
            'Modules' . DIRECTORY_SEPARATOR .
            'SepaHbciFints' . DIRECTORY_SEPARATOR .
            'files';

        $fileSystem = $fileSystemFactory->createLocal($baseDir);

        return new BankUrlRepository(
            $fileSystem
        );
    }
}
